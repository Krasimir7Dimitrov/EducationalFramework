<?php
namespace App\Controllers;

use App\Model\Collections\CarsCollection;
use App\Model\Collections\MakeCollection;
use App\Model\Collections\ModelCollection;
use App\System\AbstractController;
use App\System\Registry;
use phpDocumentor\Reflection\Types\This;
use function PHPUnit\Framework\isEmpty;

class CarsController extends AbstractController
{

    /**
     * @var string
     */

    private $collectionInst;
    private $makeInst;
    private $modelInst;

    private $numberOfRowsInAPage = 10;
    private $offset;
    private $params;

    public function __construct()
    {
        parent::__construct();
        $this->collectionInst = new CarsCollection();
        $this->makeInst = new MakeCollection();
        $this->modelInst = new ModelCollection();
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
        return Registry::get('config')['baseUrl'] . '/';
    }

    private function numberOfPages($number): float
    {
        return ceil($number / $this->numberOfRowsInAPage);
    }


    private function convertOrderForDb($param)
    {
        switch ($param) {
            case 1:
                $option = 'mk.name';
                break;
            case 2:
                $option = 'mk.name DESC';
                break;
            case 3:
                $option = 'c.first_registration';
                break;
            case 4:
                $option = 'c.first_registration DESC';
                break;
            default:
                $option = 'c.created_at';
                break;
        }
        return $option;
    }


    private function buildQueryString($req): string
    {
        unset($req['page']);
        if (!empty($req)) {
            return http_build_query($req);
        }
        return '';
    }


    private function getMakes()
    {
        return $this->makeInst->getAllMakes();
    }


    public function listing()
    {
        $getParams = $_GET;
        $filters = [
            'make_id' => $getParams['make_id'] ?? null,
            'model_id' => $getParams['model_id'] ?? null,
            'transmission' => $getParams['transmission'] ?? null,
        ];

        $orderBy = $getParams['order'] ?? null;
        $page = $getParams['page'] ?? 1;
        $offset = ($page - 1) * $this->numberOfRowsInAPage;
        $order = $this->convertOrderForDb($orderBy);
        $data = $this->collectionInst->getRowsForAPageFromCars($this->numberOfRowsInAPage, $offset, $filters, $order);
        $query = $this->buildQueryString($getParams);
        $number = $this->collectionInst->getNumberOfCars($filters);
        $pages = $this->numberOfPages($number);
        $baseUrl = $this->getBaseUrl();
        $makes = $this->getMakes();
        $models = $this->getModels();

        $filters['order'] = $orderBy;
        $viewData = [
            'filters' => $filters,
            'order' => $order,
            'query' => $query,
            'pages' => $pages,
            'baseUrl' => $baseUrl,
            'data' => $data,
            'makes' => $makes,
            'models' => $models
        ];

        $this->renderView('cars/listing', $viewData);
    }

    private function getModels()
    {
        return $this->modelInst->getAllModels();
    }
}