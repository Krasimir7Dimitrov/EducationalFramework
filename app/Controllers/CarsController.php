<?php
namespace App\Controllers;

use App\Model\Collections\CarsCollection;
use App\System\AbstractController;
use App\System\BaseCollection;

class CarsController extends AbstractController
{

    public function index()
    {

       $data = [];

       $this->renderView('cars/index', $data);
    }

    public function create()
    {
        var_dump('This is the create method of the CarsController');
        $data = [
            'make' => 'Mitsubishi',
            'model' => 'Pajero Mk1',
            'first_registration' => '1996',
            'transmission' => 'automatic',
            'created_at' => date('Y-m-d H:i:s')
        ];
        $record = new CarsCollection();
        $record->insert($data);
    }

    public function update()
    {
        var_dump('This is the update method of the CarsController');
        $whereCondition = [
            'transmission' => 'automatic',
        ];

        $data = [
            'transmission' => 'manual',
            'first_registration' => '123456',
            'updated_at' => date('Y-m-d H:i:s')
        ];
        $record = new CarsCollection();
        $record->update($whereCondition, $data);
    }

    public function delete()
    {
        var_dump('This is the delete method of the CarsController');

        $where = [
            'id' => 2,
        ];

        $record = new CarsCollection();
        $record->delete($where);
    }



}