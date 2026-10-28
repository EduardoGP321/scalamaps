<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\ParteObra;
use Illuminate\Auth\Access\HandlesAuthorization;

class ParteObraPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:ParteObra');
    }

    public function view(AuthUser $authUser, ParteObra $parteObra): bool
    {
        return $authUser->can('View:ParteObra');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:ParteObra');
    }

    public function update(AuthUser $authUser, ParteObra $parteObra): bool
    {
        return $authUser->can('Update:ParteObra');
    }

    public function delete(AuthUser $authUser, ParteObra $parteObra): bool
    {
        return $authUser->can('Delete:ParteObra');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:ParteObra');
    }

    public function restore(AuthUser $authUser, ParteObra $parteObra): bool
    {
        return $authUser->can('Restore:ParteObra');
    }

    public function forceDelete(AuthUser $authUser, ParteObra $parteObra): bool
    {
        return $authUser->can('ForceDelete:ParteObra');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:ParteObra');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:ParteObra');
    }

    public function replicate(AuthUser $authUser, ParteObra $parteObra): bool
    {
        return $authUser->can('Replicate:ParteObra');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:ParteObra');
    }

}