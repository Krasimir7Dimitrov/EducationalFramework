<?php
namespace App\Controllers;

use App\Model\Collections\CarsCollection;
use App\System\AbstractController;
use phpDocumentor\Reflection\Types\This;

class CarsController extends AbstractController
{

    /**
     * @var string
     */

    private $collectionInst;

    private $numberOfRowsInAPage = 5;

    public function __construct()
    {
        parent::__construct();
        $this->collectionInst = new CarsCollection();
    }

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

    private function getBaseUrl(): string
    {
        $protocol = $_SERVER['HTTPS'] ?? 'http';
        $host = $_SERVER['HTTP_HOST'];
        return $protocol . '://' . $host . '/';
    }

    private function numberOfPages(): float
    {
        $allCars = $this->collectionInst->getNumberOfAllCars();
        return ceil($allCars / $this->numberOfRowsInAPage);
    }

    private function getRowsFromDb(): array
    {
        $page = $_GET['page'] ?? 1;
        $offset = ($page - 1) * $this->numberOfRowsInAPage;
        $data = $this->collectionInst->getRowsForAPageFromCars($this->numberOfRowsInAPage, $offset);
        return $data;
    }

    public function listing()
    {
        $pages = $this->numberOfPages();
        $baseUrl = $this->getBaseUrl();
        $data = $this->getRowsFromDb();
        $this->renderView('cars/listing', ['pages' => $pages, 'baseUrl' => $baseUrl, 'data' => $data]);
    }
}