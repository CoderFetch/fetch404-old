<?php namespace Fetch404\Core\Repositories;

use Fetch404\Core\Models\User;

class UsersRepository extends BaseRepository {

    public function __construct(User $model)
    {
        $this->model = $model;
        $this->itemsPerPage = 20;
    }

    /**
     * Get a user by name.
     *
     * @param $name
     * @return User
     */
    public function getByName($name)
    {
        $model = $this->model->where('name', '=', $name)->first();

        return $model;
    }

    /**
     * Get a user by email address.
     *
     * @param $email
     * @return User
     */
    public function getByEmail($email)
    {
        $model = $this->model->where('email', '=', $email)->first();

        return $model;
    }

}