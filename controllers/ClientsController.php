<?php
require_once __DIR__ . '/../services/ClientsService.php';
require_once __DIR__ . '/../system/Controller.php';

class ClientsController extends Controller {
    private $clientsService;

    public function __construct() {
        $this->clientsService = new ClientsService();
    }

    public function index() {
        try {
            return $this->view(data:$this->clientsService->getList());
        } catch (Exception $ex) {
            echo $ex;
        }
    }

    public function create($data = null) {
        try {
            if ($data != null) {
                $this->clientsService->create($data);
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

            $client = $this->clientsService->getById((int)$id);
            if ($client == null) {
                return $this->notFound();
            }

            if ($data != null) {
                $this->clientsService->update((int)$id, $data);
                return $this->index();
            }

            return $this->view(data:$client);
        } catch (Exception $ex) {
            echo $ex;
        }
    }

    public function delete($id = null) {
        try {
            if ($id != null && is_numeric($id) && (int)$id > 0) {
                $this->clientsService->delete((int)$id);
            }

            return $this->index();
        } catch (Exception $ex) {
            echo $ex;
        }
    }
}
