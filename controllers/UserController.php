<?php
class UserController {
    private $userService;

    public function __construct(UserService $userService) {
        $this->userService = $userService;
    }

    public function createUser(array $data) {
        return $this->userService->create($data);
    }

    public function getUser(int $id) {
        return $this->userService->getById($id);
    }

    public function getAllUsers() {
        return $this->userService->getAll();
    }

    public function updateUser(int $id, array $data) {
        return $this->userService->update($id, $data);
    }

    public function deleteUser(int $id) {
        return $this->userService->delete($id);
    }
}