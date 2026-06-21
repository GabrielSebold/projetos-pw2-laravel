<div class="form-grid">
    <label>
        Título
        <input name="titulo" value="{{ old('titulo', $projeto->titulo) }}" required maxlength="160">
    </label>

    <label>
        Responsável
        <input name="responsavel" value="{{ old('responsavel', $projeto->responsavel) }}" required maxlength="120">
    </label>
</div>

<div class="form-grid">
    <label>
        Categoria
        <select name="categoria_id" required>
            <option value="">Selecione</option>
            @foreach ($categorias as $categoria)
                <option value="{{ $categoria->id }}" @selected((int) old('categoria_id', $projeto->categoria_id) === $categoria->id)>{{ $categoria->nome }}</option>
            @endforeach
        </select>
    </label>

    <label>
        Status
        <select name="status" required>
            @foreach ($statusList as $status)
                <option value="{{ $status }}" @selected(old('status', $projeto->status) === $status)>{{ $status }}</option>
            @endforeach
        </select>
    </label>
</div>

<div class="form-grid">
    <label>
        Ano letivo
        <input type="number" name="ano_letivo" value="{{ old('ano_letivo', $projeto->ano_letivo) }}" min="2000" max="2100" required>
    </label>

    <label>
        Período letivo
        <select name="periodo_letivo" required>
            @foreach ($periodosLetivos as $numero => $rotulo)
                <option value="{{ $numero }}" @selected((int) old('periodo_letivo', $projeto->periodo_letivo) === $numero)>{{ $rotulo }}</option>
            @endforeach
        </select>
    </label>
</div>

<label>
    Resumo
    <textarea name="resumo" rows="6" required maxlength="1500">{{ old('resumo', $projeto->resumo) }}</textarea>
</label>

<div class="form-grid">
    <label>
        Data de início
        <input type="date" name="data_inicio" value="{{ old('data_inicio', optional($projeto->data_inicio)->format('Y-m-d')) }}">
    </label>

    <label>
        Data de entrega
        <input type="date" name="data_entrega" value="{{ old('data_entrega', optional($projeto->data_entrega)->format('Y-m-d')) }}">
    </label>
</div>

<label>
    Imagem do projeto
    <input type="file" name="imagem" accept="image/*">
    @if ($projeto->imagem)
        <small>Imagem atual: {{ $projeto->imagem }}</small>
    @endif
</label>

<div class="form-actions">
    <button class="button primary" type="submit">Salvar</button>
    <a class="button ghost" href="{{ route('projetos.index') }}">Cancelar</a>
</div>
