<?php
header('Content-Type: application/json');

const MAX_PLIES = 1000;

function callError(int $status, string $message): void {
	http_response_code($status); 
	exit(json_encode(['error' => $message]));
}


//$config = require __DIR__ . '/config.php';

//$conn = new PDO(
//	$config['dsn'],
//	$config['user'],
//	$config['pass'],
//	[PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
//);

$raw = $_GET['moves'] ?? '';

if (!is_string($raw)) {
	callError(400, 'Invalid Move!');
}

$tokens = preg_split('/\s/', $raw, -1, PREG_SPLIT_NO_EMPTY);

if (sizeOf($tokens) > MAX_PLIES) {
	callError(400, 'Move Limit Reached!');
}


foreach ($tokens as $token) {
	if (!preg_match('/^(O-O(-O)?|([KQRBN]?|[a-h]?)x?[a-h][a-h]?[1-8]?[1-8](\(=[QRBN]\))?[+#]?)$/', $token)) {
		callError(400, 'Unexpected SAN Pattern!');
	}
}
//$row = $conn -> query('SELECT count(*)') -> fetchAll(PDO::FETCH_ASSOC);


echo json_encode($tokens); 