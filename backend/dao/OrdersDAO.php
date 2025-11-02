<?php
declare(strict_types=1);
$pdo = require dirname(__DIR__, 2) . '/config.php';
require dirname(__DIR__, 1) . '/dao/BaseDao.php';
require dirname(__DIR__, 1) . '/dao/OrdersDao.php';

$dao = new OrdersDao($pdo);


$id = $dao->create([
  'user_id' => 2,
  'status'  => 'pending',
  'total'   => 0.00
]);
echo "Created order id = $id\n";

print_r($dao->getById($id));

echo "Update: " . ($dao->update($id, ['status'=>'paid', 'total'=>5.60]) ? "OK":"FAIL") . "\n";

$all = $dao->getAll([], ['limit'=>10]);
echo "Fetched: " . count($all) . "\n";

echo "Delete: " . ($dao->delete($id) ? "OK":"FAIL") . "\n";
