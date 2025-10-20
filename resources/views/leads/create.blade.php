@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Novo Lead</h1>
    <form method="POST" action="{{ route('leads.store') }}">
        @csrf
        @include('leads.form')
        <button class="btn btn-success">Salvar</button>
    </form>
</div>
@endsection
