@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Agendamentos</h1>

    <a href="{{ route('appointments.create') }}" class="btn btn-primary mb-3">Novo Agendamento</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($appointments->isEmpty())
        <p>Nenhum agendamento encontrado.</p>
    @else
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Lead</th>
                    <th>Data</th>
                    <th>Hora</th>
                    <th>Observações</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach($appointments as $appointment)
                <tr>
                    <td>{{ $appointment->lead ? $appointment->lead->name : '—' }}</td>
                    <td>{{ \Carbon\Carbon::parse($appointment->date)->format('d/m/Y') }}</td>
                    <td>{{ $appointment->time }}</td>
                    <td>{{ $appointment->notes ?? '—' }}</td>
                    <td>
                        <a href="{{ route('appointments.edit', $appointment->id) }}" class="btn btn-sm btn-warning">Editar</a>
                        <form action="{{ route('appointments.destroy', $appointment->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger" onclick="return confirm('Excluir este agendamento?')">Excluir</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
