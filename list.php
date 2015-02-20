<?php 
require_once "pdo.php";

$list_stmt = $pdo->prepare("SELECT u.first_name, u.last_name, u.headline, u.picture, u.email FROM Profile as u");
$list_stmt->execute();
echo json_encode($list_stmt->fetchAll());
