<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Partida;
use Illuminate\Auth\Access\HandlesAuthorization;

class PartidaPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Partida');
    }

    public function view(AuthUser $authUser, Partida $partida): bool
    {
        return $authUser->can('View:Partida');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Partida');
    }

    public function update(AuthUser $authUser, Partida $partida): bool
    {
        return $authUser->can('Update:Partida');
    }

    public function delete(AuthUser $authUser, Partida $partida): bool
    {
        return $authUser->can('Delete:Partida');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:Partida');
    }

    public function restore(AuthUser $authUser, Partida $partida): bool
    {
        return $authUser->can('Restore:Partida');
    }

    public function forceDelete(AuthUser $authUser, Partida $partida): bool
    {
        return $authUser->can('ForceDelete:Partida');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Partida');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Partida');
    }

    public function replicate(AuthUser $authUser, Partida $partida): bool
    {
        return $authUser->can('Replicate:Partida');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Partida');
    }

}