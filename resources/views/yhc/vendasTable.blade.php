<table id="datatable-vendas" class="table table-striped table-bordered table">
    <thead class="thead-th">
        <tr>
            <th>Data</th>
            <th>Pacote</th>
            <th>Forma Pagamento</th>
            <th>Valor</th>
            <th>Valor Pago</th>
            <th>Ações</th>
        </tr>
    </thead>
    <tbody>
        @if(isset($vendas))

        @foreach($vendas as $venda => $value)
        <tr id="tr{{$value->id }}">
            <td class="td" data-th="Data:&nbsp&nbsp">
                <span>
                {{ date("d/m/Y H:i",strtotime($value->data)) }}
                </span>
            </td>

            <td class="td" data-th="Pacote:&nbsp&nbsp">
                <span >
                {{ $value->p_nome }}
                </span>
            </td>
            <td class="td" data-th="Forma Pagamento:&nbsp&nbsp">
                <span>

                {{ $value->forma_pagamento }}
                </span>
            </td>
            <td class="td" data-th="Valor:&nbsp&nbsp">
                <span>
                {{ $value->valor_pacote }}
                </span>
            </td>
            <td class="td" data-th="Valor Pago:&nbsp&nbsp">
                <span>
                {{ $value->valor_pago }}
                </span>
            </td>
            <td class="td" data-th="Ações:&nbsp&nbsp">
                <button
                    class="btn btn-primary" id="btn_getDados" onClick="getSessao('{{ $value->id }}')"><i
                        class="fas fa-eye"></i>
                </button>
                <button
                    class="btn btn-primary" id="btn_getDados" onClick="addVendaModal('{{ $value->id }}')"><i
                        class="fas fa-edit"></i>
                </button>
                <button
                    class="btn btn-primary" id="btn_getDados" onClick="addSessao('{{ $value->id }}')"><i
                        class="fas fa-plus"></i>
                </button>
                <button
                    class="btn btn-primary" id="btn_excluir" onClick="confirmaremove('excluirVenda', 'venda', '{{ $value->id }}')"><i
                        class="fas fa-times-circle"></i>
                </button>
                <span id='loadingVenda{{ $value->id }}' style="display:none">
                    <img src="{{asset('imagens/loading.gif')}}" style="width:35px;height:35px; position:relative; left:0;top: 0px;">
                </span>

            </td>

        </tr>
        @endforeach
        @endif
    </tbody>
</table>
