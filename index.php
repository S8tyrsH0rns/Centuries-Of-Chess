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
	
<div class="split left">
	<div id="board">
		<script>
			var game = new Chess();
			var board = Chessboard('board', {position: 'start', draggable: true, dropOffBoard: 'snapback', 
			
			onDrop: function(source, target, piece, newPos, oldPos, orientation) {
				if ((orientation == 'white' && target.includes('8')) || (orientation == 'black' && target.includes('1'))) {
					var move = game.move({from: source, to: target, promotion: 'q'});
				} else {
					var move = game.move({from: source, to: target});
				}
				if (move === null) {
					return 'snapback';
				}
				console.log(game.history());
				board.position(game.fen());
			},
			
			onSnapEnd: function() {
				board.position(game.fen());
			}
			
		}) 
		</script>	
	</div>
	<!-- Needs Fixing! Currently No Pawn Promotion -->
	<div class="promotion-options">
		<ul class="menu-items">
			<li class="item">
			  <button>Queen</button>
			</li>
			<li class="item">
			  <button>Rook</button>
			</li>
			<li class="item">
			  <button>Bishop</button>
			</li>
			<li class="item">
		  <button>Knight</button>
			</li>
		</ul>
	</div>
</div>
<div class="split right">
	<!-- Area for information on past games and button controls for resetting, undoing/redoing -->
	<button onclick="resetBoard()" class="icon-button"> ⟳ </button>
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