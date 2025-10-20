<table id="datatable-despesa" class="table table-striped table-bordered table">
    <thead class="thead-th">
        <tr>
        <th>Data</th>
            <th>Categoria</th>
            <th>Despesa</th>
            <th>Valor</th>
            <th>Ações</th>
        </tr>
    </thead>
    <tbody>
        @if(isset($despesas))

        @foreach($despesas as $despesa => $value)
        <tr>
            <td class="td" data-th="Data:&nbsp&nbsp">
                <span>
                    @if(isset($value->data))
                        {{ date("d/m/Y H:i", strtotime($value->data)) }}
                    @endif
                </span>
            </td>

            <td class="td" data-th="Anotação:&nbsp&nbsp">
                <span >
                {{ $value->categoria }}
                </span>
            </td>
            <td class="td" data-th="Valor:&nbsp&nbsp">
                <span>
                {{ $value->despesa }}
                </span>
            </td>
            <td class="td" data-th="Valor:&nbsp&nbsp">
                <span>
                {{ $value->valor }}
                </span>
            </td>
            <td class="td" data-th="Ações:&nbsp&nbsp">

                <button
                    class="btn btn-primary" id="btn_getDados" onClick="editarDespesa('{{ $value->id }}','{{date('d-m-Y H:i', strtotime($value->data))}}','{{$value->categoria}}','{{$value->despesa}}','{{$value->valor}}')"><i
                        class="fas fa-edit"></i>
                </button>
                <button
                    class="btn btn-primary" id="btn_excluir" onClick="excluirDespesa('{{ $value->id }}')"><i
                        class="fas fa-times-circle"></i>
                </button>
                <span id='loadingSessao{{ $value->id }}' style="display:none">
                    <img src="{{asset('imagens/loading.gif')}}" style="width:35px;height:35px; position:relative; left:0;top: 0px;">
                </span>

            </td>

        </tr>
        @endforeach
        @endif
    </tbody>
</table>
