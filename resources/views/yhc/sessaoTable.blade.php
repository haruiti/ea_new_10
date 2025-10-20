<table id="datatable-sessao" class="table table-striped table-bordered table">
    <thead class="thead-th">
        <tr>
            <th>Data</th>
            <th>Anotação</th>
            <th>Valor</th>
            <td>Ações</td>
        </tr>
    </thead>
    <tbody>
        @if(isset($sessoes))

        @foreach($sessoes as $sessao => $value)
        <tr id="{{$value->id }}">
            <td class="td" data-th="Data:&nbsp&nbsp">
                <span>
                    @if(isset($value->t_data))
                        {{ date("d/m/Y H:i", strtotime($value->t_data)) }}
                    @endif
                </span>
            </td>

            <td class="td" data-th="Anotação:&nbsp&nbsp">
                <span >
                {{ $value->t_note }}
                </span>
            </td>
            <td class="td" data-th="Valor:&nbsp&nbsp">
                <span>
                {{ $value->valor }}
                </span>
            </td>
            <td class="td" data-th="Ações:&nbsp&nbsp">

                <button
                    class="btn btn-primary" id="btn_getDados" onClick="editarSessao('{{ $value->id }}')"><i
                        class="fas fa-edit"></i>
                </button>
                <button
                    class="btn btn-primary" id="btn_excluir" onClick="confirmaremove('excluirSessao', 'sessão', '{{ $value->id }}', '{{$value->venda_id}}')"><i
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
