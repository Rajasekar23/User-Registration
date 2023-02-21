<?php

namespace App\Core\Interfaces;

use App\Core\CoreModel;
use App\Core\Data\CustomDto;

/**
 * Crud.
 */
interface CrudInterface
{
    public function getAll();

    public function paginate();

    public function getByID(int $id);

    public function saveData(CustomDto $dto);

    public function updateData(CustomDto $dto, CoreModel $model);

    public function deleteById(int $id);

}
