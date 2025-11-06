@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2 class="mb-3">Editar Agendamento</h2>

    <form action="{{ route('appointments.update', $appointment->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group mb-3">
            <label for="lead_id">Cliente</label>
            <select name="lead_id" class="form-control" required>
                @foreach($leads as $lead)
                    <option value="{{ $lead->id }}" {{ $lead->id == $appointment->lead_id ? 'selected' : '' }}>
                        {{ $lead->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="date">Data</label>
                <input type="date" name="date" class="form-control" value="{{ $appointment->date }}" required>
            </div>
            <div class="col-md-6 mb-3">
                <label for="time">Hora</label>
                <input type="time" name="time" class="form-control" value="{{ $appointment->time }}" required>
            </div>
        </div>

        <div class="form-group mb-3">
            <label for="status">Status</label>
            <input type="text" name="status" class="form-control" value="{{ $appointment->status }}">
        </div>

        <div class="form-group mb-3">
            <label for="notes">Anotações</label>
            <textarea name="notes" class="form-control" rows="3">{{ $appointment->notes }}</textarea>
        </div>

        <button type="submit" class="btn btn-success">Atualizar</button>
        <a href="{{ route('appointments.index') }}" class="btn btn-secondary">Voltar</a>
    </form>
</div>
@endsection
