<?php
declare(strict_types=1);

require_once __DIR__ . '/flight/Flight.php';

$pdo = require __DIR__ . '/config.php';

require_once __DIR__ . '/dao/BaseDAO.php';
require_once __DIR__ . '/dao/UsersDAO.php';
require_once __DIR__ . '/dao/ProductsDAO.php';
require_once __DIR__ . '/dao/CategoriesDAO.php';
require_once __DIR__ . '/dao/OrdersDAO.php';
require_once __DIR__ . '/dao/ReviewsDAO.php';

require_once __DIR__ . '/services/UsersService.php';
require_once __DIR__ . '/services/ProductsService.php';
require_once __DIR__ . '/services/CategoriesService.php';
require_once __DIR__ . '/services/OrdersService.php';
require_once __DIR__ . '/services/ReviewsService.php';

Flight::set('pdo', $pdo);

Flight::map('usersService', function () {
    return new UsersService(Flight::get('pdo'));
});
Flight::map('productsService', function () {
    return new ProductsService(Flight::get('pdo'));
});
Flight::map('categoriesService', function () {
    return new CategoriesService(Flight::get('pdo'));
});
Flight::map('ordersService', function () {
    return new OrdersService(Flight::get('pdo'));
});
Flight::map('reviewsService', function () {
    return new ReviewsService(Flight::get('pdo'));
});

// USERS
Flight::route('GET /api/users', function () {
    Flight::json(Flight::usersService()->getAll());
});

Flight::route('GET /api/users/@id', function ($id) {
    $user = Flight::usersService()->getById((int)$id);
    if (!$user) {
        Flight::json(['error' => 'User not found'], 404);
        return;
    }
    Flight::json($user);
});

Flight::route('POST /api/users', function () {
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
    $payload = Flight::request()->data->getData();
    try {
        $user = Flight::usersService()->update((int)$id, $payload);
        if (!$user) {
            Flight::json(['error' => 'User not found'], 404);
            return;
        }
        Flight::json($user);
    } catch (InvalidArgumentException $e) {
        Flight::json(['error' => $e->getMessage()], 400);
    } catch (Throwable $e) {
        Flight::json(['error' => 'Server error'], 500);
    }
});

Flight::route('DELETE /api/users/@id', function ($id) {
    $ok = Flight::usersService()->delete((int)$id);
    if (!$ok) {
        Flight::json(['error' => 'User not found'], 404);
        return;
    }
    Flight::json(['status' => 'deleted']);
});

// PRODUCTS
Flight::route('GET /api/products', function () {
    Flight::json(Flight::productsService()->getAll());
});

Flight::route('GET /api/products/@id', function ($id) {
    $p = Flight::productsService()->getById((int)$id);
    if (!$p) {
        Flight::json(['error' => 'Product not found'], 404);
        return;
    }
    Flight::json($p);
});

Flight::route('POST /api/products', function () {
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
    $payload = Flight::request()->data->getData();
    try {
        $p = Flight::productsService()->update((int)$id, $payload);
        if (!$p) {
            Flight::json(['error' => 'Product not found'], 404);
            return;
        }
        Flight::json($p);
    } catch (InvalidArgumentException $e) {
        Flight::json(['error' => $e->getMessage()], 400);
    } catch (Throwable $e) {
        Flight::json(['error' => 'Server error'], 500);
    }
});

Flight::route('DELETE /api/products/@id', function ($id) {
    $ok = Flight::productsService()->delete((int)$id);
    if (!$ok) {
        Flight::json(['error' => 'Product not found'], 404);
        return;
    }
    Flight::json(['status' => 'deleted']);
});

// CATEGORIES
Flight::route('GET /api/categories', function () {
    Flight::json(Flight::categoriesService()->getAll());
});

Flight::route('GET /api/categories/@id', function ($id) {
    $c = Flight::categoriesService()->getById((int)$id);
    if (!$c) {
        Flight::json(['error' => 'Category not found'], 404);
        return;
    }
    Flight::json($c);
});

Flight::route('POST /api/categories', function () {
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
    $payload = Flight::request()->data->getData();
    try {
        $c = Flight::categoriesService()->update((int)$id, $payload);
        if (!$c) {
            Flight::json(['error' => 'Category not found'], 404);
            return;
        }
        Flight::json($c);
    } catch (InvalidArgumentException $e) {
        Flight::json(['error' => $e->getMessage()], 400);
    } catch (Throwable $e) {
        Flight::json(['error' => 'Server error'], 500);
    }
});

Flight::route('DELETE /api/categories/@id', function ($id) {
    $ok = Flight::categoriesService()->delete((int)$id);
    if (!$ok) {
        Flight::json(['error' => 'Category not found'], 404);
        return;
    }
    Flight::json(['status' => 'deleted']);
});

// ORDERS
Flight::route('GET /api/orders', function () {
    Flight::json(Flight::ordersService()->getAll());
});

Flight::route('GET /api/orders/@id', function ($id) {
    $o = Flight::ordersService()->getById((int)$id);
    if (!$o) {
        Flight::json(['error' => 'Order not found'], 404);
        return;
    }
    Flight::json($o);
});

Flight::route('POST /api/orders', function () {
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
    $payload = Flight::request()->data->getData();
    try {
        $o = Flight::ordersService()->update((int)$id, $payload);
        if (!$o) {
            Flight::json(['error' => 'Order not found'], 404);
            return;
        }
        Flight::json($o);
    } catch (InvalidArgumentException $e) {
        Flight::json(['error' => $e->getMessage()], 400);
    } catch (Throwable $e) {
        Flight::json(['error' => 'Server error'], 500);
    }
});

Flight::route('DELETE /api/orders/@id', function ($id) {
    $ok = Flight::ordersService()->delete((int)$id);
    if (!$ok) {
        Flight::json(['error' => 'Order not found'], 404);
        return;
    }
    Flight::json(['status' => 'deleted']);
});

// REVIEWS
Flight::route('GET /api/reviews', function () {
    Flight::json(Flight::reviewsService()->getAll());
});

Flight::route('GET /api/reviews/@id', function ($id) {
    $r = Flight::reviewsService()->getById((int)$id);
    if (!$r) {
        Flight::json(['error' => 'Review not found'], 404);
        return;
    }
    Flight::json($r);
});

Flight::route('POST /api/reviews', function () {
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
    $payload = Flight::request()->data->getData();
    try {
        $r = Flight::reviewsService()->update((int)$id, $payload);
        if (!$r) {
            Flight::json(['error' => 'Review not found'], 404);
            return;
        }
        Flight::json($r);
    } catch (InvalidArgumentException $e) {
        Flight::json(['error' => $e->getMessage()], 400);
    } catch (Throwable $e) {
        Flight::json(['error' => 'Server error'], 500);
    }
});

Flight::route('DELETE /api/reviews/@id', function ($id) {
    $ok = Flight::reviewsService()->delete((int)$id);
    if (!$ok) {
        Flight::json(['error' => 'Review not found'], 404);
        return;
    }
    Flight::json(['status' => 'deleted']);
});


Flight::route('GET /docs', function() {
    require __DIR__ . '/docs.html';
});


Flight::start();
