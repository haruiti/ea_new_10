@extends('layouts.app')
@include('includes.head')

@section('content')

@php date_default_timezone_set('America/Sao_Paulo') @endphp

<div class="container">
    <div class="panel panel-default">
        <div class="panel-body">
            <div class="row align-items-center">
                <div class="col-sm-1 col-md-1">
                    <label for="cliente">Cliente:</label>
                </div>

                <div class="col-sm-4 col-md-4">
                    <select name="cliente" id="cliente" class="js-example-basic-single form-control" style="width: 100%">
                        @foreach($clientes as $cliente)
                            <option value="{{$cliente->id}}">{{$cliente->nome}}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-sm-5 col-md-5 d-flex align-items-center gap-1">
                    <button type="button" class="btn btn-info btn-sm" onclick="addClienteModal()" id="addcliente">
                        <i class="fas fa-plus"></i>Cliente
                    </button>
                    <button type="button" class="btn btn-info btn-sm" onclick="editClienteModal()" id="editcliente">
                        <i class="fas fa-edit"></i>Cliente
                    </button>
                    <button type="button" class="btn btn-info btn-sm" onclick="confirmaremove('deleteCliente', 'cliente')" id="deletecliente">
                        <i class="fas fa-times-circle"></i>Cliente
                    </button>
                    <button type="button" class="btn btn-info btn-sm" onclick="getVenda()" id="verifyservice">
                        <i class="fas fa-eye"></i>Serviço
                    </button>
                    <button type="button" class="btn btn-info btn-sm" onclick="addVendaModal()" id="addservice">
                        <i class="fas fa-plus"></i>Serviço
                    </button>
                </div>

                <div class="col-sm-1 col-md-1">
                    <span id='loading' style="display:none">
                        <img src="{{asset('imagens/loading.gif')}}" style="width:35px;height:35px;">
                    </span>
                    <label for="clienteCount" style="font-size:9px">{{$countClientes}}</label>
                </div>
            </div>

            <table>
                <tr><td style="height:65px"><div id="msgMain"></div></td></tr>
            </table>
        </div>
    </div>
</div>

{{-- 🔽 NOVO BLOCO: CONVERSÃO DE LEADS --}}
<div class="container mt-3" id="leadConverterContainer">
    <div class="panel panel-default shadow-sm rounded">
        <div class="panel-heading bg-info text-white p-2">
            <strong>Conversão de Leads</strong>
        </div>
        <div class="panel-body">
            <div id="leadInfo" class="text-muted small mb-2">
                <em>Buscando lead correspondente...</em>
            </div>
            <button id="convertLeadBtn" class="btn btn-success btn-sm" style="display:none">
                <i class="fas fa-sync-alt"></i> Converter Lead em Paciente
            </button>
        </div>
    </div>
</div>

<div class="container" id="panelContainer">
    <div id="table"></div>
</div><br>

<div class="container" id="panelContainer">
    <div class="panel panel-default">
        <div class="panel-body">
            <div id="tableSessao"></div>
        </div>
    </div>
</div>

<div id="modal"></div>

@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const clienteSelect = document.getElementById('cliente');
    const leadInfo = document.getElementById('leadInfo');
    const convertBtn = document.getElementById('convertLeadBtn');

    async function verificarLeadRelacionado(clienteId) {
        leadInfo.textContent = '🔍 Verificando lead correspondente...';
        convertBtn.style.display = 'none';
        try {
            const res = await fetch(`/api/verificar-lead/${clienteId}`);
            const data = await res.json();
            if (data.success && data.lead) {
                leadInfo.innerHTML = `
                    <strong>Lead encontrado:</strong> ${data.lead.name}<br>
                    <small>Origem: ${data.lead.source} | Criado em: ${data.lead.created_at}</small>
                `;
                convertBtn.style.display = 'inline-block';
                convertBtn.onclick = () => converterLead(data.lead.id);
            } else {
                leadInfo.innerHTML = '<em>Nenhum lead relacionado a este cliente.</em>';
            }
        } catch (err) {
            leadInfo.innerHTML = '<span class="text-danger">Erro ao verificar lead.</span>';
        }
    }

    async function converterLead(leadId) {
        if (!confirm('Deseja realmente converter este lead em paciente?')) return;
        leadInfo.textContent = '⏳ Convertendo lead...';
        try {
            const res = await fetch(`/converter-lead/${leadId}`, { method: 'POST', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }});
            const data = await res.json();
            if (data.success) {
                leadInfo.innerHTML = '<span class="text-success">✅ Lead convertido com sucesso!</span>';
                setTimeout(() => location.reload(), 1500);
            } else {
                leadInfo.innerHTML = '<span class="text-danger">❌ ' + (data.message || 'Erro ao converter lead') + '</span>';
            }
        } catch (err) {
            leadInfo.innerHTML = '<span class="text-danger">❌ Erro inesperado.</span>';
        }
    }

    clienteSelect.addEventListener('change', e => verificarLeadRelacionado(e.target.value));
});
</script>
@endsection
