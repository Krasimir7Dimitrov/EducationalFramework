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
            foreach ($results as $key => $value) {
                switch ($key) {
                    case 'make':
                        $makeArray[] = $value;
                        break;
                    case 'model':
                        $modelArray[] = $value;
                        break;
                    case 'first_registration':
                        $firstRegistrationArray[] = $value;
                        break;
                    case 'transmission':
                        $transmissionArray[] = $value;
                        break;
                    default:
                        $otherFieldsArray[] = $value;
                        break;
                }
            }
        }

        $results                       = [];
        $results['make']               = $_POST['make'];
        $results['model']              = $_POST['model'];
        $results['first_registration'] = $_POST['first_registration'];
        $results['transmission']       = $_POST['transmission'];

        var_dump($results);

//        $searchResult = $cars->getCarBySearchCriteria($results);

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