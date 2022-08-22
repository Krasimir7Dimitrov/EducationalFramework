<?php
namespace App\Controllers;

use App\Model\Collections\CarsCollection;
use App\Model\Collections\MakesCollection;
use App\Model\Collections\ModelsCollection;
use App\System\AbstractController;

class CarsController extends AbstractController
{

    public function index()
    {
        $this->renderView('cars/index', []);
    }

    public function create()
    {
        if (!$this->isLoggedIn())
        {
            header("Location: {$this->config['baseUrl']}"); die();
        }

        $messageArray           = [];

        $data = [];
        $data['make_id']            = $_POST['make'] ?? 0;
        $data['model_id']           = $_POST['model'] ?? 0;
        $data['first_registration'] = $_POST['first_registration'] ?? 2000;
        $data['transmission']       = $_POST['transmission'] ?? 'Nqma transmission';
        $data['created_at']         = date('Y-m-d H:s:i');

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

        $insert = $cars->insertCar($data);

        if ($insert) {
            $messageArray[] = 'Successfully created record in database';
        } else {
            $messageArray[] = 'Something went wrong';
        }

        $this->renderView('cars/create', [
            'carResults'             => $carResults,
            'allMakes'               => $allMakes,
            'allModels'              => $allModels,
            'firstRegistrationArray' => array_unique($firstRegistrationArray),
            'transmissionArray'      => array_unique($transmissionArray),
            'messageArray'           => $messageArray
        ]);
    }

    public function update()
    {
        $car = [];
        $method = $_SERVER['REQUEST_METHOD'];
        if ($method == 'GET'){
            $carId = $_GET['id'];
            $car = (new CarsCollection())->getCarById($carId);
        } else {
            $car['id']                 = $_POST['id'] ?? 'Nqma id';
            $car['make_id']            = $_POST['make'] ?? 'Nqma marka';
            $car['model_id']           = $_POST['model'] ?? 'Nqma model';
            $car['first_registration'] = $_POST['first_registration'] ?? 'Nqma registration';
            $car['transmission']       = $_POST['transmission'] ?? 'Nqma transmission';
            $car['updated_at']         = date('Y-m-d H:s:i');

            $carsCollection = new CarsCollection();
            $carsCollection->update(['id' => $car['id']], $car);
        }

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

        $this->renderView('cars/edit', [
            'car' => $car,
            'allMakes' => $allMakes,
            'allModels' => $allModels,
            'firstRegistrationArray' => array_unique($firstRegistrationArray),
            'transmissionArray' => array_unique($transmissionArray)
        ]);
    }

    public function delete()
    {
        var_dump('This is the delete method of the CarsController');
    }

    public function listing()
    {
        $limit          = 5;
        $page           = $_GET['page'] ?? 1;
        $offset         = ($page - 1) * $limit;
        $cars           = new \App\Model\Collections\CarsCollection();
        $carResults     = $cars->getCarsPagination($limit, $offset);
        $carsCount      = $cars->getCarsCount();
        $totalPages     = (int) ceil($carsCount/$limit);

        $this->renderView('cars/listing', ['totalPages' => $totalPages, 'carResults' => $carResults]);
    }



}