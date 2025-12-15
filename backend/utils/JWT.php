<?php
declare(strict_types=1);

final class Jwt {
  public static function encode(array $payload, string $secret, int $ttlSeconds = 3600): string {
    $header = ['alg' => 'HS256', 'typ' => 'JWT'];
    $now = time();

    $payload['iat'] = $now;
    $payload['exp'] = $now + $ttlSeconds;

    $h = self::b64(json_encode($header, JSON_UNESCAPED_SLASHES));
    $p = self::b64(json_encode($payload, JSON_UNESCAPED_SLASHES));

    $sig = hash_hmac('sha256', "$h.$p", $secret, true);
    $s = self::b64bin($sig);

    return "$h.$p.$s";
  }

  public static function decode(string $jwt, string $secret): array {
    $parts = explode('.', $jwt);
    if (count($parts) !== 3) throw new RuntimeException('Invalid token');

    [$h, $p, $s] = $parts;

    $sigCheck = self::b64bin(hash_hmac('sha256', "$h.$p", $secret, true));
    if (!hash_equals($sigCheck, $s)) throw new RuntimeException('Invalid signature');

    $payload = json_decode(self::b64d($p), true);
    if (!is_array($payload)) throw new RuntimeException('Invalid payload');

    if (isset($payload['exp']) && time() > (int)$payload['exp']) {
      throw new RuntimeException('Token expired');
    }

    return $payload;
  }

  private static function b64(string $json): string {
    return rtrim(strtr(base64_encode($json), '+/', '-_'), '=');
  }
  private static function b64bin(string $bin): string {
    return rtrim(strtr(base64_encode($bin), '+/', '-_'), '=');
  }
  private static function b64d(string $b64): string {
    $b64 = strtr($b64, '-_', '+/');
    return base64_decode($b64 . str_repeat('=', (4 - strlen($b64) % 4) % 4));
  }
}
