<?php
require_once __DIR__ . '/../services/UserService.php';
require_once __DIR__ . '/../system/Controller.php';

class UsersController extends Controller {
    private $userService;

    public function __construct() {
        $this->userService = new UserService();
    }

    public function index() {
        try {
            return $this->view(data:$this->userService->getList());
        } catch (Exception $ex) {
            echo $ex;
        }
    }

    public function create($data = null) {
        try {
            if ($data != null) {
                $this->userService->create($data);
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

            $user = $this->userService->getById((int)$id);
            if ($user == null) {
                return $this->notFound();
            }

            if ($data != null) {
                $this->userService->update((int)$id, $data);
                return $this->index();
            }

            return $this->view(data:$user);
        } catch (Exception $ex) {
            echo $ex;
        }
    }

    public function delete($id = null) {
        try {
            if ($id != null && is_numeric($id) && (int)$id > 0) {
                $this->userService->delete((int)$id);
            }

            return $this->index();
        } catch (Exception $ex) {
            echo $ex;
        }
    }
}
