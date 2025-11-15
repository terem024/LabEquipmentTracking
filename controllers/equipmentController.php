<?php
require_once __DIR__ . '/../models/EquipmentModel.php';

class EquipmentController
{
    private $model;

    public function __construct($db)
    {
        $this->model = new EquipmentModel($db);
    }

    public function listAll()
    {
        return $this->model->getAll();
    }
}
