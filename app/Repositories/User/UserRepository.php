<?php

namespace App\Repositories\User;

use App\Models\User;

interface UserRepository
{
    function create(array $input): User;

    public function getById($id): ?User;

    public function update(User $user, array $attributes): bool;

    public function delete(User $user): ?bool;

    public function search(array $filters = [], bool $count = false);
}
