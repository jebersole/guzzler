<?php

namespace Helpers;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

class GuzzleHelper {
	const BASE_URL =  'http://172.28.0.1/';
	const TEST_LOGIN = 'test';
	const TEST_USERNAME = 'ivanov';
	const TEST_USERID = '23';
	const TEST_PASSWORD = '12345';
	const TEST_TOKEN = 'KqxZ8nOvRx';
	const AUTH_URL = self::BASE_URL . 'auth';
	const GET_URL = self::BASE_URL . 'get-user/';
	const POST_URL = self::BASE_URL . 'user/'; // . user_id . '/update';

	/**
	 * @return string JSON response
	 */
	public static function getToken() {
		$queryParams = [
			'query' => [
				'login' => $_GET['login'] ?? '',
				'pass' => $_GET['pass'] ?? ''
			]
		];
		return self::getData($queryParams);
	}

	/**
	 * @return string JSON response
	 */
	public static function getUserData() {
		$queryParams = [
			'query' => [
				'token' => $_GET['token'] ?? '',
				'username' => $_GET['username'] ?? ''
			]
		];
		return self::getData($queryParams);
	}

	/**
	 * @return string JSON response
	 */
	public static function getData($queryParams) {
		$client = new Client([
			'headers' => ['Accept' => 'application/json'],
			'verify' => false,	// выключать SSL
		]);


		$log = new Logger('guzzle');
		try {
			$log->pushHandler(new StreamHandler(__DIR__.'/../guzzle.log', Logger::DEBUG));
		} catch (\Exception $e) {
			return 'Cannot create log file for guzzle: ' . $e->getMessage();
		}

		if (isset($queryParams['query']['login'])) {
			$reqUrl = self::AUTH_URL;
		} else {
			$reqUrl = self::GET_URL . $queryParams['query']['username'] ?? '';
			unset($queryParams['query']['username']);
		}

		$respCode = 200;
		try {
			$log->info('Sending GET ', $queryParams);
			$response = $client->request('GET', $reqUrl, $queryParams);
		} catch (RequestException $e) {
			$response = $e->getResponse();
			$respCode = $response ? $response->getStatusCode(): '';
			$log->error('Guzzle get error (1a): ', [
				'e' => $e->getMessage(),
				'code' => $respCode
			]);
		} catch (\Exception $e) {
			$log->error('Guzzle get error (1b): '. $e->getMessage());
		}

		$body = $response ? $response->getBody() : '';
		$content = $body ? $body->getContents() : '';
		http_response_code($respCode);
		return $content;
	}

	/**
	 * @return string JSON response
	 */
	public static function postUserData() {
		$token =  $_POST['token'] ?? '';
		$userid = $_POST['userid'] ?? '';
		$formParams = [
			'name' => $_POST['name'] ?? '',
			'permissions' => $_POST['permissions'] ?? '',
			'blocked' => $_POST['blocked'] ?? '',
			'active' => $_POST['active'] ?? ''
		];

		$client = new Client([
			'headers' => ['Content-Type' => 'application/x-www-form-urlencoded', 'Accept' => 'application/json'],
			'verify' => false,
		]);

		$log = new Logger('guzzle');
		try {
			$log->pushHandler(new StreamHandler(__DIR__.'/../guzzle.log', Logger::DEBUG));
		} catch (\Exception $e) {
			return 'Cannot create log file for guzzle: ' . $e->getMessage();
		}

		$reqUrl = self::POST_URL . $userid . '/update';
		$respCode = 200;
		try {
			$log->info('Sending POST');
			$response = $client->request('POST', $reqUrl, ['form_params' => $formParams, 'query' => ['token' => $token]]);
		} catch (RequestException $e) {
			$response = $e->getResponse();
			$respCode = $response ? $response->getStatusCode(): '';
			$log->error('Guzzle post error (1a): ', [
				'e' => $e->getMessage(),
				'code' => $respCode
			]);
		} catch (\Exception $e) {
			$log->error('Guzzle post error (1b): '.$e->getMessage());
		}

		$body = $response ? $response->getBody() : '';
		$content = $body ? $body->getContents() : '';
		http_response_code($respCode);
		return $content;
	}

}
