<?php
// В public/index.php
require_once '../app/controllers/CoffeeController.php';

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = trim($uri, '/'); // Удаляем слеши
$basePath = 'CoffeeWebSite/';
if (strpos($uri, $basePath) === 0) { // Проверяем, начинается ли строка с basePath
    $uri = substr($uri, strlen($basePath)); // Обрезаем basePath из начала
}
var_dump($uri);
$controller = new CoffeeController();

if ($uri === 'coffees') {
    $controller->index();
} elseif ($uri === 'coffees/create') {
    $controller->create();
} elseif (preg_match('/^coffees\/edit\/(\d+)$/', $uri, $matches)) {
    $controller->edit($matches[1]);
} elseif (preg_match('/^coffees\/delete\/(\d+)$/', $uri, $matches) && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller->delete($matches[1]);
} else {
    // 404 Not Found
    echo "404 Not Found";
}