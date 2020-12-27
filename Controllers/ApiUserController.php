<?php

namespace Controllers;

use Helpers\GuzzleHelper;

class ApiUserController {

	public function showToken() {
		print GuzzleHelper::getToken();
	}

	public function showUserData() {
		print GuzzleHelper::getUserData();
	}

	public function updateUserData() {
		print GuzzleHelper::postUserData();
	}

	public function goHome() {
		require $_SERVER['DOCUMENT_ROOT'] . '/../views/home.php';
	}
}