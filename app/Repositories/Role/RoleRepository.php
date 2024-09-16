<?php

namespace App\Repositories\Role;

use App\Models\Role;

interface RoleRepository
{
    function create(array $input): Role;

    public function getById($id): ?Role;

    public function update(Role $role, array $attributes): bool;

    public function delete(Role $role): ?bool;

    public function search(array $filters = [], bool $count = false);
}
