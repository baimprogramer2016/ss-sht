<?php

namespace App\Repositories\Kfa;

use App\Models\Kfa;

class KfaRepository implements KfaInterface
{
    private $model;

    public function __construct()
    {
        $this->model = new Kfa();
    }
    public function getQuery()
    {
        $query = $this->model;
        return $query->query();
        // return $this->model->select()->squery();
    }
}
