<?php

use Controllers\ApiUserController;
use Controllers\ApiTestController;

$reqUrl = $_SERVER['REQUEST_URI'];
$routeMap = [
	'/^\/$/' => ['controller' => ApiUserController::class, 'method' => 'goHome'],
	'/^\/user\/token/' => ['controller' => ApiUserController::class, 'method' => 'showToken'],
	'/^\/auth/' => ['controller' => ApiTestController::class, 'method' => 'getToken'],
	'/^\/user\/data/' => ['controller' => ApiUserController::class, 'method' => $_SERVER['REQUEST_METHOD'] === 'POST' ?
		'updateUserData' : 'showUserData'],
	'/^\/get-user/' => ['controller' => ApiTestController::class, 'method' => 'getUserData'],
	'/^\/user\/\d+\/update/' => ['controller' => ApiTestController::class, 'method' => 'postUserData'],
];

foreach ($routeMap as $url => $route) {
	if (preg_match($url, $reqUrl)) {
		$dispatch = new $route['controller'];
		$dispatch->{$route['method']}();
		exit();
	}
}

require $_SERVER['DOCUMENT_ROOT'] . '/../views/404.php';