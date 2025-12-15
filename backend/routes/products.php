<?php
declare(strict_types=1);

Flight::route('GET /api/products', function () {
  Flight::json(Flight::productsService()->getAll());
});

Flight::route('GET /api/products/@id', function ($id) {
  $p = Flight::productsService()->getById((int)$id);
  if (!$p) { Flight::json(['error' => 'Product not found'], 404); return; }
  Flight::json($p);
});

Flight::route('POST /api/products', function () {
    AuthMiddleware::requireAdmin(); //roles for admin
  $payload = Flight::request()->data->getData();
  try {
    $p = Flight::productsService()->create($payload);
    Flight::json($p, 201);
  } catch (InvalidArgumentException $e) {
    Flight::json(['error' => $e->getMessage()], 400);
  } catch (Throwable $e) {
    Flight::json(['error' => 'Server error'], 500);
  }
});

Flight::route('PUT /api/products/@id', function ($id) {
    AuthMiddleware::requireAdmin();
  $payload = Flight::request()->data->getData();
  try {
    $p = Flight::productsService()->update((int)$id, $payload);
    if (!$p) { Flight::json(['error' => 'Product not found'], 404); return; }
    Flight::json($p);
  } catch (InvalidArgumentException $e) {
    Flight::json(['error' => $e->getMessage()], 400);
  } catch (Throwable $e) {
    Flight::json(['error' => 'Server error'], 500);
  }
});

Flight::route('DELETE /api/products/@id', function ($id) {
    AuthMiddleware::requireAdmin();
  $ok = Flight::productsService()->delete((int)$id);
  if (!$ok) { Flight::json(['error' => 'Product not found'], 404); return; }
  Flight::json(['status' => 'deleted']);
});
