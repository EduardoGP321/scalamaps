<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

trait HasUserOwnership
{
    /**
     * El "boot" del Trait se ejecuta automáticamente al instanciar el modelo.
     */
    protected static function bootHasUserOwnership(): void
    {
        static::addGlobalScope('user_ownership', function (Builder $builder) {
            $user = auth()->user();

            // 1. Si no hay usuario autenticado (consola/seeder/jobs) → sin filtro
            if (! $user) {
                return;
            }

            // 2. Los roles globales de administración (empresa_id = NULL) nunca se filtran
            if ($user->hasRole('super_admin')) {
                return;
            }

            // 3. Para el resto de usuarios, delegar al método que cada modelo sobreescribe
            static::applyUserOwnershipFilter($builder, $user);
        });
    }

    /**
     * Filtro por defecto: relación directa many-to-many con 'users'.
     * Usado por Contrato (tabla pivote contrato_user).
     * Los modelos Tramo y Carretera sobreescriben este método para la cascada.
     */
    protected static function applyUserOwnershipFilter(Builder $builder, $user): void
    {
        $builder->whereHas('users', function (Builder $query) use ($user) {
            $query->where('users.id', $user->id);
        });
    }
}
