<?php

namespace App\Controllers;

use App\Model\Collections\CarsCollection;
use App\Model\Collections\MakesCollection;
use App\Model\Collections\ModelsCollection;
use App\System\AbstractController;

class DefaultController extends AbstractController
{
    public function index()
    {
        $cars = new CarsCollection();
        $carResults = $cars->getAllCars();

        $makes = new MakesCollection();
        $allMakes = $makes->getAllMakes();

        $models = new ModelsCollection();
        $allModels = $models->getAllModels();

        $firstRegistrationArray = [];
        $transmissionArray      = [];

        foreach ($carResults as $result) {
            $firstRegistrationArray[]  = strtolower(ucfirst($result['first_registration']));
            $transmissionArray[]       = strtolower(ucfirst($result['transmission']));
        }

        $searchResult = [];
        $method = $_SERVER['REQUEST_METHOD'];
        if ($method == 'POST'){
            $where   = [];
            $where[] = 'AND c.`make_id` = ' . $_POST['make'];
            $where[] = 'c.`model_id` = ' . $_POST['model'];
            $where[] = '`first_registration` = ' . $_POST['first_registration'];
            $where[] = '`transmission` = ' . '\'' . $_POST['transmission'] . '\'';

            $searchResult = $cars->getCarBySearchCriteria($where);
        }

        $this->renderView('default/index', [
            'searchResult'           => $searchResult,
            'allMakes'               => $allMakes,
            'allModels'              => $allModels,
            'firstRegistrationArray' => array_unique($firstRegistrationArray),
            'transmissionArray'      => array_unique($transmissionArray),
        ]);
    }
}