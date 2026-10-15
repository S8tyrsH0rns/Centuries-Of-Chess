<?php
require __DIR__ . '/common.php';

$prefix = getPrefix();

//Setting search rules for the database
const PAGE_SIZE = 20;
const MAX_PAGE = 1000;

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

try {
	$conn = connect();
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