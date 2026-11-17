<?php
require_once __DIR__ . '/../services/ProductsService.php';
require_once __DIR__ . '/../system/Controller.php';

class ProductsController extends Controller {
    private $productsService;

    public function __construct() {
        $this->productsService = new ProductsService();
    }

    public function index() {
        try {
            return $this->view(data:$this->productsService->getList());
        } catch (Exception $ex) {
            echo $ex;
        }
    }

    public function create($data = null) {
        try {
            if ($data != null) {
                $this->productsService->create($data);
                return $this->index();
            }

            return $this->view();
        } catch (Exception $ex) {
            echo $ex;
        }
    }

    public function edit($id = null, $data = null) {
        try {
            if ($id == null || !is_numeric($id) || (int)$id <= 0) {
                return $this->notFound();
            }

            $product = $this->productsService->getById((int)$id);
            if ($product == null) {
                return $this->notFound();
            }

            if ($data != null) {
                $this->productsService->update((int)$id, $data);
                return $this->index();
            }

            return $this->view(data:$product);
        } catch (Exception $ex) {
            echo $ex;
        }
    }

    public function delete($id = null) {
        try {
            if ($id != null && is_numeric($id) && (int)$id > 0) {
                $this->productsService->delete((int)$id);
            }

            return $this->index();
        } catch (Exception $ex) {
            echo $ex;
        }
    }
}
