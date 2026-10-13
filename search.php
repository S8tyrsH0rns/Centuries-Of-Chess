<?php
header('Content-Type: application/json');

//Setting search rules for the database
const MAX_PLIES = 1000;
const PAGE_SIZE = 20;
const MAX_PAGE = 1000;

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

//Validation for the sorting method for the database 
$sortingMethod = $_GET['sort'] ?? 'elo';
if (!is_string($sortingMethod)) {
	callError(400, 'Invalid Sort!');
}

if (!isset($orders[$sortingMethod])) {
	callError(400, 'Invalid Sort!');
}

//Page number sanitation, used to navigate the database
$page = $_GET['page'] ?? '1';

if (!is_string($page)) {
	callError(400, 'Invalid Page!');
}

if (!ctype_digit($page)) {
	callError(400, 'Invalid Page Number!');
}

$page = (int)$page;

if (1 > $page || $page > MAX_PAGE) {
	callError(400, 'Invalid Page Number!');
} 

$offset = ($page - 1) * PAGE_SIZE;

//Config required for connecting to database, check config.example.php for what config should look like.
$config = require __DIR__ . '/config.php';

try {
$conn = new PDO(
	$config['dsn'],
	$config['user'],
	$config['pass'],
	[PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
);

	$query = $conn -> prepare("
		SELECT year, white_name, white_elo, black_name, black_elo, source, result, move_count, moves
		FROM games
		WHERE moves LIKE :pattern
		ORDER BY {$orders[$sortingMethod]}
		LIMIT :limit OFFSET :offset"
		);
	
	$query ->bindValue(':pattern', $prefix.'%');
	$query ->bindValue(':limit', PAGE_SIZE + 1);
	$query ->bindValue(':offset', $offset);
	$query->execute();
	
	$rows = $query -> fetchAll(PDO::FETCH_ASSOC);
	
} catch (Exception $e) {
	error_log($e->getMessage());
	callError(500, 'Internal Server Error!');
}

$results = array_slice($rows, 0, PAGE_SIZE);
$hasNext = count($rows) > PAGE_SIZE;


echo json_encode(['prefix' => $prefix, 'sort' => $sortingMethod, 'offset' => $offset, 'results' => $results, 'hasNext' => $hasNext]);