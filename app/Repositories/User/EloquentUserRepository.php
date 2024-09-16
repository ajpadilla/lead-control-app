<?php

namespace App\Repositories\User;

use App\Models\User;

class EloquentUserRepository implements UserRepository
{

    /**
     * @var User
     */
    private User $user;

    /**
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * @param array $input
     * @return User
     */
    function create(array $input): User
    {
        return $this->user->create($input);
    }

    /**
     * @param $id
     * @return User|null
     */
    public function getById($id): ?User
    {
        return $this->user->findOrFail($id);
    }

    public function update(User $user, array $attributes): bool
    {
        return $user->update($attributes);
    }

    public function delete(User $user): ?bool
    {
        return $user->delete();
    }

    public function search(array $filters = [], bool $count = false)
    {
        $query = $this->user
            ->distinct()
            ->select('users.*');


        if (isset($filters['username'])) {
            $query->ofName($filters['username']);
        }

        if ($count) {
            return $query->count('users.id');
        }

        return $query->orderBy('users.id');
    }
}
