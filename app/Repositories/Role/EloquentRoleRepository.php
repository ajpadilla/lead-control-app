<?php

namespace App\Repositories\Role;

use App\Models\Role;

class EloquentRoleRepository implements RoleRepository
{
    /**
     * @var Role
     */
    private $role;

    /**
     * @param Role $role
     */
    public function __construct(Role $role)
    {
        $this->role = $role;
    }

    /**
     * @param array $input
     * @return Role
     */
    function create(array $input): Role
    {
        return $this->role->create($input);
    }

    /**
     * @param $id
     * @return Role|null
     */
    public function getById($id): ?Role
    {
        return $this->role->findOrFail($id);
    }

    /**
     * @param Role $role
     * @param array $attributes
     * @return bool
     */
    public function update(Role $role, array $attributes): bool
    {
        return $role->update($attributes);
    }

    /**
     * @param Role $role
     * @return bool|null
     */
    public function delete(Role $role): ?bool
    {
        return $role->delete();
    }

    /**
     * @param array $filters
     * @param bool $count
     * @return mixed
     */
    public function search(array $filters = [], bool $count = false)
    {
        $query = $this->role
            ->distinct()
            ->select('roles.*');

        if ($count) {
            return $query->count('roles.id');
        }

        return $query->orderBy('roles.id');
    }
}
