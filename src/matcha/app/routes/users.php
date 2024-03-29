<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use Slim\Http\UploadedFile;

$app = new \Slim\App([
	'settings' => [
		'displayErrorDetails' => true
	]
]);

$container = $app->getContainer();
$container['upload_directory'] = dirname(dirname(__DIR__)) . '/uploads';

$app->post('/api/user/login', function(Request $req, Response $res) {
	$userModel = new User();
	$loggedIn = $userModel->login($req->getParam('username'), $req->getParam('password'));
	if ($loggedIn) {
		$loggedIn->token = bin2hex(random_bytes(8));
		$loggedIn->tokenExpiration = date('Y-m-d H:i:s', strtotime('+2 hour'));
		$userModel->setToken([
			'token' => $loggedIn->token,
			'tokenExpiration' => $loggedIn->tokenExpiration,
			'id' => $loggedIn->id
		]);
		$loggedIn->images = $userModel->getUserImages($loggedIn->id);
	}
	return $res->withJson($loggedIn);
});

$app->post('/api/user/isloggedin', function(Request $req, Response $res) {
	$userModel = new User();
	$loggedIn = $userModel->checkToken($req->getParam('token'));
	if ($loggedIn) {
		$loggedIn->token = bin2hex(random_bytes(8));
		$loggedIn->tokenExpiration = date('Y-m-d H:i:s', strtotime('+2 hour'));
		$userModel->setToken([
			'token' => $loggedIn->token,
			'tokenExpiration' => $loggedIn->tokenExpiration,
			'id' => $loggedIn->id
		]);
		$loggedIn->images = $userModel->getUserImages($loggedIn->id);
	}
	return $res->withJson($loggedIn);
});

$app->post('/api/user/position/{id}', function(Request $req, Response $res) {
	$userModel = new User();
	$userModel->updateUserPosition([
		'lat' => $req->getParam('lat'),
		'lng' => $req->getParam('lng'),
		'id' => $req->getAttribute('id')
	]);
	return $res->withJson(['ok' => true]);
});

$app->post('/api/user/logout', function(Request $req, Response $res) {
	return $res->withJson(['ok' => true]);
});

$app->get('/api/users', function(Request $req, Response $res) {
	$userModel = new User();
	return $res->withJson($userModel->getAllUsers());
});

$app->post('/api/user/show/{id}', function(Request $req, Response $res) {
	$userModel = new User();
	$user = $userModel->getUser($req->getAttribute('id'));
	$user[0]->images = $userModel->getUserImages($user[0]->id);
	$userModel->history($req->getParam('visitor'), $user[0]->id);
	return $res->withJson($user);
});

$app->post('/api/user/install', function(Request $req, Response $res) {
	$userModel = new User();
	$data = [
		'first_name' => $req->getParam('first_name'),
		'last_name' => $req->getParam('last_name'),
		'username' => $req->getParam('username'),
		'email' => $req->getParam('email'),
		'password' => $req->getParam('password'),
		'vkey' => bin2hex(random_bytes(10))
	];
	$pass = $data['password'];
	$data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
	if ($userModel->addUser($data) && $userModel->verifyUser($data['vkey']))
	{
		$user = $userModel->login($data['username'], $pass);
		$data = [
			'id' => $user->id,
			'first_name' => $req->getParam('first_name'),
			'last_name' => $req->getParam('last_name'),
			'username' => $req->getParam('username'),
			'email' => $req->getParam('email'),
			'gender' => $req->getParam('gender'),
			'looking' => $req->getParam('looking'),
			'birthdate' => $req->getParam('birthdate'),
			'biography' => $req->getParam('biography'),
			'tags' => $req->getParam('tags'),
			'address' => $req->getParam('address'),
			'city' => $req->getParam('city'),
			'country' => $req->getParam('country'),
			'rating' => $req->getParam('rating'),
			'postal_code' => $req->getParam('postal_code'),
			'phone' => $req->getParam('phone')
		];
		if ($userModel->updateUser($data)) {
			$data = [
				'user_id' => $user->id,
				'name' => $req->getParam('image'),
				'profile' => 1
			];
			if ($userModel->addImage($data)) {
				$userModel->updateUserPosition([
					'lat' => $req->getParam('lat'),
					'lng' => $req->getParam('lng'),
					'id' => $user->id
				]);
				return $res->withJson(1);
			}
		}
	}
	return $res->withJson(0);
});

$app->post('/api/user/add', function(Request $req, Response $res) {
	$userModel = new User();
	$validator = new Validator();
	$data = [
		'first_name' => $req->getParam('first_name'),
		'last_name' => $req->getParam('last_name'),
		'username' => $req->getParam('username'),
		'email' => $req->getParam('email'),
		'password' => $req->getParam('password'),
		'vkey' => bin2hex(random_bytes(10))
	];
	$err = [
		'username' => $validator->validateName($data['username'], 'User'),
		'password' => $validator->validatePassword($data['password']),
		'email' => $validator->validateEmail($data['email'])
	];
	$state = [
		'response' => $data,
		'err' => '',
		'ok' => true
	];
	if (empty($err['username']) && empty($err['password']) && empty($err['email'])) {
		$data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
		if ($userModel->addUser($data)) {
			mail($data['email'], 'Mail verification','http://localhost/matcha/public/api/user/verify/' . $data['vkey']);
		} else {
			$state['err'] = ['server' => 'Cant add user ..'];
			$res['ok'] = false;
		}
	} else {
		$state['err'] = $err;
		$state['ok'] = false;
	}
	return $res->withJson($state);
});

