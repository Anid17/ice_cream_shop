<?php
declare(strict_types=1);

Flight::route('GET /api/categories', function () {
  Flight::json(Flight::categoriesService()->getAll());
});

Flight::route('GET /api/categories/@id', function ($id) {
  $c = Flight::categoriesService()->getById((int)$id);
  if (!$c) { Flight::json(['error' => 'Category not found'], 404); return; }
  Flight::json($c);
});

Flight::route('POST /api/categories', function () {
    AuthMiddleware::requireAdmin();
  $payload = Flight::request()->data->getData();
  try {
    $c = Flight::categoriesService()->create($payload);
    Flight::json($c, 201);
  } catch (InvalidArgumentException $e) {
    Flight::json(['error' => $e->getMessage()], 400);
  } catch (Throwable $e) {
    Flight::json(['error' => 'Server error'], 500);
  }
});

Flight::route('PUT /api/categories/@id', function ($id) {
    AuthMiddleware::requireAdmin();
  $payload = Flight::request()->data->getData();
  try {
    $c = Flight::categoriesService()->update((int)$id, $payload);
    if (!$c) { Flight::json(['error' => 'Category not found'], 404); return; }
    Flight::json($c);
  } catch (InvalidArgumentException $e) {
    Flight::json(['error' => $e->getMessage()], 400);
  } catch (Throwable $e) {
    Flight::json(['error' => 'Server error'], 500);
  }
});

Flight::route('DELETE /api/categories/@id', function ($id) {
    AuthMiddleware::requireAdmin();
  $ok = Flight::categoriesService()->delete((int)$id);
  if (!$ok) { Flight::json(['error' => 'Category not found'], 404); return; }
  Flight::json(['status' => 'deleted']);
});
