<?php
declare(strict_types=1);

/* ---------- FLIGHT ---------- */
require_once __DIR__ . '/flight/Flight.php';

/* ---------- DATABASE ---------- */
$pdo = require __DIR__ . '/config.php';
Flight::set('pdo', $pdo);

/* ---------- DAO ---------- */
require_once __DIR__ . '/dao/BaseDAO.php';
require_once __DIR__ . '/dao/UsersDAO.php';
require_once __DIR__ . '/dao/ProductsDAO.php';
require_once __DIR__ . '/dao/CategoriesDAO.php';
require_once __DIR__ . '/dao/OrdersDAO.php';
require_once __DIR__ . '/dao/ReviewsDAO.php';
require_once __DIR__ . '/dao/RolesDAO.php';

/* ---------- SERVICES ---------- */
require_once __DIR__ . '/services/UsersService.php';
require_once __DIR__ . '/services/ProductsService.php';
require_once __DIR__ . '/services/CategoriesService.php';
require_once __DIR__ . '/services/OrdersService.php';
require_once __DIR__ . '/services/ReviewsService.php';

/* ---------- UTILS & MIDDLEWARE ---------- */
require_once __DIR__ . '/utils/Jwt.php';
require_once __DIR__ . '/middleware/AuthMiddleware.php';

/* ---------- JWT SECRET ---------- */
Flight::set('jwt_secret', getenv('JWT_SECRET') ?: 'CHANGE_ME_DEV_SECRET');

/* ---------- SERVICE MAPPING ---------- */
Flight::map('usersService', fn() => new UsersService(Flight::get('pdo')));
Flight::map('productsService', fn() => new ProductsService(Flight::get('pdo')));
Flight::map('categoriesService', fn() => new CategoriesService(Flight::get('pdo')));
Flight::map('ordersService', fn() => new OrdersService(Flight::get('pdo')));
Flight::map('reviewsService', fn() => new ReviewsService(Flight::get('pdo')));

/* ---------- AUTH ROUTES ---------- */
require_once __DIR__ . '/routes/auth.php';

/* ---------- ENTITY ROUTES ---------- */
require_once __DIR__ . '/routes/users.php';
require_once __DIR__ . '/routes/products.php';
require_once __DIR__ . '/routes/categories.php';
require_once __DIR__ . '/routes/orders.php';
require_once __DIR__ . '/routes/reviews.php';

/* ---------- SWAGGER DOCS ---------- */
Flight::route('GET /docs', function () {
    require __DIR__ . '/docs.html';
});

/* ---------- MIDDLEWARE ATTACH ---------- */
AuthMiddleware::attach();

/* ---------- START APP ---------- */
Flight::start();
