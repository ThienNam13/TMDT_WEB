<?php
require_once("../../php/db.php");

$sql = "SELECT * FROM foods WHERE is_available = 1 ORDER BY loai, id DESC";
$result = $link->query($sql);

$foods_by_category = [];

while ($row = $result->fetch_assoc()) {
  $loai = $row['loai'];
  if (!isset($foods_by_category[$loai])) {
    $foods_by_category[$loai] = [];
  }
  $foods_by_category[$loai][] = $row;
}

echo json_encode($foods_by_category);
?>
