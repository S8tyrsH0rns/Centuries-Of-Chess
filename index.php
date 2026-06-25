<!DOCTYPE html>
<html>
<head>
    <title>Centuries Of Chess</title>
	<link rel="stylesheet" href="css/styles.css">
	<link rel="stylesheet" href="css/chessboard-1.0.0.css">
	 
	<script src="js/jquery-4.0.0.js"></script>
	<script src="js/chessboard-1.0.0.js"></script>

	<script src="https://cdnjs.cloudflare.com/ajax/libs/chess.js/0.10.3/chess.min.js"></script>
	<script src="js/board.js"></script>
</head>

<body>

<h1>Centuries Of Chess</h1>
<h2>Chess is a game that has been played for centuries, which begs the question...</h2>
<h2>how unique is each game?</h2>
	
<div id="board">
	<script>
		var game = new Chess();
		var board = Chessboard('board', {position: 'start', draggable: true, dropOffBoard: 'snapback', 
		
		onDrop: function(source, target, piece, newPos, oldPos, orientation) {
			if ((orientation == 'white' && target.includes('8')) || (orientation == 'black' && target.includes('1'))) {
				console.log('promotion')
			}
			
			const move = game.move({from: source, to: target});
			
			if (move === null) {
				return 'snapback';
			}
			console.log(game.history());
			board.position(game.fen());
		}
		
		}) 
		

	</script>
	<button onclick="resetBoard()"> ⟳ </button>
</div>

<script>
	function resetBoard(){
		console.log("Resetting...");
		board.start();
		game = new Chess();
	}
</script>
</body>
</html>