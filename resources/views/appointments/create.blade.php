@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2 class="mb-3">Novo Agendamento</h2>

    <form action="{{ route('appointments.store') }}" method="POST">
        @csrf

        <div class="form-group mb-3">
            <label for="lead_id">Cliente</label>
            <select name="lead_id" class="form-control" required>
                <option value="">Selecione...</option>
                @foreach($leads as $lead)
                    <option value="{{ $lead->id }}" {{ isset($selectedLead) && $selectedLead == $lead->id ? 'selected' : '' }}>
                        {{ $lead->name }}
                    </option>
                @endforeach
            </select>

        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="date">Data</label>
                <input type="date" name="date" class="form-control" required>
            </div>
            <div class="col-md-6 mb-3">
                <label for="time">Hora</label>
                <input type="time" name="time" class="form-control" required>
            </div>
        </div>

        <div class="form-group mb-3">
            <label for="status">Status</label>
            <input type="text" name="status" class="form-control" placeholder="Ex: Confirmado, Pendente, Cancelado">
        </div>

        <div class="form-group mb-3">
            <label for="notes">Anotações</label>
            <textarea name="notes" class="form-control" rows="3" placeholder="Observações opcionais..."></textarea>
        </div>

        <button type="submit" class="btn btn-success">Salvar</button>
        <a href="{{ route('appointments.index') }}" class="btn btn-secondary">Voltar</a>
    </form>
</div>
@endsection
