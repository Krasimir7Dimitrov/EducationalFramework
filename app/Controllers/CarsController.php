<?php
namespace App\Controllers;

use App\Model\Collections\CarsCollection;
use App\System\AbstractController;

class CarsController extends AbstractController
{

    public function __construct()
    {
        parent::__construct();

        echo "Heere we are in cars controller constructor <hr/>";
    }

    public function index()
    {
        $carsCollection = new CarsCollection();

       $this->renderView('cars/index', []);
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

    public function isLoggedIn()
    {
        return !empty($_SESSION['loggedIn']);
    }


    public function __destruct()
    {
        parent::__destruct();

        echo "Heere we are in cars Controller destructor <hr/>";
    }

}