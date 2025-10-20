@extends('layouts.app')
{{-- @include('Includes.head') --}}
@section('content')

@php date_default_timezone_set('America/Sao_Paulo') @endphp

<table id="datatable-corretivas" class="table table-striped table-bordered" cellspacing="0" width="100%">
    <thead class="thead-th">
        <tr>
            <th>Dia</th>
            <th>Semana</th>
            <th>Hora Inicial</th>
            <th>Hora Final</th>
            <th>Sala</th>
            <th>Total de Horas</th>
            <th>Data limite p/ cancelamento</th>
            <th>Cancelar/Editar</th>
        </tr>
    </thead>
    <tbody>
        @if(isset($users))
            @foreach($users as $user => $value)
                <tr>
                    <td class="td" data-th="Dia:&nbsp&nbsp">
                        {{ date('d/m/Y', strtotime($value->start_datetime)) }}
                    </td>
                    <td class="td" data-th="Semana:&nbsp&nbsp">
                        @php $dayOfWeek= date("l", strtotime($value->start_datetime))  @endphp
                        @switch($dayOfWeek)
                            @case('Monday') Segunda @break
                            @case('Tuesday') Terça @break
                            @case('Wednesday') Quarta @break
                            @case('Thursday') Quinta @break
                            @case('Friday') Sexta @break
                            @case('Saturday') Sábado @break
                            @case('Sunday') Domingo @break
                        @endswitch
                    </td>
                    <td class="td" data-th="Hora Inicial:&nbsp&nbsp">
                        {{ date("H:i",strtotime($value->start_datetime)) }}
                    </td>
                    <td class="td" data-th="Hora Final:&nbsp&nbsp">
                        {{ date("H:i",strtotime($value->end_datetime)) }}
                    </td>
                    <td class="td" data-th="Sala:&nbsp&nbsp">
                        {{ $value->last_name }}
                    </td>
                    <td class="td" data-th="Total de Horas:&nbsp&nbsp">
                        {{ $value->name }}
                    </td>
                    @php 
                        $limitCancel = strtotime('-8 hour', strtotime($value->start_datetime));
                        $now = strtotime('now');
                        $color = ($limitCancel < $now) ? 'red' : 'green';
                    @endphp
                    <td class="td" data-th="Data limite p/ cancelamento:&nbsp&nbsp" style="color: {{ $color }};">
                        {{ date('d/m/Y H:i', $limitCancel) }}
                    </td>
                    <td class="td">
                        <button type="button" title="Editar" data-toggle="modal" data-target="#modalCorretivas"
                            class="btn btn-primary" id="btn_getDados" data-id="{{ $value->hash }}">
                            <i class="fas fa-edit"></i>
                        </button>

                        <button type="button" {{ $value->hash == 'cancelado' ? "disabled" : "" }} 
                            title="Cancelar" class="btn btn-danger btn_setStatus" 
                            data-id="{{ $value->hash }}" data-status="cancelado">
                            <i class="fas fa-times-circle"></i>
                        </button>
                    </td>
                </tr>
            @endforeach
        @endif
    </tbody>
</table>

<div id="corretiva-detail-modal"></div>

@endsection

@section('scripts')
<script>
$(document).ready(function() {
    $('#datatable-corretivas').DataTable({
        dom: 'Bfrtip',
        paging: true,          // ativa a paginação
        pageLength: 10,        // 10 registros por página
        responsive: true,
        buttons: [
            { extend: 'csv', text: 'CSV', title: "Hersil Corretivas" },
            { extend: 'excel', text: 'XLSX', title: "Hersil Corretivas" }
        ],
        language: {
            "lengthMenu": "Exibindo _MENU_ Registros por página",
            "zeroRecords": "Nenhum registro encontrado",
            "info": "Mostrando página _PAGE_ de _PAGES_",
            "infoEmpty": "Nenhum registro encontrado",
            "sSearch": "Pesquisar: ",
            "oPaginate": {
                "sNext": "Próximo",
                "sPrevious": "Anterior",
                "sFirst": "Primeiro",
                "sLast": "Último"
            },
            "infoFiltered": "(filtrados de um total de _MAX_ registros)"
        }
    });
});
</script>


@endsection
