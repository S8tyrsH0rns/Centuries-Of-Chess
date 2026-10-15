<?php
require __DIR__ . '/common.php';

$prefix = getPrefix();

try {
	$conn = connect();
	$query = $conn -> prepare("
		SELECT count(*) AS games,
		count(*) FILTER (WHERE result = 'w') AS white_wins,
		count(*) FILTER (WHERE result = 'b') AS black_wins,
		round(avg(white_elo))::int AS avg_white_elo,
		round(avg(black_elo))::int AS avg_black_elo,
		round(avg(move_count))::int AS avg_move_count
		FROM games
		WHERE moves LIKE :pattern"
		);
	
	$query ->bindValue(':pattern', $prefix.'%');
	$query->execute();
	
	$stats = $query -> fetch(PDO::FETCH_ASSOC);
} catch (Exception $e) {
	error_log($e->getMessage());
	callError(500, 'Internal Server Error!');
}



echo json_encode($stats);