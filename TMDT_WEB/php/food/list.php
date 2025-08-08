<?php
require_once("../../php/db.php");

$sql = "SELECT * FROM san_pham WHERE is_available = 1 ORDER BY phan_loai, id DESC";
$result = $link->query($sql);

$products_by_category = [];

while ($row = $result->fetch_assoc()) {
  $phan_loai = $row['phan_loai'];
  if (!isset($products_by_category[$phan_loai])) {
    $products_by_category[$phan_loai] = [];
  }
  $products_by_category[$phan_loai][] = $row;
}

echo json_encode($products_by_category);
?>
