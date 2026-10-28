<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Tramo;
use Illuminate\Auth\Access\HandlesAuthorization;

class TramoPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Tramo');
    }

    public function view(AuthUser $authUser, Tramo $tramo): bool
    {
        return $authUser->can('View:Tramo');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Tramo');
    }

    public function update(AuthUser $authUser, Tramo $tramo): bool
    {
        return $authUser->can('Update:Tramo');
    }

    public function delete(AuthUser $authUser, Tramo $tramo): bool
    {
        return $authUser->can('Delete:Tramo');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:Tramo');
    }

    public function restore(AuthUser $authUser, Tramo $tramo): bool
    {
        return $authUser->can('Restore:Tramo');
    }

    public function forceDelete(AuthUser $authUser, Tramo $tramo): bool
    {
        return $authUser->can('ForceDelete:Tramo');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Tramo');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Tramo');
    }

    public function replicate(AuthUser $authUser, Tramo $tramo): bool
    {
        return $authUser->can('Replicate:Tramo');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Tramo');
    }

}