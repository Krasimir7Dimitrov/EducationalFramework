<?php
namespace App\Controllers;

use App\Model\Collections\CarsCollection;
use App\System\AbstractController;

class CarsController extends AbstractController
{

    public function index()
    {

       $data = [];

       $this->renderView('cars/index', $data);
    }

    public function create()
    {
        $data = [
            'make' => 'Mitsubishi',
            'model' => 'Pajero Mk1',
            'first_registration' => '1996',
            'transmission' => 'automatic',
            'created_at' => date('Y-m-d H:i:s')
        ];
        $record = new CarsCollection();

        try {
            $record->insert($data);
        } catch (\Throwable $e) {
            var_dump($e->getMessage());
        }
    }

    public function update()
    {
        $whereCondition = [
            'transmission' => 'automatic',
        ];

        $data = [
            'transmission' => 'manual',
            'first_registration' => '123456',
            'updated_at' => date('Y-m-d H:i:s')
        ];
        $record = new CarsCollection();
        try {
            $record->update($whereCondition, $data);
        } catch (\Throwable $e) {
            var_dump($e->getMessage());
        }
    }

    public function delete()
    {
        $where = [
            'id' => 5,
            'make' => 'ford'
        ];

        $record = new CarsCollection();

        try {
            $record->delete($where);
        } catch (\Throwable $e) {
            var_dump($e->getMessage());
        }
    }

}