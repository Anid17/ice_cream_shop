<?php
declare(strict_types=1);

final class AuthMiddleware {
  public static function attach(): void {
    // Basic request validation (JSON on POST/PUT/PATCH)
    Flight::before('start', function (&$params, &$output) {
      $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
      if (in_array($method, ['POST','PUT','PATCH'], true)) {
        $ct = $_SERVER['CONTENT_TYPE'] ?? '';
        if ($ct && stripos($ct, 'application/json') !== false) {
          // Flight parses JSON into request()->data automatically.
        }
      }

      $authHeader = $_SERVER['HTTP_AUTHORIZATION'] ?? '';
      if (preg_match('/Bearer\s+(.+)/i', $authHeader, $m)) {
        try {
          $payload = Jwt::decode(trim($m[1]), Flight::get('jwt_secret'));
          Flight::set('auth_user', $payload);
        } catch (Throwable $e) {
          // invalid token => treat as unauthenticated
          Flight::set('auth_user', null);
        }
      } else {
        Flight::set('auth_user', null);
      }
    });
  }

  public static function requireAuth(): array {
    $u = Flight::get('auth_user');
    if (!$u) {
      Flight::json(['error' => 'Unauthorized'], 401);
      Flight::halt(401);
    }
    return $u;
  }

  public static function requireAdmin(): array {
    $u = self::requireAuth();
    // admin role_id = 1
    if ((int)($u['role_id'] ?? 2) !== 1) {
      Flight::json(['error' => 'Forbidden'], 403);
      Flight::halt(403);
    }
    return $u;
  }
}
