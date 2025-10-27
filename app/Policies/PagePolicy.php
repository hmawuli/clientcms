<?php

namespace App\Policies;

use App\Models\Page;
use App\Models\User;

class PagePolicy
{
    /**
     * Determine if the user can view any pages.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine if the user can view the page.
     */
    public function view(User $user, Page $page): bool
    {
        return $user->id === $page->user_id || $user->isAdmin();
    }

    /**
     * Determine if the user can create pages.
     */
    public function create(User $user): bool
    {
        return $user->isClient() || $user->isAdmin();
    }

    /**
     * Determine if the user can update the page.
     */
    public function update(User $user, Page $page): bool
    {
        return $user->id === $page->user_id || $user->isAdmin();
    }

    /**
     * Determine if the user can delete the page.
     */
    public function delete(User $user, Page $page): bool
    {
        return $user->id === $page->user_id || $user->isAdmin();
    }

    /**
     * Determine if the user can restore the page.
     */
    public function restore(User $user, Page $page): bool
    {
        return $user->id === $page->user_id || $user->isAdmin();
    }

    /**
     * Determine if the user can permanently delete the page.
     */
    public function forceDelete(User $user, Page $page): bool
    {
        return $user->isAdmin();
    }
}
