<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;

class Projeto extends Model
{
    use HasFactory;

    public const STATUS = [
        'Planejamento',
        'Em andamento',
        'Concluído',
        'Pausado',
    ];

    protected $fillable = [
        'user_id',
        'categoria_id',
        'titulo',
        'responsavel',
        'resumo',
        'status',
        'ano_letivo',
        'periodo_letivo',
        'data_inicio',
        'data_entrega',
        'imagem',
    ];

    protected function casts(): array
    {
        return [
            'data_inicio' => 'date',
            'data_entrega' => 'date',
        ];
    }

    public function categoria(): BelongsTo
    {
        return $this->belongsTo(Categoria::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function etapas(): HasMany
    {
        return $this->hasMany(EtapaProjeto::class)->orderBy('ordem')->orderBy('data_registro');
    }
}
