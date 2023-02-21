<?php

namespace App\Domain\Users\Repository;

use App\Core\CoreRepository;
use App\Models\User;
/**
 * UserRepository Class.
 */
class UserRepository extends CoreRepository
{



    /**
     * Defind the Model Class
     * {@inheritdoc}
     *
     * @return string
     */
    public function model()
    {
        return User::class;
    }

    /**
     * User validator:class.
     *
     * @return string
     */
    public function validator()
    {
        return UserValidator::class;
    }

    /**
     * Get all Users.
     *
     * @return User $users
     */
    public function getAll()
    {
        return $this->model->get();
    }

}
