<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\QueryException;
use App\Exceptions\UserServiceException;

class UserService
{
    /**
     * Retrieve all users.
     *
     * @return Collection<int, User>
     */
    public function list(): Collection
    {
        return User::all();
    }

    /**
     * Create a new user with the given attributes.
     *
     * @param  array<string, mixed>  $attrs
     * @throws UserServiceException
     */
    public function create(array $attrs): User
    {
        try {
            return User::create($attrs);
        } catch (QueryException $e) {
            throw new UserServiceException('Unable to create user: ' . $e->getMessage(), 0, $e);
        }
    }

    /**
     * Update an existing user with the given attributes.
     *
     * @param  User  $user
     * @param  array<string, mixed>  $attrs
     * @throws UserServiceException
     */
    public function update(User $user, array $attrs): User
    {
        try {
            $user->fill($attrs);
            $user->save();

            return $user;
        } catch (QueryException $e) {
            throw new UserServiceException('Unable to update user: ' . $e->getMessage(), 0, $e);
        }
    }

    /**
     * Delete the given user.
     *
     * @param  User  $user
     * @throws UserServiceException
     */
    public function delete(User $user): void
    {
        try {
            $user->delete();
        } catch (QueryException $e) {
            throw new UserServiceException('Unable to delete user: ' . $e->getMessage(), 0, $e);
        }
    }
}
