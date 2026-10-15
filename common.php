<?php
header('Content-Type: application/json');
//Common.php makes requesting different data from the database


//Setting search rules for the database
const MAX_PLIES = 1000;

//This is the different versions of sorting that can be performed
$orders = [
	'elo' => 'white_elo + black_elo DESC NULLS LAST, id',
	'elo-difference' => 'ABS(white_elo - black_elo) DESC NULLS LAST, id',
	'longest' => 'move_count DESC NULLS LAST, id',
	'shortest' => 'move_count ASC NULLS LAST, id',
	'oldest' => 'year ASC NULLS LAST, id',
	'newest' => 'year DESC NULLS LAST, id'
];

function callError(int $status, string $message): void {
	http_response_code($status); 
	exit(json_encode(['error' => $message]));
}

function getPrefix() {
	//Recieving users moves from site
	$raw = $_GET['moves'] ?? '';

	//Validating the recieved moves before being handed to the database
	//Including checking if its of type string and that its a valid SAN pattern
	//Note: It just checks if the moves are real, not that they make logical sense.
	if (!is_string($raw)) {
		callError(400, 'Invalid Move!');
	}

	$tokens = preg_split('/\s/', $raw, -1, PREG_SPLIT_NO_EMPTY);

	if (sizeOf($tokens) > MAX_PLIES) {
		callError(400, 'Move Limit Reached!');
	}


	foreach ($tokens as $token) {
		if (!preg_match('/^(?:O-O(?:-O)?|[KQRBN](?:[a-h]|[1-8]|[a-h][1-8])?x?[a-h][1-8]|[a-h](?:x[a-h])?[1-8](?:=[QRBN])?)[+#]?$/', $token)) {
			callError(400, 'Unexpected SAN Pattern!');
		}
	}

	if ($tokens) {
		$prefix = implode(' ', $tokens).' ';
	} else {
		//If no moves are made, return an empty prefix, gathing all chess games
		$prefix = '';
	}
	return $prefix;
}
function connect(): PDO {
	//Config required for connecting to database, check config.example.php for what config should look like.
	$config = require __DIR__ . '/config.php';

	
	$conn = new PDO(
		$config['dsn'],
		$config['user'],
		$config['pass'],
		[PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
	);
	
	return $conn;
}
