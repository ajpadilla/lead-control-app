<?php

namespace App\Repositories\Permission;

use App\Models\Permission;

class EloquentPermissionRepository implements PermissionRepository
{

    /**
     * @var Permission
     */
    private Permission $permission;

    /**
     * @param Permission $permission
     */
    public function __construct(Permission $permission)
    {
        $this->permission = $permission;
    }

    /**
     * @param array $input
     * @return Permission
     */
    function create(array $input): Permission
    {
        return $this->permission->create($input);
    }

    /**
     * @param $id
     * @return Permission|null
     */
    public function getById($id): ?Permission
    {
        return $this->permission->findOrFail($id);
    }

    /**
     * @param Permission $permission
     * @param array $attributes
     * @return bool
     */
    public function update(Permission $permission, array $attributes): bool
    {
        return $permission->update($attributes);
    }

    /**
     * @param Permission $permission
     * @return bool|null
     */
    public function delete(Permission $permission): ?bool
    {
        return $permission->delete();
    }

    /**
     * @param array $filters
     * @param bool $count
     * @return mixed
     */
    public function search(array $filters = [], bool $count = false)
    {
        $query = $this->permission
            ->distinct()
            ->select('permissions.*');

        if ($count) {
            return $query->count('permissions.id');
        }

        return $query->orderBy('permissions.id');
    }
}
