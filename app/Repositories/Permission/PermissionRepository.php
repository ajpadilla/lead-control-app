<?php

namespace App\Repositories\Permission;

use App\Models\Permission;

interface PermissionRepository
{
    function create(array $input): Permission;

    public function getById($id): ?Permission;

    public function update(Permission $permission, array $attributes): bool;

    public function delete(Permission $permission): ?bool;

    public function search(array $filters = [], bool $count = false);
}
