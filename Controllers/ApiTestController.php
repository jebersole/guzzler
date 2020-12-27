<?php

namespace Controllers;

use Helpers\GuzzleHelper;

class ApiTestController {

	private $testUser = [
		'status' => 'OK',
		'active' => '1',
		'blocked' => false,
		'created_at' => 1587457590,
		'id' => 23,
		'name' => 'Ivanov Ivan',
		'permissions' => [
			[
				'id' => 1,
				'permission' => 'comment'
			],
			[
				'id' => 2,
				'permission' => 'upload photo'
			],
			[
				'id' => 3,
				'permission' => 'add event'
			]
		]
	];

	public function getToken() {
		$login = $_GET['login'] ?? '';
		$pass = $_GET['pass'] ?? '';
		if (!$login || !$pass) {
			http_response_code(400);
			print json_encode(['status' => 'Error', 'errors' => 'Please provide an username and password.']);
			exit();
		}

		if (($login === GuzzleHelper::TEST_LOGIN) && ($pass === GuzzleHelper::TEST_PASSWORD)) {
			print json_encode(['status' => 'OK', 'token' => GuzzleHelper::TEST_TOKEN]);
		} else {
			http_response_code(401);
			print json_encode(['status' => 'Not found', 'errors' => 'Invalid username or password.']);
		}
	}

	public function getUserData() {
		$slashPos = strrpos($_SERVER['REQUEST_URI'], '/') + 1;
		$username = substr($_SERVER['REQUEST_URI'], $slashPos, strpos($_SERVER['REQUEST_URI'], '?') - $slashPos);
		$token = $_GET['token'] ?? '';

		if (!$token || !$username) {
			http_response_code(400);
			print json_encode(['status' => 'Error', 'errors' => 'Please provide an username and token.']);
			exit();
		}

		if (($username === GuzzleHelper::TEST_USERNAME) && ($token === GuzzleHelper::TEST_TOKEN)) {
			// query db, example result:
			print json_encode($this->testUser);
		} else {
			http_response_code(401);
			print json_encode(['status' => 'Not found', 'errors' => 'Invalid token or username.']);
		}
	}

	public function postUserData() {
		$matches = [];
		preg_match('/\d+/', $_SERVER['REQUEST_URI'], $matches);
		$userid = $matches[0] ?? 0;
		$token = $_GET['token'] ?? '';
		if (!$token || !$userid) {
			http_response_code(400);
			print json_encode(['status' => 'Error', 'errors' => 'Please provide an userid and token.']);
			exit();
		}

		if (($userid !== GuzzleHelper::TEST_USERID) && ($token !== GuzzleHelper::TEST_TOKEN)) {
			http_response_code(401);
			print json_encode(['status' => 'Not found', 'errors' => 'Invalid token or userid.']);
			exit();
		}

		$permissions = $_POST['permissions'] ?? '';
		if ($permissions) {
			$newPermissions = [];
			if (strpos($permissions, ';') !== false) {
				$parts = preg_split('/;/', $permissions);
				foreach ($parts as $part) {
					$this->parsePermission($part, $newPermissions);
				}
			} else if (strpos($permissions, ',') !== false) {
				$this->parsePermission($permissions, $newPermissions);
			} else {
				http_response_code(400);
				print json_encode(['status' => 'Error', 'errors' => 'Invalid format for permissions.']);
				exit();
			}
			if ($newPermissions) {
				$this->testUser['permissions'] = $newPermissions;
			}
		}

		if (!empty($_POST['name'])) $this->testUser['name'] = $_POST['name'];
		foreach (['blocked', 'active'] as $field) {
			if (isset($_POST[$field])) $this->testUser[$field] = boolval($_POST[$field]);
		}

		print json_encode($this->testUser);
	}

	private function parsePermission($part, &$newPermissions) {
		$innerParts = preg_split('/,/', $part);
		$id = isset($innerParts[0]) ? (int) trim($innerParts[0]) : '';
		$name = isset($innerParts[1]) ? trim($innerParts[1]) : '';
		if (!$id || !$name) {
			http_response_code(400);
			print json_encode(['status' => 'Error', 'errors' => 'Invalid format for permissions.']);
			exit();
		}
		$newPermissions[] = [
			'id' => $id,
			'name' => $name
		];
	}

}