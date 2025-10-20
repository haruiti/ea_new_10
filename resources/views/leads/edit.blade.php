@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Editar Lead</h1>
    <form method="POST" action="{{ route('leads.update', $lead->id) }}">
        @csrf
        @method('PUT')
        @include('leads.form')
        <button class="btn btn-success">Atualizar</button>
    </form>
</div>
@endsection
