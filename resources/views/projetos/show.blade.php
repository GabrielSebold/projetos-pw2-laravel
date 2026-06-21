@extends('layouts.app')

@section('title', $projeto->titulo . ' - Projetos PW2')

@section('content')
    <article class="project-detail">
        <div class="detail-cover">
            @if ($projeto->imagem)
                <img src="{{ asset('storage/' . $projeto->imagem) }}" alt="Imagem do projeto {{ $projeto->titulo }}">
            @else
                <span>{{ Str::substr($projeto->titulo, 0, 2) }}</span>
            @endif
        </div>

        <div>
            <p class="eyebrow">Projeto acadêmico</p>
            <h1>{{ $projeto->titulo }}</h1>
            <p>{{ $projeto->resumo }}</p>

            <dl class="meta-grid">
                <div>
                    <dt>Categoria</dt>
                    <dd>{{ $projeto->categoria->nome }}</dd>
                </div>
                <div>
                    <dt>Responsável</dt>
                    <dd>{{ $projeto->responsavel }}</dd>
                </div>
                <div>
                    <dt>Status</dt>
                    <dd>{{ $projeto->status }}</dd>
                </div>
                <div>
                    <dt>Ano letivo</dt>
                    <dd>{{ $projeto->ano_letivo }}</dd>
                </div>
                <div>
                    <dt>Período</dt>
                    <dd>{{ $periodosLetivos[$projeto->periodo_letivo] ?? $projeto->periodo_letivo . 'º período' }}</dd>
                </div>
                <div>
                    <dt>Entrega</dt>
                    <dd>{{ optional($projeto->data_entrega)->format('d/m/Y') ?: 'Sem data' }}</dd>
                </div>
            </dl>

            <div class="form-actions">
                <a class="button primary" href="{{ route('projetos.edit', $projeto) }}">Editar</a>
                <a class="button ghost" href="{{ route('projetos.index') }}">Voltar</a>
                <form action="{{ route('projetos.destroy', $projeto) }}" method="post" onsubmit="return confirm('Excluir este projeto?')">
                    @csrf
                    @method('DELETE')
                    <button class="button danger" type="submit">Excluir</button>
                </form>
            </div>
        </div>
    </article>

    <section class="timeline-section">
        <div class="section-title">
            <div>
                <p class="eyebrow">Gestão do desenvolvimento</p>
                <h2>Etapas, atualizações e problemáticas</h2>
            </div>
        </div>

        <form class="form-panel compact" action="{{ route('projetos.etapas.store', $projeto) }}" method="post">
            @csrf

            <div class="form-grid three">
                <label>
                    Etapa
                    <input name="titulo" value="{{ old('titulo') }}" placeholder="Ex.: Modelagem do banco" required maxlength="140">
                </label>

                <label>
                    Data
                    <input type="date" name="data_registro" value="{{ old('data_registro', now()->format('Y-m-d')) }}" required>
                </label>

                <label>
                    Status
                    <select name="status" required>
                        @foreach (\App\Models\EtapaProjeto::STATUS as $status)
                            <option value="{{ $status }}" @selected(old('status') === $status)>{{ $status }}</option>
                        @endforeach
                    </select>
                </label>
            </div>

            <label>
                Atualização realizada
                <textarea name="descricao" rows="4" required maxlength="1500" placeholder="Descreva o que foi planejado, feito ou revisado nesta etapa.">{{ old('descricao') }}</textarea>
            </label>

            <div class="form-grid">
                <label>
                    Problematica encontrada
                    <textarea name="problematica" rows="4" maxlength="1500" placeholder="Ex.: dificuldade na relação entre tabelas, validação ou organização do layout.">{{ old('problematica') }}</textarea>
                </label>

                <label>
                    Método ou solução usada
                    <textarea name="metodo_gestao" rows="4" maxlength="1500" placeholder="Ex.: dividir em tarefas, testar por partes, usar migrations e validar os formulários.">{{ old('metodo_gestao') }}</textarea>
                </label>
            </div>

            <div class="form-actions">
                <button class="button primary" type="submit">Registrar etapa</button>
            </div>
        </form>

        <div class="timeline">
            @forelse ($projeto->etapas as $etapa)
                <article class="timeline-item">
                    <div class="timeline-marker"></div>

                    <div class="timeline-card">
                        <div class="timeline-head">
                            <div>
                                <span class="status">{{ $etapa->status }}</span>
                                <h3>{{ $etapa->titulo }}</h3>
                                <small>{{ $etapa->data_registro->format('d/m/Y') }}</small>
                            </div>

                            <form action="{{ route('projetos.etapas.destroy', [$projeto, $etapa]) }}" method="post" onsubmit="return confirm('Remover esta etapa?')">
                                @csrf
                                @method('DELETE')
                                <button class="button danger" type="submit">Remover</button>
                            </form>
                        </div>

                        <div class="timeline-content">
                            <div>
                                <strong>Atualização</strong>
                                <p>{{ $etapa->descricao }}</p>
                            </div>

                            @if ($etapa->problematica)
                                <div>
                                    <strong>Problematica</strong>
                                    <p>{{ $etapa->problematica }}</p>
                                </div>
                            @endif

                            @if ($etapa->metodo_gestao)
                                <div>
                                    <strong>Gestão aplicada</strong>
                                    <p>{{ $etapa->metodo_gestao }}</p>
                                </div>
                            @endif
                        </div>

                        <details class="edit-stage">
                            <summary>Editar etapa</summary>

                            <form class="form-panel compact inline" action="{{ route('projetos.etapas.update', [$projeto, $etapa]) }}" method="post">
                                @csrf
                                @method('PUT')

                                <div class="form-grid three">
                                    <label>
                                        Etapa
                                        <input name="titulo" value="{{ old('titulo', $etapa->titulo) }}" required maxlength="140">
                                    </label>

                                    <label>
                                        Data
                                        <input type="date" name="data_registro" value="{{ old('data_registro', $etapa->data_registro->format('Y-m-d')) }}" required>
                                    </label>

                                    <label>
                                        Status
                                        <select name="status" required>
                                            @foreach (\App\Models\EtapaProjeto::STATUS as $status)
                                                <option value="{{ $status }}" @selected(old('status', $etapa->status) === $status)>{{ $status }}</option>
                                            @endforeach
                                        </select>
                                    </label>
                                </div>

                                <label>
                                    Atualização realizada
                                    <textarea name="descricao" rows="3" required maxlength="1500">{{ old('descricao', $etapa->descricao) }}</textarea>
                                </label>

                                <div class="form-grid">
                                    <label>
                                        Problematica encontrada
                                        <textarea name="problematica" rows="3" maxlength="1500">{{ old('problematica', $etapa->problematica) }}</textarea>
                                    </label>

                                    <label>
                                        Método ou solução usada
                                        <textarea name="metodo_gestao" rows="3" maxlength="1500">{{ old('metodo_gestao', $etapa->metodo_gestao) }}</textarea>
                                    </label>
                                </div>

                                <label>
                                    Ordem
                                    <input type="number" name="ordem" value="{{ old('ordem', $etapa->ordem) }}" min="1" max="999">
                                </label>

                                <div class="form-actions">
                                    <button class="button dark" type="submit">Salvar etapa</button>
                                </div>
                            </form>
                        </details>
                    </div>
                </article>
            @empty
                <div class="empty">Nenhuma etapa registrada. Use o formulário acima para documentar o andamento do projeto.</div>
            @endforelse
        </div>
    </section>
@endsection
