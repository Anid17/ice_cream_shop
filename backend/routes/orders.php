<?php
declare(strict_types=1);

Flight::route('GET /api/orders', function () {
    AuthMiddleware::requireAdmin();
  Flight::json(Flight::ordersService()->getAll());
});

Flight::route('GET /api/orders/@id', function ($id) {
    AuthMiddleware::requireAdmin();
  $o = Flight::ordersService()->getById((int)$id);
  if (!$o) { Flight::json(['error' => 'Order not found'], 404); return; }
  Flight::json($o);
});

Flight::route('POST /api/orders', function () {
    AuthMiddleware::requireAdmin();
  $payload = Flight::request()->data->getData();
  try {
    $o = Flight::ordersService()->create($payload);
    Flight::json($o, 201);
  } catch (InvalidArgumentException $e) {
    Flight::json(['error' => $e->getMessage()], 400);
  } catch (Throwable $e) {
    Flight::json(['error' => 'Server error'], 500);
  }
});

Flight::route('PUT /api/orders/@id', function ($id) {
    AuthMiddleware::requireAdmin();
  $payload = Flight::request()->data->getData();
  try {
    $o = Flight::ordersService()->update((int)$id, $payload);
    if (!$o) { Flight::json(['error' => 'Order not found'], 404); return; }
    Flight::json($o);
  } catch (InvalidArgumentException $e) {
    Flight::json(['error' => $e->getMessage()], 400);
  } catch (Throwable $e) {
    Flight::json(['error' => 'Server error'], 500);
  }
});

Flight::route('DELETE /api/orders/@id', function ($id) {
    AuthMiddleware::requireAdmin();
  $ok = Flight::ordersService()->delete((int)$id);
  if (!$ok) { Flight::json(['error' => 'Order not found'], 404); return; }
  Flight::json(['status' => 'deleted']);
});
