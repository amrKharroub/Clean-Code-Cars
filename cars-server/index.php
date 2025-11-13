<?php 
require_once("./services/ResponseService.php");
require_once("routes/api.php");

$base_dir = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');
$request = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

if (strpos($request, $base_dir) === 0) {
    $request = substr($request, strlen($base_dir));
}

if ($request == '') {
    $request = '/';
}
$found = false;
$key = "";
foreach(array_keys($apis) as $url){
	$pattern= "|^" . str_replace("/", "\/", $url) . "$|";
    if (preg_match($pattern, $request)){
        $found = true;
        $key = $url;
        break;
    }
}

if ($found) {
    $controller_name = $apis[$key]['controller']; 
    $method = $apis[$key]['method'];
    require_once "controllers/{$controller_name}.php";
    
    $controller = new $controller_name();
    if (method_exists($controller, $method)) {
        $controller->$method();
    } else {
        echo ResponseService::response(500, "Error: Method {$method} not found in {$controller_name}");
    }
} else {
    echo ResponseService::response(404, "Route Not Found");
}