$app->get('/api/user/verify/{vkey}', function(Request $req, Response $res) {
	$userModel = new User();
	$vkey = $req->getAttribute('vkey');
	$row = $userModel->isVerified($vkey);
	$state = ['ok' => true];
	if ($row) {
		if (!$userModel->verifyUser($vkey)) {
			$state['ok'] = false;
			if ($row->verified == 1)
				$state['err'] = 'Already verified';
			else
				$state['err'] = 'Cant verify';
		}
	} else {
		$state['ok'] = false;
		$state['err'] = 'User not found';
	}
	return $res->withJson($state);
});

$app->post('/api/user/update/{id}', function(Request $req, Response $res) {
	$userModel = new User();
	$data = [
		'id' => $req->getAttribute('id'),
		'first_name' => $req->getParam('first_name'),
		'last_name' => $req->getParam('last_name'),
		'username' => $req->getParam('username'),
		'email' => $req->getParam('email'),
		'gender' => $req->getParam('gender'),
		'looking' => $req->getParam('looking'),
		'birthdate' => $req->getParam('birthdate'),
		'biography' => $req->getParam('biography'),
		'tags' => $req->getParam('tags'),
		'address' => $req->getParam('address'),
		'city' => $req->getParam('city'),
		'country' => $req->getParam('country'),
		'rating' => $req->getParam('rating'),
		'postal_code' => $req->getParam('postal_code'),
		'phone' => $req->getParam('phone')
	];
	$state = [
		'response' => $data,
		'err' => '',
		'ok' => true
	];
	if ($userModel->isUser($data['id'])) {
		if (!$userModel->updateUser($data)) {
			$state['ok'] = false;
			$state['err'] = 'Cant update';
		}
	} else {
		$state['ok'] = false;
		$state['err'] = 'User not found';
	}
	return $res->withJson($state);
});

$app->post('/api/user/image/{id}', function(Request $req, Response $res) {
	$state = [
		'ok' => false,
		'name' => ''
	];
	$userModel = new User();
	$id = $req->getAttribute('id');
	$isProfile = $req->getParam('profile');
	$dest = $userModel->saveImage64($_POST['image'], $this->get('upload_directory'), $id);
	if ($dest) {
		$data = [
			'user_id' => $id,
			'name' => $dest,
			'profile' => 0
		];
		if ($isProfile && $userModel->unsetProfile($id))
			$data['profile'] = 1;
		if ($userModel->addImage($data)) {
			$state['ok'] = true;
			$state['name'] = $dest;
		}
	}
	return $res->withJson($state);
});

$app->post('/api/user/delete/{id}', function(Request $req, Response $res) {
	$userModel = new User();
	$id = $req->getAttribute('id');
	$state = [ 'ok' => true ];
	if ($userModel->isUser($id)) {
		if (!$userModel->deleteUser($id)) {
			$state['ok'] = false;
			$state['err'] = 'Cant delete';
		}
	} else {
		$state['ok'] = false;
		$state['err'] = 'User not found';
	}
	return $res->withJson($state);
});

$app->post('/api/user/getmatches', function(Request $req, Response $res) {
	$userModel = new User();
	$id = $req->getParam('id');
	return $res->withJson($userModel->getMatches($id));
});

$app->post('/api/user/getblocked', function(Request $req, Response $res) {
	$userModel = new User();
	$id = $req->getParam('id');
	return $res->withJson($userModel->getBlocked($id));
});

$app->post('/api/user/gethistory', function(Request $req, Response $res) {
	$userModel = new User();
	$id = $req->getParam('id');
	return $res->withJson($userModel->getHistory($id));
});

$app->post('/api/user/match/{id}', function(Request $req, Response $res) {
	$userModel = new User();
	$matched = $req->getAttribute('id');
	$matcher = $req->getParam('matcher');
	$state = [ 'ok' => true ];
	if (!$req->getParam('liked')) {
		if ($userModel->match($matcher, $matched)) {
			$state['match'] = true;
		} else {
			$state['ok'] = false;
			$state['err'] = 'Cant match';
		}
	} else {
		if ($userModel->unmatch($matcher, $matched)) {
			$state['match'] = false;
		} else {
			$state['ok'] = false;
			$state['err'] = 'Cant unmatch';
		}
	}
	return $res->withJson($state);
});

$app->post('/api/user/block/{id}', function(Request $req, Response $res) {
	$userModel = new User();
	$blocked = $req->getAttribute('id');
	$blocker = $req->getParam('blocker');
	$state = [ 'ok' => true ];
	if (!$userModel->block($blocker, $blocked)) {
		$state['ok'] = false;
		$state['err'] = 'Cant match';
	}
	return $res->withJson($state);
});
