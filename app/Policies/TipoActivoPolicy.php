<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\TipoActivo;
use Illuminate\Auth\Access\HandlesAuthorization;

class TipoActivoPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:TipoActivo');
    }

    public function view(AuthUser $authUser, TipoActivo $tipoActivo): bool
    {
        return $authUser->can('View:TipoActivo');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:TipoActivo');
    }

    public function update(AuthUser $authUser, TipoActivo $tipoActivo): bool
    {
        return $authUser->can('Update:TipoActivo');
    }

    public function delete(AuthUser $authUser, TipoActivo $tipoActivo): bool
    {
        return $authUser->can('Delete:TipoActivo');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:TipoActivo');
    }

    public function restore(AuthUser $authUser, TipoActivo $tipoActivo): bool
    {
        return $authUser->can('Restore:TipoActivo');
    }

    public function forceDelete(AuthUser $authUser, TipoActivo $tipoActivo): bool
    {
        return $authUser->can('ForceDelete:TipoActivo');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:TipoActivo');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:TipoActivo');
    }

    public function replicate(AuthUser $authUser, TipoActivo $tipoActivo): bool
    {
        return $authUser->can('Replicate:TipoActivo');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:TipoActivo');
    }

}