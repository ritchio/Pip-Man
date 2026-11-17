<?php
require_once __DIR__ . '/../system/DBConfig.php';
require_once __DIR__ . '/../data/entities/User.php';
require_once __DIR__ . '/../data/models/UserModel.php';
class UserService {
    private $pdo;
    private $userModel;

    public function __construct() {
        $this->pdo = DBConfig::getConnection();
        $this->userModel = new UserModel($this->pdo);
    }

    public function registerUser(string $username, string $password): bool {
        $this->validateCredentials($username, $password);

        return $this->userModel->create([
            "username" => $username,
            "password" => password_hash($password, PASSWORD_DEFAULT)
        ]) > 0;
    }

    public function getList() {
        return $this->userModel->getList();
    }

    public function getById(int $id) {
        if ($id <= 0) {
            throw new InvalidArgumentException("Invalid user ID.");
        }

        return $this->userModel->getById($id);
    }

    public function create(array $data) {
        $username = $this->validateUsername($data["username"] ?? null);
        $password = $this->validatePassword($data["password"] ?? null);
        $this->ensureUsernameAvailable($username);

        return $this->userModel->create([
            "username" => $username,
            "password" => password_hash($password, PASSWORD_DEFAULT)
        ]);
    }

    public function update(int $id, array $data) {
        if ($id <= 0 || $this->userModel->getById($id) === null) {
            throw new InvalidArgumentException("User not found.");
        }

        $username = $this->validateUsername($data["username"] ?? null);
        $existingUser = $this->userModel->getByUsername($username);
        if ($existingUser !== null && (int)$existingUser["id"] !== $id) {
            throw new InvalidArgumentException("Username is already in use.");
        }

        $updateData = ["username" => $username];
        if (isset($data["password"]) && $data["password"] !== "") {
            $updateData["password"] = password_hash(
                $this->validatePassword($data["password"]),
                PASSWORD_DEFAULT
            );
        }

        return $this->userModel->update($id, $updateData);
    }

    public function delete(int $id) {
        if ($id <= 0) {
            throw new InvalidArgumentException("Invalid user ID.");
        }

        return $this->userModel->delete($id);
    }

    public function authenticateUser(string $username, string $password): int {
        $user = $this->getUserByUsername($username);
        if ($user) {
            $valid = password_verify($password, $user->password);
            if ($valid) {
                return $user->id;
            }
        }
        return 0;
    }

    public function getUserByUsername(string $username): ?User {
        $stmt = $this->pdo->prepare("SELECT id, username, password FROM user WHERE username = :username");
        $stmt->execute(['username' => $username]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            $user = new User();
            $user->id = (int)$row['id'];
            $user->username = $row['username'];
            $user->password = $row['password'];
            return $user;

        }

        return null;
    }

    private function validateCredentials(string $username, string $password): void {
        $this->validateUsername($username);
        $this->validatePassword($password);
    }

    private function validateUsername($username): string {
        if (!is_string($username) || trim($username) === "") {
            throw new InvalidArgumentException("Username is required.");
        }

        return trim($username);
    }

    private function validatePassword($password): string {
        if (!is_string($password) || $password === "") {
            throw new InvalidArgumentException("Password is required.");
        }

        return $password;
    }

    private function ensureUsernameAvailable(string $username): void {
        if ($this->userModel->getByUsername($username) !== null) {
            throw new InvalidArgumentException("Username is already in use.");
        }
    }

    public function getPermissionsForUser(string $user_id): array {
        $stmt = $this->pdo->prepare("SELECT m.name,m.url, mp.action,m.list_order FROM user_permission up 
        INNER JOIN module_permission mp ON mp.id = up.module_permission_id 
        INNER JOIN module m ON m.id = mp.module_id
        WHERE up.user_id = :userId
        UNION ALL 
        SELECT m.name, m.url,'Access' as action, m.list_order
        FROM module m
        WHERE m.restricted = 0 and active = 1
        ORDER BY list_order ASC");
        $stmt->execute(['userId' => $user_id]);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $permissions = [];
        
        foreach ($result as $permission) {

            $permissions[$permission['name']]['actions'][] = $permission['action'];
            if(!array_key_exists("name",$permissions[$permission['name']])){
                $permissions[$permission['name']]['url'] = $permission['url'];
            }
        }
        return $permissions;
    }
}
?>