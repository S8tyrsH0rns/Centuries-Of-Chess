<?php
header('Content-Type: application/json');

$config = require __DIR__ . '/config.php';

$conn = new PDO(
	$config['dsn'],
	$config['user'],
	$config['pass'],
	[PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
);

$row = $conn -> query('SELECT count(*)') -> fetchAll(PDO::FETCH_ASSOC);

echo json_encode($row); 