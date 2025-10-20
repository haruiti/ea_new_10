<div class="mb-3">
    <label>Nome</label>
    <input type="text" name="name" class="form-control" value="{{ old('name', $lead->name ?? '') }}" required>
</div>

<div class="mb-3">
    <label>Email</label>
    <input type="email" name="email" class="form-control" value="{{ old('email', $lead->email ?? '') }}">
</div>

<div class="mb-3">
    <label>Telefone</label>
    <input type="text" name="phone" class="form-control" value="{{ old('phone', $lead->phone ?? '') }}">
</div>

<div class="mb-3">
    <label>Origem</label>
    <input type="text" name="source" class="form-control" value="{{ old('source', $lead->source ?? '') }}">
</div>

<div class="mb-3">
    <label>Status</label>
    <select name="status" class="form-control">
        @foreach(['novo', 'em_contato', 'agendado', 'convertido', 'perdido'] as $status)
            <option value="{{ $status }}" {{ old('status', $lead->status ?? 'novo') == $status ? 'selected' : '' }}>
                {{ ucfirst($status) }}
            </option>
        @endforeach
    </select>
</div>

<div class="mb-3">
    <label>Observações</label>
    <textarea name="notes" class="form-control">{{ old('notes', $lead->notes ?? '') }}</textarea>
</div>
