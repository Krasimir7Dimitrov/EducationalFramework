<?php

namespace App\Controllers;

use App\Model\Collections\CarsCollection;
use App\System\AbstractController;

class DefaultController extends AbstractController
{
    public function index()
    {
        $cars = new CarsCollection();
        $carResults = $cars->getAllCars();

        $makeArray = [];
        $modelArray = [];
        $firstRegistrationArray = [];
        $transmissionArray = [];
        $otherFieldsArray = [];

        foreach ($carResults as $results) {
            $makeArray[]              = strtolower(ucfirst($results['makename']));
            $modelArray[]             = strtolower(ucfirst($results['modelname']));
            $firstRegistrationArray[] = strtolower(ucfirst($results['first_registration']));
            $transmissionArray[]      = strtolower(ucfirst($results['transmission']));
        }

        $results                       = [];
        $results[]  = 'make = ' . $_POST['make'];
        $results[]  = 'model = ' . $_POST['model'];
        $results[]  = 'first_registration = ' . $_POST['first_registration'];
        $results[]  = 'transmission = ' . $_POST['transmission'];


//        $searchResult = $cars->getCarBySearchCriteria($data, $results);

        $this->renderView('default/index', [
            'carResults' => $carResults,
            'makeArray'  => array_unique($makeArray),
            'modelArray' => array_unique($modelArray),
            'firstRegistrationArray' => array_unique($firstRegistrationArray),
            'transmissionArray'      => array_unique($transmissionArray),
            'results'                => $results
        ]);
    }
}