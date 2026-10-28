<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\CatalogoConcepto;
use Illuminate\Auth\Access\HandlesAuthorization;

class CatalogoConceptoPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:CatalogoConcepto');
    }

    public function view(AuthUser $authUser, CatalogoConcepto $catalogoConcepto): bool
    {
        return $authUser->can('View:CatalogoConcepto');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:CatalogoConcepto');
    }

    public function update(AuthUser $authUser, CatalogoConcepto $catalogoConcepto): bool
    {
        return $authUser->can('Update:CatalogoConcepto');
    }

    public function delete(AuthUser $authUser, CatalogoConcepto $catalogoConcepto): bool
    {
        return $authUser->can('Delete:CatalogoConcepto');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:CatalogoConcepto');
    }

    public function restore(AuthUser $authUser, CatalogoConcepto $catalogoConcepto): bool
    {
        return $authUser->can('Restore:CatalogoConcepto');
    }

    public function forceDelete(AuthUser $authUser, CatalogoConcepto $catalogoConcepto): bool
    {
        return $authUser->can('ForceDelete:CatalogoConcepto');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:CatalogoConcepto');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:CatalogoConcepto');
    }

    public function replicate(AuthUser $authUser, CatalogoConcepto $catalogoConcepto): bool
    {
        return $authUser->can('Replicate:CatalogoConcepto');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:CatalogoConcepto');
    }

}