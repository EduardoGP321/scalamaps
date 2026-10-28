<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Dependencia;
use Illuminate\Auth\Access\HandlesAuthorization;

class DependenciaPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Dependencia');
    }

    public function view(AuthUser $authUser, Dependencia $dependencia): bool
    {
        return $authUser->can('View:Dependencia');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Dependencia');
    }

    public function update(AuthUser $authUser, Dependencia $dependencia): bool
    {
        return $authUser->can('Update:Dependencia');
    }

    public function delete(AuthUser $authUser, Dependencia $dependencia): bool
    {
        return $authUser->can('Delete:Dependencia');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:Dependencia');
    }

    public function restore(AuthUser $authUser, Dependencia $dependencia): bool
    {
        return $authUser->can('Restore:Dependencia');
    }

    public function forceDelete(AuthUser $authUser, Dependencia $dependencia): bool
    {
        return $authUser->can('ForceDelete:Dependencia');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Dependencia');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Dependencia');
    }

    public function replicate(AuthUser $authUser, Dependencia $dependencia): bool
    {
        return $authUser->can('Replicate:Dependencia');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Dependencia');
    }

}