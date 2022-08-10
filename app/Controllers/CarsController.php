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
    public $urlSegments;

    public function __construct()
    {
        parent::__construct();
        $this->collectionInst = new CarsCollection();
        $this->makeInst = new MakeCollection();
        $this->modelInst = new ModelCollection();
        $this->setNumberOfRowsInAPage();
        $this->getUrlSegments();
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
        $makes = $this->getMakes();
        $models = $this->getModels();
        $this->renderView('cars/create', ['makes' => $makes, 'models' => $models]);

        $method = $_SERVER['REQUEST_METHOD'];
        if ($method == 'POST') {
            $create = $this->postRequest();
            $dateTime = date("Y-m-d H:i:s");
            $create['created_at'] = $dateTime;
            $id = $this->collectionInst->createCar($create);

            $this->setFlashMessage("Record with Id {$id} was created successfully");
            $this->redirect('cars', 'listing');

        }

    }

    public function update()
    {
        if (!$this->isLoggedIn())
        {
            $this->setFlashMessage('You need to be logged user');

            $this->redirect('default', 'index');
        }
        $segment = $this->urlSegments[3];
        $data = $this->collectionInst->getSingleCar($segment);
        $makes = $this->getMakes();
        $models = $this->getModels();

        $method = $_SERVER['REQUEST_METHOD'];
        if ($method == 'POST') {
            $update = $this->postRequest();
            $where = [
                'id' => $segment
            ];
            $this->collectionInst->updateCar($update, $where);

            $this->setFlashMessage("Record with Id {$segment} was created successfully");
            $this->redirect('cars', 'update', ['id' => $segment]);
        }
        $this->renderView('cars/update', ['data' => $data, 'makes' => $makes, 'models' => $models]);
    }

    public function car()
    {

    }

    public function delete()
    {
        $id = (int) $this->urlSegments[3] ?? 0;
        $where = [
            'id' => $id
        ];

        $result = $this->collectionInst->deleteCar($where);

        $message = "Record with Id {$id} was not deleted successfully.
         There are some problem with the delete operation.";
        if (!empty($result)) {
            $message = "Record with Id {$id} was deleted successfully";
        }

        $this->setFlashMessage($message);
        $this->redirect('cars', 'listing');
    }

    private function getUrlSegments(): void
    {
        $uriPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $uriSegments = explode('/', $uriPath);
        $this->urlSegments = $uriSegments;
    }

    private function getRequest(): array
    {
        return $_GET;
    }

    private function postRequest(): array
    {
        return $_POST;
    }

    private function setNumberOfRowsInAPage()
    {
        $numCars = $this->getRequest();
        if (!empty($numCars['carsNum'])) {
            $this->numberOfRowsInAPage = $numCars['carsNum'];
        }
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
        $getParams = $this->getRequest();
        $filters = [
            'make_id' => $getParams['make_id'] ?? null,
            'model_id' => $getParams['model_id'] ?? null,
            'transmission' => $getParams['transmission'] ?? null,
        ];

        $orderBy = $getParams['order'] ?? null;
        $numCarsInAPage = $getParams['carsNum'] ?? null;
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
        if (!empty($numCarsInAPage)) {
            $filters['carsNum'] = $numCarsInAPage;
        }
        $filters['carsNum'] = $this->numberOfRowsInAPage;
        $filters['order'] = $orderBy;

        $viewData = [
            'filters' => $filters,
            'order' => $order,
            'query' => $query,
            'pages' => $pages,
            'baseUrl' => $baseUrl,
            'data' => $data,
            'makes' => $makes,
            'models' => $models,
            'page' => $page
        ];

        $this->renderView('cars/listing', $viewData);
    }

    private function getModels()
    {
        return $this->modelInst->getAllModels();
    }
}