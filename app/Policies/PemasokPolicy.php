<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Pemasok;
use Illuminate\Auth\Access\HandlesAuthorization;

class PemasokPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Pemasok');
    }

    public function view(AuthUser $authUser, Pemasok $pemasok): bool
    {
        return $authUser->can('View:Pemasok');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Pemasok');
    }

    public function update(AuthUser $authUser, Pemasok $pemasok): bool
    {
        return $authUser->can('Update:Pemasok');
    }

    public function delete(AuthUser $authUser, Pemasok $pemasok): bool
    {
        return $authUser->can('Delete:Pemasok');
    }

    public function restore(AuthUser $authUser, Pemasok $pemasok): bool
    {
        return $authUser->can('Restore:Pemasok');
    }

    public function forceDelete(AuthUser $authUser, Pemasok $pemasok): bool
    {
        return $authUser->can('ForceDelete:Pemasok');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Pemasok');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Pemasok');
    }

    public function replicate(AuthUser $authUser, Pemasok $pemasok): bool
    {
        return $authUser->can('Replicate:Pemasok');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Pemasok');
    }

}