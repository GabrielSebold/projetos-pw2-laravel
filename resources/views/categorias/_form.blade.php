<div class="form-grid">
    <label>
        Nome
        <input name="nome" value="{{ old('nome', $categoria->nome) }}" required maxlength="120">
    </label>

    <label>
        Cor de identificação
        <input type="color" name="cor" value="{{ old('cor', $categoria->cor ?? '#0f766e') }}" required>
    </label>
</div>

<label>
    Descrição
    <textarea name="descricao" rows="5" maxlength="1000">{{ old('descricao', $categoria->descricao) }}</textarea>
</label>

<label class="check-row">
    <input type="checkbox" name="ativo" value="1" @checked(old('ativo', $categoria->ativo ?? true))>
    Categoria ativa
</label>

<div class="form-actions">
    <button class="button primary" type="submit">Salvar</button>
    <a class="button ghost" href="{{ route('categorias.index') }}">Cancelar</a>
</div>
