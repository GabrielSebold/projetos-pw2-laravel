<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class EtapaProjeto extends Model
{
    use HasFactory;

    public const STATUS = [
        'Planejada',
        'Em execução',
        'Concluída',
        'Bloqueada',
    ];

    protected $fillable = [
        'projeto_id',
        'titulo',
        'data_registro',
        'status',
        'descricao',
        'problematica',
        'metodo_gestao',
        'ordem',
    ];

    protected function casts(): array
    {
        return [
            'data_registro' => 'date',
        ];
    }

    public function projeto(): BelongsTo
    {
        return $this->belongsTo(Projeto::class);
    }
}
