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

<div class="title-banner">
	<h1>Centuries Of Chess</h1>
	<h2>Chess is a game that has been played for centuries, which begs the question...</h2>
	<h2>how unique is each game?</h2>
</div>

<div class="main">
	<div class="split left centered">
		<div class="board-area">
			<div class="board-row">
				<div id="board"></div>
				<!-- Area for information on past games -->
				<div class="game-controls">
					<button onclick="resetBoard()" class="icon-button"> ⟳ </button>

					<div class="promotion-options" id="promotion-options">
						<ul class="menu-items">
							<li class="item"><button data-piece="q">Queen</button></li>
							<li class="item"><button data-piece="r">Rook</button></li>
							<li class="item"><button data-piece="b">Bishop</button></li>
							<li class="item"><button data-piece="n">Knight</button></li>
						</ul>
					</div>
				</div>
			</div>
			<div class="move-list" id="list-of-moves"></div>
		</div>
	</div>

	<div class="split right">
		
	</div>
</div>

<script>
	var game = new Chess();
	var pendingMove = null;
	var promotionMenu = document.getElementById("promotion-options");

	function updateHistory() {
		var text = "Move history: ";
		var moveHistory = "";
		game.history().forEach(function(move, i) {
			moveHistory += move + " ";
		});
		text += moveHistory;
		var box = document.getElementById("list-of-moves");
		box.textContent = text;
		box.scrollLeft = box.scrollWidth;
	}

	document.getElementById("list-of-moves").addEventListener("wheel", function(e) {
		e.preventDefault();
		this.scrollLeft += e.deltaY;
	});

	function onDragStart() {
		if (pendingMove || game.game_over()) {
			return false;
		}
	}

	function onDrop(source, target, piece) {
		var isPromotion = (piece === 'wP' && target[1] === '8') || (piece === 'bP' && target[1] === '1');

		if (isPromotion) {
			var legal = game.moves({verbose: true}).some(function(m) {
				return m.from === source && m.to === target;
			});
			if (!legal) {
				return 'snapback';
			}
			// Wait for a piece to be chosen from the menu
			pendingMove = {from: source, to: target};
			promotionMenu.style.display = 'block';
			return;
		}

		if (game.move({from: source, to: target}) === null) {
			return 'snapback';
		}
	}

	function onSnapEnd() {
		if (pendingMove) {
			return;
		}
		board.position(game.fen());
		updateHistory();
	}

	var board = Chessboard('board', {
		position: 'start',
		draggable: true,
		dropOffBoard: 'snapback',
		onDragStart: onDragStart,
		onDrop: onDrop,
		onSnapEnd: onSnapEnd
	});

	document.querySelectorAll('.promotion-options button').forEach(function(button) {
		button.addEventListener('click', function() {
			game.move({from: pendingMove.from, to: pendingMove.to, promotion: this.dataset.piece});
			pendingMove = null;
			promotionMenu.style.display = 'none';
			board.position(game.fen());
			updateHistory();
		});
	});

	function resetBoard() {
		game = new Chess();
		pendingMove = null;
		promotionMenu.style.display = 'none';
		board.start();
		updateHistory();
	}

	updateHistory();
</script>

</body>
</html>
