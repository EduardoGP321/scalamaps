<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasTenants;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class User extends Authenticatable implements FilamentUser, HasTenants
{
    use HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'name',
        'email',
        'password',
        'telefono',
        'dependency_id',
        'is_active',
        'empresa_id'
    ];
    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function getTenants(Panel $panel): Collection
    {
        return $this->empresas;
    }
    public function canAccessTenant(Model $tenant): bool
    {
        return $this->empresas->contains($tenant);
    }
    public function canAccessPanel(Panel $panel): bool
    {
        return true;
    }
    public function empresas(): BelongsToMany
    {
        return $this->belongsToMany(Empresa::class);
    }
    public function dependencia()
    {
        return $this->belongsTo(Dependencia::class, 'dependency_id');
    }
}
