{{-- resources/views/yhc/atendimentoTable.blade.php --}}

<table id="datatable-dashboard" class="table table-striped table-bordered table">
    <thead class="thead-th">
        <tr>
            <th>Data</th>
            <th>Semana</th>
            <th>Cliente</th>
            <th>Pacote</th>
            <th>Anotações</th>
        </tr>
    </thead>
    <tbody>
        @if(isset($vendas) && count($vendas) > 0)
            @foreach($vendas as $venda)
                <tr>
                    {{-- Data --}}
                    <td class="td" data-th="Data:&nbsp&nbsp">
                        <span>{{ date("d/m/Y H:i", strtotime($venda->data)) }}</span>
                    </td>

                    {{-- Semana --}}
                    <td class="td" data-th="Semana:&nbsp&nbsp">
                        <span>
                            @php $dayOfWeek = date("l", strtotime($venda->data)); @endphp
                            @if($dayOfWeek == 'Monday') Segunda
                            @elseif($dayOfWeek == 'Tuesday') Terça
                            @elseif($dayOfWeek == 'Wednesday') Quarta
                            @elseif($dayOfWeek == 'Thursday') Quinta
                            @elseif($dayOfWeek == 'Friday') Sexta
                            @elseif($dayOfWeek == 'Saturday') Sábado
                            @elseif($dayOfWeek == 'Sunday') Domingo
                            @endif
                        </span>
                    </td>

                    {{-- Cliente --}}
                    <td class="td" data-th="Cliente:&nbsp&nbsp">
                        <span>{{ $venda->nome ?? $venda->cliente_nome ?? '-' }}</span>
                    </td>

                    {{-- Pacote --}}
                    <td class="td" data-th="Pacote:&nbsp&nbsp">
                        <span>{{ $venda->p_nome ?? $venda->pacote_nome ?? '-' }}</span>
                    </td>

                    {{-- Anotações --}}
                    <td class="td" data-th="Anotações:&nbsp&nbsp">
                        <span>{{ $venda->notes ?? $venda->observacao ?? '-' }}</span>
                    </td>
                </tr>
            @endforeach
        @else
            <tr>
                <td colspan="5" class="text-center">Nenhum atendimento encontrado.</td>
            </tr>
        @endif
    </tbody>
</table>
