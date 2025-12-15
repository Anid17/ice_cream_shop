<?php
declare(strict_types=1);

Flight::route('GET /api/users', function () {
    AuthMiddleware::requireAdmin();
  Flight::json(Flight::usersService()->getAll());
});

Flight::route('GET /api/users/@id', function ($id) {
    AuthMiddleware::requireAdmin();
  $user = Flight::usersService()->getById((int)$id);
  if (!$user) { Flight::json(['error' => 'User not found'], 404); return; }
  Flight::json($user);
});

Flight::route('POST /api/users', function () {
    AuthMiddleware::requireAdmin();
  $payload = Flight::request()->data->getData();
  try {
    $user = Flight::usersService()->create($payload);
    Flight::json($user, 201);
  } catch (InvalidArgumentException $e) {
    Flight::json(['error' => $e->getMessage()], 400);
  } catch (Throwable $e) {
    Flight::json(['error' => 'Server error'], 500);
  }
});

Flight::route('PUT /api/users/@id', function ($id) {
    AuthMiddleware::requireAdmin();
  $payload = Flight::request()->data->getData();
  try {
    $user = Flight::usersService()->update((int)$id, $payload);
    if (!$user) { Flight::json(['error' => 'User not found'], 404); return; }
    Flight::json($user);
  } catch (InvalidArgumentException $e) {
    Flight::json(['error' => $e->getMessage()], 400);
  } catch (Throwable $e) {
    Flight::json(['error' => 'Server error'], 500);
  }
});

Flight::route('DELETE /api/users/@id', function ($id) {
    AuthMiddleware::requireAdmin();
  $ok = Flight::usersService()->delete((int)$id);
  if (!$ok) { Flight::json(['error' => 'User not found'], 404); return; }
  Flight::json(['status' => 'deleted']);
});
