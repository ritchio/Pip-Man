<?php
session_start();
if(!isset($_SESSION['authenticated'])){
    echo '<script>window.location.href = "login.php";</script>';
    exit;
}
//check if came with the right header
if (($_SERVER['HTTP_X_REQUESTED_WITH'] ?? '') !== 'HTMLFragment') {
    header('Location: index.php');
    exit;
}

$requested = $_GET['q'] ?? '';

$urlParts = parse_url($requested);

$pathParts = explode('/', $urlParts['path']);

//we just accept 4 levels of path: component/action/id/extra
$module = ucfirst($pathParts[0] ?? 'home'); // Default to 'home' module if not specified
$action = $pathParts[1] ?? 'index';
$id = $pathParts[2] ?? null;
$extra = $pathParts[3] ?? null;

//other parameters should be passed in body as JSON, not in the URL, to avoid injection and parsing issues
$postData = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $contentType = $_SERVER["CONTENT_TYPE"] ?? '';

    if (str_contains($contentType, 'application/json')) {
        $postData = json_decode(file_get_contents('php://input'), true);
    } else {
        $postData = $_POST;
    }

}

//in $_SESSION must have a list of allowed pages for the user, based on their role/permissions
$permissions = $_SESSION['permissions'] ?? [];
if (!array_key_exists($module, $permissions)) {
    print_r($permissions);
    echo '<div class="error">Access Denied: You do not have permission to access this page.</div>';
    exit;
}

//map to its path as class and method, for example:
// module "projects" with action "edit" would map to ProjectsController->edit($id, $extra)
$controllerClass = $module . 'Controller';

try {
    if(!file_exists(str_replace('/public_html', '', __DIR__) . '/controllers/' . $controllerClass . '.php')) {
        throw new Exception("Controller file for $controllerClass not found.");
    }
    
    include_once str_replace('/public_html', '', __DIR__) . '/controllers/' . $controllerClass . '.php';

    if (!class_exists($controllerClass)) {
        throw new Exception("Controller $controllerClass not found.");
    }
    $controller = new $controllerClass();
    if (!method_exists($controller, $action)) {
        throw new Exception("Method $action not found in controller $controllerClass.");
    }
    // Call the method with id and extra as parameters
    if($postData == null) {
        if($id === null && $extra === null) {
            $controller->$action();
            exit;
        } elseif ($extra === null) {
            $controller->$action($id);
            exit;
        }else{
            $controller->$action($id, $extra);
            exit;
        }
    } else {
        // If postData is present, pass it as an additional parameter
        if($id === null && $extra === null) {
            $controller->$action($postData);
            exit;
        } elseif ($extra === null) {
            $controller->$action($id, $postData);
            exit;
        }else{
            $controller->$action($id, $extra, $postData);
            exit;
        }
    }
} catch (Exception $e) {
    echo '<div class="error">Error: ' . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8') . '</div>';
}