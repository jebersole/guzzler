<?php

declare(strict_types=1);
require_once(__DIR__.'/../autoload.php');

use PHPUnit\Framework\TestCase;
use Helpers\GuzzleHelper;

final class GuzzleTest extends TestCase {

	public function testTokenIsTokenReturnedFromValidCredentials(): void {
		$_GET['login'] = GuzzleHelper::TEST_LOGIN;
		$_GET['pass'] = GuzzleHelper::TEST_PASSWORD;
		$response = json_decode(GuzzleHelper::getToken());
		$this->assertEquals(
			GuzzleHelper::TEST_TOKEN,
			$response->token
		);
	}

	public function testTokenIsErrorReturnedFromEmptyCredentials(): void {
		$_GET['login'] = '';
		$response = json_decode(GuzzleHelper::getToken());
		$this->assertEquals(
			'Error',
			$response->status
		);
	}

	public function testTokenIsNotFoundReturnedFromInvalidCredentials(): void {
		$_GET['login'] = 'something';
		$_GET['pass'] = 'something else';
		$response = json_decode(GuzzleHelper::getToken());
		$this->assertEquals(
			'Not found',
			$response->status
		);
	}

	public function testGetDataIsOKReturnedFromValidCredentials(): void {
		$_GET['token'] = GuzzleHelper::TEST_TOKEN;
		$_GET['username'] = GuzzleHelper::TEST_USERNAME;
		$response = json_decode(GuzzleHelper::getUserData());
		$this->assertEquals(
			'OK',
			$response->status
		);
	}

	public function testGetDataUserIdFromValidCredentials(): void {
		$_GET['token'] = GuzzleHelper::TEST_TOKEN;
		$_GET['username'] = GuzzleHelper::TEST_USERNAME;
		$response = json_decode(GuzzleHelper::getUserData());
		$this->assertEquals(
			'23',
			$response->id
		);
	}

	public function testGetDataIsErrorReturnedFromEmptyCredentials(): void {
		$_GET['token'] = '';
		$response = json_decode(GuzzleHelper::getUserData());
		$this->assertEquals(
			'Error',
			$response->status
		);
	}

	public function testGetDataIsNotFoundReturnedFromInvalidCredentials(): void {
		$_GET['token'] = 'something';
		$_GET['username'] = 'something else';
		$response = json_decode(GuzzleHelper::getUserData());
		$this->assertEquals(
			'Not found',
			$response->status
		);
	}

	public function testPostDataIsOKReturnedFromValidCredentials(): void {
		$_POST['token'] = GuzzleHelper::TEST_TOKEN;
		$_POST['userid'] = GuzzleHelper::TEST_USERID;
		$response = json_decode(GuzzleHelper::postUserData());
		$this->assertEquals(
			'OK',
			$response->status
		);
	}

	public function testPostDataUserIdFromValidCredentials(): void {
		$_POST['token'] = GuzzleHelper::TEST_TOKEN;
		$_POST['userid'] = GuzzleHelper::TEST_USERID;
		$response = json_decode(GuzzleHelper::postUserData());
		$this->assertEquals(
			'23',
			$response->id
		);
	}

	public function testPostDataIsErrorReturnedFromEmptyCredentials(): void {
		$_POST['token'] = '';
		$response = json_decode(GuzzleHelper::postUserData());
		$this->assertEquals(
			'Error',
			$response->status
		);
	}

	public function testPostDataIsNotFoundReturnedFromInvalidCredentials(): void {
		$_POST['token'] = 'something';
		$_POST['userid'] = '420000000';
		$response = json_decode(GuzzleHelper::postUserData());
		$this->assertEquals(
			'Not found',
			$response->status
		);
	}

	public function testPostDataValidData(): void {
		$testData = [
			'name' => 'Albert',
			'active' => 0,
			'blocked' => 1,
			'permissions' => '2,Update;4,Delete everything'
		];

		$_POST['token'] = GuzzleHelper::TEST_TOKEN;
		$_POST['userid'] = GuzzleHelper::TEST_USERID;
		foreach ($testData as $key => $val) $_POST[$key] = $val;

		$response = json_decode(GuzzleHelper::postUserData());
		$this->assertEquals(
			'OK',
			$response->status
		);
		$this->assertEquals(
			$testData['name'],
			$response->name
		);

		foreach (['active', 'blocked'] as $key) {
			$this->assertEquals(
				boolval($testData[$key]),
				boolval($response->{$key})
			);
		}

		$testPermissionMap = [
			'2' => 'Update',
			'4' => 'Delete everything'
		];
		foreach ($response->permissions as $permission) {
			$id = $permission->id;
			$name = $permission->name;
			$this->assertTrue($testPermissionMap[$id] === $name);
		}
	}

	public function testPostDataIsErrorReturnedFromInvalidPermissions(): void {
		$testData = [
			'name' => 'Albert',
			'active' => 0,
			'blocked' => 1,
			'permissions' => 'this should not be here'
		];

		$_POST['token'] = GuzzleHelper::TEST_TOKEN;
		$_POST['userid'] = GuzzleHelper::TEST_USERID;
		foreach ($testData as $key => $val) $_POST[$key] = $val;

		$response = json_decode(GuzzleHelper::postUserData());
		$this->assertEquals(
			'Error',
			$response->status
		);
	}

}
