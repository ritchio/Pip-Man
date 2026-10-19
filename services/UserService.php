<?php
require_once __DIR__ . '/../system/DBConfig.php';
require_once __DIR__ . '/../data/entities/User.php';
class UserService {
    private $pdo;

    public function __construct() {
        $this->pdo = DBConfig::getConnection();
    }

    public function registerUser(string $username, string $password): bool {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->pdo->prepare("INSERT INTO user (username, password) VALUES (:username, :password)");
        return $stmt->execute(['username' => $username, 'password' => $hashedPassword]);
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
            if(!array_key_exists("url",$permissions[$permission['name']])){
                $permissions[$permission['name']]['url'] = $permission['url'];
            }
        }
        return $permissions;
    }
}
?>