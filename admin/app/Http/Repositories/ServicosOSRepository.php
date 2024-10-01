<?php

namespace App\Http\Repositories;

use App\Models\ServicosOS;

class ServicosOSRepository extends ServicosOS
{
    protected $model;

    public function find($id)
    {
        return $this->model->find($id);
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

}