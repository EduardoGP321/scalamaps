<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Carretera;
use Illuminate\Auth\Access\HandlesAuthorization;

class CarreteraPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Carretera');
    }

    public function view(AuthUser $authUser, Carretera $carretera): bool
    {
        return $authUser->can('View:Carretera');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Carretera');
    }

    public function update(AuthUser $authUser, Carretera $carretera): bool
    {
        return $authUser->can('Update:Carretera');
    }

    public function delete(AuthUser $authUser, Carretera $carretera): bool
    {
        return $authUser->can('Delete:Carretera');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:Carretera');
    }

    public function restore(AuthUser $authUser, Carretera $carretera): bool
    {
        return $authUser->can('Restore:Carretera');
    }

    public function forceDelete(AuthUser $authUser, Carretera $carretera): bool
    {
        return $authUser->can('ForceDelete:Carretera');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Carretera');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Carretera');
    }

    public function replicate(AuthUser $authUser, Carretera $carretera): bool
    {
        return $authUser->can('Replicate:Carretera');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Carretera');
    }

}