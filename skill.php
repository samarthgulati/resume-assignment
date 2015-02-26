<?php 
require_once "pdo.php";

$skill_stmt = $pdo->prepare("SELECT u.first_name, u.last_name, u.headline, u.picture, u.email FROM Profile as u LEFT JOIN Skills as s on s.id_profile = u.id_profile where s.name = :skill");
$skill_stmt->execute(array(':skill' => $_GET["skill"]));
echo json_encode($skill_stmt->fetchAll());
