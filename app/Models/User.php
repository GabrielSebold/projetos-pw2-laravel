<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password', 'tipo_periodo'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    public const TIPOS_PERIODO = [
        'semestre' => 'Semestral',
        'trimestre' => 'Trimestral',
        'bimestre' => 'Bimestral',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function categorias(): HasMany
    {
        return $this->hasMany(Categoria::class);
    }

    public function projetos(): HasMany
    {
        return $this->hasMany(Projeto::class);
    }

    public function quantidadePeriodos(): int
    {
        return match ($this->tipo_periodo) {
            'trimestre' => 4,
            'bimestre' => 6,
            default => 2,
        };
    }

    public function periodoAtual(): int
    {
        $mes = now()->month;

        return match ($this->tipo_periodo) {
            'trimestre' => (int) ceil($mes / 3),
            'bimestre' => (int) ceil($mes / 2),
            default => (int) ceil($mes / 6),
        };
    }

    public function periodosLetivos(): array
    {
        $prefixo = match ($this->tipo_periodo) {
            'trimestre' => 'Trimestre',
            'bimestre' => 'Bimestre',
            default => 'Semestre',
        };

        return collect(range(1, $this->quantidadePeriodos()))
            ->mapWithKeys(fn ($periodo) => [$periodo => "{$periodo}º {$prefixo}"])
            ->all();
    }
}
