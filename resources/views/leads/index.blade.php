@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Leads</h1>

    <a href="{{ route('leads.create') }}" class="btn btn-primary mb-3">Novo Lead</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-striped">
        <thead>
            <tr>
                <th>Nome</th>
                <th>Email</th>
                <th>Telefone</th>
                <th>Status</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            @foreach($leads as $lead)
            <tr>
                <td>{{ $lead->name }}</td>
                <td>{{ $lead->email }}</td>
                <td>{{ $lead->phone }}</td>
                <td>{{ $lead->status }}</td>
                <td>
                    <a href="{{ route('leads.edit', $lead->id) }}" class="btn btn-sm btn-warning">Editar</a>

                    <a href="{{ route('appointments.create', ['lead_id' => $lead->id]) }}" 
                       class="btn btn-sm btn-success">
                        Agendar
                    </a>

                    <form action="{{ route('leads.destroy', $lead->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-danger" onclick="return confirm('Excluir este lead?')">
                            Excluir
                        </button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
