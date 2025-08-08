<?php
require_once("../../php/db.php");

$keyword = $_GET['q'] ?? '';
$keyword = "%" . $keyword . "%";

$stmt = $link->prepare("SELECT * FROM san_pham WHERE is_available = 1 AND (ten_sp LIKE ? OR mo_ta LIKE ?)");
$stmt->bind_param("ss", $keyword, $keyword);
$stmt->execute();
$result = $stmt->get_result();

$products = [];
while ($row = $result->fetch_assoc()) {
  $products[] = $row;
}

echo json_encode($products);
?>
