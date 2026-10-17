<?php
require __DIR__ . '/common.php';

$prefix = getPrefix();
$field = substr_count($prefix, ' ') + 1;

try {
	$conn = connect();
	$query = $conn -> prepare("
		SELECT split_part(moves, ' ', :field) AS next_move,
		count(*) AS games,
		count(*) FILTER (WHERE result = 'w') AS white_wins,
		count(*) FILTER (WHERE result = 'b') AS black_wins,
		sum(count(*)) OVER () AS total
		FROM games
		WHERE moves LIKE :pattern
		AND moves <> :currentMoves
		GROUP BY next_move
		ORDER BY games DESC, next_move
		LIMIT 10
		"
		);
	
	$query ->bindValue(':field', $field, PDO::PARAM_INT);
	$query ->bindValue(':pattern', $prefix.'%');
	$query ->bindValue(':currentMoves', $prefix);
	$query->execute();
	
	$moveExploration = $query -> fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
	error_log($e->getMessage());
	callError(500, 'Internal Server Error!');
}



echo json_encode($moveExploration);