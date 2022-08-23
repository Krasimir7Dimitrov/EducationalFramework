<?php
namespace App\Controllers;

use App\Model\Collections\CarsCollection;
use App\Model\Collections\MakeCollection;
use App\Model\Collections\ModelCollection;
use App\System\AbstractController;
use App\System\Debugbar\Enums\SuperAdmin;
use App\System\Registry;
use phpDocumentor\Reflection\Types\This;
use function PHPUnit\Framework\isEmpty;
use function PHPUnit\Framework\stringContains;

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
            $this->setFlashMessage('You need to be logged user');
            $this->redirect('default', 'index');
        }

        $makes = $this->getMakes();
        $models = $this->getModels();
        $errors = [];

        $method = $_SERVER['REQUEST_METHOD'];
        if ($method == 'POST') {
            $create = $this->postRequest();
            $create['user_id'] = $_SESSION['user']['id'];
            if (empty($create['make_id'])) {
                $errors['makeErr'] = 'Make is required';
            }
            if (empty($create['model_id'])) {
                $errors['modelErr'] = 'Model is required';
            }
            if (empty($create['first_registration'])) {
                $errors['firstRegErr'] = 'First registration is required';
            }
            if (empty($create['image'])) {
                $errors['imageErr'] = 'Image is required';
            }

            if (empty($errors)) {
                $id = $this->collectionInst->createCar($create);

                $this->setFlashMessage("Record with Id {$id} was created successfully");
                $this->redirect('cars', 'listing');
            }
        }
        $this->renderView('cars/create', ['data' => $create, 'makes' => $makes, 'models' => $models, 'errors' => $errors]);
    }

    public function update()
    {
        if (!$this->isLoggedIn())
        {
            $this->setFlashMessage('You need to be logged user');
            $this->redirect('default', 'index');
        }
        $segment = $this->urlSegments[3];
        if((int)$segment == 0) {
            $this->setFlashMessage('Invalid data');
            $this->redirect('cars', 'listing');
        }
        $data = $this->collectionInst->getSingleCar($segment);
        if ($data['user_id'] !== $_SESSION['user']['id'] && !$this->isAdmin($_SESSION['user']['id'])) {
            $this->redirect('default', 'index');
        }

        $makes = $this->getMakes();
        $models = $this->getModels();

        $method = $_SERVER['REQUEST_METHOD'];
        if ($method == 'POST') {
            $update = $this->postRequest();
            $where = [
                'id' => $segment
            ];
            $date = $update['first_registration'];
            if ($date > date('Y-m-d') ) {
                $error['yearErr'] = 'The date is not correct';
                $update['image'] = $data['image'];
                $this->renderView('cars/update', ['error' => $error, 'data' => $update, 'makes' => $makes, 'models' => $models]);
            }
            if ($date <= date('Y-m-d')) {
                $this->collectionInst->updateCar($update, $where);

                $this->setFlashMessage("Record with Id {$segment} was updated successfully");
                $this->redirect('cars', 'listing');
            }
        }
        $this->renderView('cars/update', ['data' => $data, 'makes' => $makes, 'models' => $models]);
    }

    public function car()
    {
        $segment = $this->urlSegments[3];
        $data = $this->collectionInst->getSingleCar($segment);
        $makes = $this->getMakes();
        $models = $this->getModels();
        $baseUrl = $this->getBaseUrl();

        $this->renderView('cars/car', ['data' => $data, 'makes' => $makes, 'models' => $models, 'baseUrl' => $baseUrl]);
    }


    public function delete()
    {
        if (!$this->isLoggedIn()) {
            $this->setFlashMessage('You need to be logged user');

            $this->redirect('default', 'index');
        }

        $method = $_SERVER['REQUEST_METHOD'];
        if ($method == 'POST') {
            $id = (int)$this->urlSegments[3] ?? 0;
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
            case 5:
                $option = "c.user_id = {$_SESSION['user']['id']}";
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
        if (strpos($order, "user_id")) {
            $filters['user_id'] = $_SESSION['user']['id'];
            $order = "c.created_at";
        }
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