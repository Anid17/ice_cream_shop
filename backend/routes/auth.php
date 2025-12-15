<?php
declare(strict_types=1);

Flight::route('POST /api/auth/register', function () {
  $data = Flight::request()->data->getData();

  try {
    // force default role "user" unless you want to allow admin creation manually in DB
    $data['role_id'] = 2;

    $user = Flight::usersService()->create($data);
    unset($user['password_hash']);

    Flight::json($user, 201);
  } catch (InvalidArgumentException $e) {
    Flight::json(['error' => $e->getMessage()], 400);
  } catch (Throwable $e) {
    Flight::json(['error' => 'Server error'], 500);
  }
});

Flight::route('POST /api/auth/login', function () {
  $data = Flight::request()->data->getData();
  $email = (string)($data['email'] ?? '');
  $password = (string)($data['password'] ?? '');

  if ($email === '' || $password === '') {
    Flight::json(['error' => 'Email and password required'], 400);
    return;
  }

  try {
    /** @var UsersDao $dao */
    $dao = new UsersDao(Flight::get('pdo'));
    $user = $dao->getByEmail($email);

    if (!$user || !password_verify($password, $user['password_hash'])) {
      Flight::json(['error' => 'Invalid credentials'], 401);
      return;
    }

    $payload = [
      'sub' => (int)$user['id'],
      'role_id' => (int)($user['role_id'] ?? 2),
      'email' => $user['email'],
    ];

    $token = Jwt::encode($payload, Flight::get('jwt_secret'), 3600);

    Flight::json([
      'token' => $token,
      'user' => [
        'id' => (int)$user['id'],
        'email' => $user['email'],
        'username' => $user['username'],
        'role_id' => (int)($user['role_id'] ?? 2),
      ]
    ]);
  } catch (Throwable $e) {
    Flight::json(['error' => 'Server error'], 500);
  }
});
