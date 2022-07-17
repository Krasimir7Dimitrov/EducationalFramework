<?php
namespace App\Controllers;

use App\Model\Collections\CarsCollection;
use App\System\AbstractController;

class CarsController extends AbstractController
{

    /**
     * @var string
     */

    public function index()
    {

        $data = [
            'make' => 'fordssssssssssss',
            'model' => 'mustang',
            'first_registration' => '1986',
            'transmission' => 1,
            'updated_at' => date("Y-m-d H:i:s")
        ];

        $hi = new CarsCollection();
        $hi->insert($data);
        $hi->update(57, $data);
        $hi->delete(58);

       $data = [];

       $this->renderView('cars/index', $data);
    }

    public function create()
    {
        var_dump('This is the create method of the CarsController');

        try {
            $data = [
                'make' => 'ford_created_created',
                'model' => 'mustang_created_created',
                'first_registration' => '1986',
                'transmission' => 1,
                'updated_at' => date("Y-m-d H:i:s")
            ];
            $inst = new CarsCollection();
            $inst->insert($data);
            echo 'Success create';
        } catch (\Throwable $t) {
            echo 'Something went wrong ' . $t;
        }
    }

    public function update()
    {
        var_dump('This is the update method of the CarsController');

        try {
            $id = 68;
            $where = [
                'make' => 'fordfff',
                'model' => 'mustangfff',
                'first_registration' => '1986'
            ];

            $data = [
                'make' => 'fordfff',
                'model' => 'mustangfff',
                'first_registration' => '1986',
                'transmission' => 1,
                'updated_at' => date("Y-m-d H:i:s")
            ];
            $inst = new CarsCollection();
            $inst->update($data, null, $id);
            echo 'Success update';
        } catch (\Throwable $t) {
            echo $t;
        }
    }

    public function delete()
    {
        var_dump('This is the delete method of the CarsController');

        try {
            $inst = new CarsCollection();
            $inst->delete( 72);
            echo 'Success delete';
        } catch (\Throwable $t) {
            echo 'Something went wrong ' . $t;
        }
    }



}