<?php
require_once __DIR__ . '/../data/models/ProductModel.php';

class ProductsService {
    private $productModel;

    public function __construct() {
        $this->productModel = new ProductModel();
    }

    public function getList() {
        return $this->productModel->getList();
    }

    public function getById(int $id) {
        if ($id <= 0) {
            throw new InvalidArgumentException("Invalid product ID.");
        }

        return $this->productModel->getById($id);
    }

    public function create(array $data) {
        return $this->productModel->create($this->validate($data));
    }

    public function update(int $id, array $data) {
        if ($id <= 0 || $this->productModel->getById($id) === null) {
            throw new InvalidArgumentException("Product not found.");
        }

        return $this->productModel->update($id, $this->validate($data));
    }

    public function delete(int $id) {
        if ($id <= 0) {
            throw new InvalidArgumentException("Invalid product ID.");
        }

        return $this->productModel->delete($id);
    }

    private function validate(array $data): array {
        $name = $data["name"] ?? null;
        $active = $data["active"] ?? null;

        if (!is_string($name) || trim($name) === "") {
            throw new InvalidArgumentException("Product name is required.");
        }

        if ((!is_string($active) && !is_int($active)) ||
            !in_array((string)$active, ["0", "1"], true)) {
            throw new InvalidArgumentException("Invalid product status.");
        }

        return [
            "name" => trim($name),
            "active" => (int)$active
        ];
    }
}
