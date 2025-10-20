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
                <tr>
                    <td style="height:65px">
                        <div id="msgMain"></div>
                    </td>
                </tr>
            </table>
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


