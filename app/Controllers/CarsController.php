<?php
namespace App\Controllers;

use App\System\AbstractController;

class CarsController extends AbstractController
{


    public function index()
    {
        $data = [
            'var1' => 'pesho',
            'var2' => 'gosho',
            'var3' => 'haralampi',

        ];

       $this->renderView('cars/index', $data);
    }

    public function create()
    {
        var_dump('This is the create method of the CarsController');
    }

    public function update()
    {
        var_dump('This is the update method of the CarsController');
    }

    public function delete()
    {
        var_dump('This is the delete method of the CarsController');
    }



}