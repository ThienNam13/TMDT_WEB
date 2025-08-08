<?php
require_once("../../php/db.php");

$keyword = $_GET['q'] ?? '';
$keyword = "%" . $keyword . "%";

$stmt = $link->prepare("SELECT * FROM foods WHERE is_available = 1 AND (ten_mon LIKE ? OR mo_ta LIKE ?)");
$stmt->bind_param("ss", $keyword, $keyword);
$stmt->execute();
$result = $stmt->get_result();

$foods = [];
while ($row = $result->fetch_assoc()) {
  $foods[] = $row;
}

echo json_encode($foods);
?>
