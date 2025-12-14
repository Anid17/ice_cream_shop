<?php
declare(strict_types=1);

Flight::route('GET /api/reviews', function () {
  Flight::json(Flight::reviewsService()->getAll());
});

Flight::route('GET /api/reviews/@id', function ($id) {
  $r = Flight::reviewsService()->getById((int)$id);
  if (!$r) { Flight::json(['error' => 'Review not found'], 404); return; }
  Flight::json($r);
});

Flight::route('POST /api/reviews', function () {
    AuthMiddleware::requireAdmin();
  $payload = Flight::request()->data->getData();
  try {
    $r = Flight::reviewsService()->create($payload);
    Flight::json($r, 201);
  } catch (InvalidArgumentException $e) {
    Flight::json(['error' => $e->getMessage()], 400);
  } catch (Throwable $e) {
    Flight::json(['error' => 'Server error'], 500);
  }
});

Flight::route('PUT /api/reviews/@id', function ($id) {
    AuthMiddleware::requireAdmin();
  $payload = Flight::request()->data->getData();
  try {
    $r = Flight::reviewsService()->update((int)$id, $payload);
    if (!$r) { Flight::json(['error' => 'Review not found'], 404); return; }
    Flight::json($r);
  } catch (InvalidArgumentException $e) {
    Flight::json(['error' => $e->getMessage()], 400);
  } catch (Throwable $e) {
    Flight::json(['error' => 'Server error'], 500);
  }
});

Flight::route('DELETE /api/reviews/@id', function ($id) {
    AuthMiddleware::requireAdmin();
  $ok = Flight::reviewsService()->delete((int)$id);
  if (!$ok) { Flight::json(['error' => 'Review not found'], 404); return; }
  Flight::json(['status' => 'deleted']);
});
