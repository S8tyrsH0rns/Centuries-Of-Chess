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
	<!-- The left side of split screen contains gameplay - including the board, game controls + list of moves made -->
	<div class="split left centered">
		<div class="board-area">
			<div class="board-row">
				<div id="board"></div>
				<!-- Below are the controls for the game, including resetting the board and promotion -->
				<div class="game-controls">
					<button onclick="resetBoard()" class="icon-button"> ⟳ </button>

					<div class="promotion-options" id="promotion-options">
						<ul class="menu-items">
							<li class="item"><button class="icon-button" data-piece="q">♕</button></li>
							<li class="item"><button class="icon-button" data-piece="r">♖</button></li>
							<li class="item"><button class="icon-button" data-piece="b">♗</button></li>
							<li class="item"><button class="icon-button" data-piece="n">♘</button></li>
						</ul>
					</div>
				</div>
			</div>
			<div class="move-list" id="list-of-moves"></div>
		</div>
	</div>

	<!-- The spilt screen on the right displays data about the current played game.  -->

	<div class="split right">
		<div class="tab">
			<button class="tab-link" onclick="openTab(event, 'Info-Page')" id="defaultOpen">Info</button>
			<button class="tab-link" onclick="openTab(event, 'Opening-Explorer-Tab')">Move Explorer</button>
			<button class="tab-link" onclick="openTab(event, 'Statistics')">Statistics </button>
			<button class="tab-link" onclick="openTab(event, 'Game-Database-Tab')">Game Database </button>
		</div>
		
		<div id="Info-Page" class="tab-contents">
			<!-- Welcome/information page explaining what the project is and how to use it -->
			<h3><u>What is Centuries of Chess?</u></h3>
			<p>Centuries of Chess is an interactive way of visualising the vast number of chess games that have been played throughout history. By matching the moves you play on the board
			to a database of previously played games (currently at over 2.1 million games), the website allows you to discover how you play compares with those who played in the past.
			There are three different ways of looking back at past games: </p>
			
			<div class="subsection">
				<h4><u>Move Explorer</u></h4>
				<p>The purpose of the Move Explorer is to show the most popular next move among games that match the current moves made by you. It displays how frequently each next move is 
				played alongside the win count for the games that follow. At the start of this game this can act as an Opening Explorer, naming any openings it detects.</p>
				
				<h4><u>Statistics</u></h4>
				<p>The Statistics page shows general statistics of the current game played (such as white/black win rate, average elo for white/black). It gives insight into the level of gameplay for both sides, though the quality of data will degrade as fewer and fewer games exist to pull the data from.</p>
				
				<h4><u>Game Database</u></h4>
				<p>This panel shows a table of data on individual games with results being able to be sorted by things like earliest games or highest ranking elo. It provides the full list of moves for games that match the moves you make so you can follow the exact path of a game. This tab is most useful for looking at specific games, using the sorting filter to find interesting games.</p>
			</div>
			
			<h3><u>Development and Design</u></h3>
			
		</div>
		
		<div id="Opening-Explorer-Tab" class="tab-contents">
			<!-- This tab is about future moves, early on this will act as an opening explorer, later on a best move selector -->
			<h3><u>Move Explorer: </u></h3>
			<p>The Move Explorer shows the data for the next possible move, with what percentage of games make each move next and how that affects the win rate for either sides. Click any of the results in the next move tab to make the move on the board.</p>
			
			<table class="game-explorer-table">
				<thead>
					<tr>
						<th>Next Move</th>
						<th>Number of Games</th>
						<th>Win Count</th>
						<th>% of Remaining Games</th>
					</tr>
				</thead>
				<tbody id="game-explorer-body">
				</tbody>
			</table>
			
			<div id="explorer-loading-message" class="all-messages"> </div>
			
		</div>
		
		<div id="Statistics" class="tab-contents">
			<!-- Information on games as a whole, white win percent/black win percent based on current game state -->
			<h3><u>Statistics</u></h3>
			<p>This panel shows information about all remaining games, giving insight into how a game evolves over time as more moves are made. It is worth noting that these are averages over all remaining games, so as the number of games falls the data will be less accurate.</p>
			<div class = "statistics-list">
				<div id="remainingGames"> </div>
				<div id="winPercentage"> </div>
				<div id="eloAverages"> </div>
				<div id="avgGameLength"> </div>
			</div>
		</div>
		
		<div id="Game-Database-Tab" class="tab-contents">
			<!-- Sorting menu -->
			<div class = "search-settings">
				<label for="sort">Sort By: </label>
				
				<select name="sort" id="sort">
					<option value="elo">Elo</option>
					<option value="elo-difference">Elo Difference</option>
					<option value="longest">Longest Game</option>
					<option value="shortest">Shortest Game</option>
					<option value="oldest">Oldest</option>
					<option value="newest">Newest</option>
				</select>
			</div>
			
			<!-- Future Feature: Display result count here -->
			
			
			<table class="game-table">
				<thead>
					<tr>
						<th>Year</th>
						<th>Names</th>
						<th>Elo</th>
						<th>Move Count</th>
						<th>Moves</th>
						<th>Result</th>
					</tr>
				</thead>
				<tbody id="games-body">
					
				</tbody>
			</table>
			
			<!-- Loading message to appear when database being searched -->
			<div id="loading-message" class="all-messages"> </div>
			
			<div class="page-controls">
				<button id="previous-page" onclick="prevPage()" disabled="true">Prev.</button>
				<!-- Future Feature: go to page by entering a number -->
				<span id="page-label">Page 1</span>
				<button id="next-page" onclick="nextPage()">Next.</button>
			</div>
			
		</div>
		
	</div>
	
	
</div>

<script>
	var game = new Chess();
	var pendingMove = null;
	var promotionMenu = document.getElementById("promotion-options");

	//Updates the move history tab below the board when a move is made.
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
		
		//Game has changed so data is stale
		staleGame = true;
		staleStats = true;
		staleExplorer = true;
		currentPage = 1;
		
		if ((document.getElementById('Game-Database-Tab').style.display === 'block') && (staleGame == true)) {
			loadGames();
		} 
		
		if ((document.getElementById('Statistics').style.display === 'block') && (staleStats == true)) {
			loadStats();
		} 
		
		if ((document.getElementById('Opening-Explorer-Tab').style.display === 'block') && (staleExplorer == true)) {
			loadExplorer();
		}
		
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

	//Function called when player drops a piece
	function onSnapEnd() {
		if (pendingMove) {
			return;
		}
		board.position(game.fen());
		updateHistory();
	}
	
	//Board settings
	var board = Chessboard('board', {
		position: 'start',
		draggable: true,
		dropOffBoard: 'snapback',
		onDragStart: onDragStart,
		onDrop: onDrop,
		onSnapEnd: onSnapEnd
	});
	
	//Handling resizing the board when the window is resized
	window.addEventListener('resize', function() {
    board.resize();
	});
	
	//Redraws the table when the sort is changes, also sets page back to 1.
	document.getElementById('sort').addEventListener('change', function() {
		currentPage = 1;
		loadGames();
	});
	
	

	//Handles promotion for pawn
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

<script>
function openTab(evt, tabName) {
		var i, tabContents, tabLink;
		
		//Hide all elements with class="tabContents"
		tabContents = document.getElementsByClassName("tab-contents");
		for (i = 0; i < tabContents.length; i++) {
			tabContents[i].style.display = "none";
		}
		
		//Remove "active" class from all elements with class "tabLink"
		tabLink = document.getElementsByClassName("tab-link");
		for (i = 0; i < tabLink.length; i++) {
			tabLink[i].className = tabLink[i].className.replace(" active", "");
		}
		
		//Show current tab and add "active" class to selected button
		document.getElementById(tabName).style.display = "block";
		evt.currentTarget.className += " active";
		
		if (tabName === 'Game-Database-Tab' && staleGame) {
			loadGames();
		} else if (tabName === 'Statistics' && staleStats) {
			loadStats();
		} else if (tabName === 'Opening-Explorer-Tab' && staleExplorer) {
			loadExplorer();
		}
	}
	
	document.getElementById("defaultOpen").click();
</script>

<script>
var currentPage = 1;
var gameRequestId = 0;
var staleGame = true;

//Retrieves list 20 games, based on game history, sort method and page number
async function loadGames() {
	var requestId = ++gameRequestId;
	var movesSent = game.history().join(' ');
	var sort = document.getElementById('sort').value;
	
	var url = 'search.php?moves=' + encodeURIComponent(movesSent) + 
	'&sort=' + encodeURIComponent(sort) + '&page=' + currentPage;
	
	showMessage("Loading...");
	
	try {
		var response = await fetch(url);
		if (!response.ok) {
			throw new Error("Error: " + response.status);
		}
		
		var data = await response.json();
		
		if (requestId !== gameRequestId) {
			return;
		}
		
		//StaleGame is set to false as the data has been freshly gathered
		staleGame = false;
		renderTable(data);
		
	} catch (err) {
		if (requestId !== gameRequestId) {
			return;
		}
		console.error(err);
		showMessage("Could not load games, please try again!");
	}
}

function showMessage(message) {
	document.getElementById('games-body').replaceChildren();
	document.getElementById("loading-message").textContent = message;
}

function renderTable(data) {
	//Resets table to be remade
	const body = document.getElementById('games-body');
	document.getElementById('games-body').replaceChildren();
	
	//Displays warning if no games exist
	if (data.results.length === 0) {
		showMessage("No Games Found");
		updatePage(data);
		return;
	}
	
	showMessage('');
	
	//Recursively creates rows
	for (const gameData of data.results) {
		const row = document.createElement('tr');
		
		const year = row.insertCell();
		const names = row.insertCell();
		const elo = row.insertCell();
		const moveCount = row.insertCell();
		const gameMoves = row.insertCell();
		const result = row.insertCell();
		
		year.textContent = gameData.year ?? "-";
		names.textContent = (gameData.white_name ?? "Unknown") + "\r\n" + (gameData.black_name ?? "Unknown");
		elo.textContent = (gameData.white_elo ?? "-") + "\r\n" + (gameData.black_elo ?? "-");
		moveCount.textContent = gameData.move_count;
		
		
		const playedMoves = game.history();
		const rowMoves = (gameData.moves ?? "").trim().split(/\s+/);
		const moveArray = rowMoves.filter(move => move !== "");

		gameMoves.replaceChildren();

		moveArray.forEach((move, index) => {
			const span = document.createElement("span");
			span.textContent = move;

			if (
				index < playedMoves.length &&
				move === playedMoves[index]
			) {
				span.classList.add("played-move");
			} 
			
			if (index === playedMoves.length - 1 && 
				move === playedMoves[index]
			) {
				span.classList.add("latest-move");
			} 

			gameMoves.appendChild(span);

			if (index < moveArray.length - 1) {
				gameMoves.appendChild(document.createTextNode(" "));
			}
		});

		//gameMoves.textContent = gameData.moves;
		
		switch (gameData.result) {
			case "b":
				result.textContent = "Black";
				break;
			case "w":
				result.textContent = "White";
				break;
			default:
				result.textContent = "Draw";
				break;
		}
		
		body.appendChild(row);
		
		
	}
	updatePage(data);
}


function updatePage(data) {
	const pageCounter = document.getElementById('page-label')
	pageCounter.textContent = 'Page: ' + currentPage;
	
	//If on first page, disable the previous page button
	if (currentPage == 1) {
		document.getElementById("previous-page").disabled = true;
	} else {
			document.getElementById("previous-page").disabled = false;
		}
	//If next page doesnt exist, disable next page button
	if (!data.hasNext) {
		document.getElementById("next-page").disabled = true;
	} else {
			document.getElementById("next-page").disabled = false;
		}
		
}

function prevPage() {
	currentPage -= 1;
	loadGames();
}

function nextPage() {
	currentPage += 1;
	loadGames();
}

</script>
<script>
var statsRequestId = 1;
var staleStats = true;

//Similar to loadGames()
async function loadStats() {
	var requestId = ++statsRequestId;
	var movesSentStats = game.history().join(' ');
	
	var urlStats = 'stats.php?moves=' + encodeURIComponent(movesSentStats);
	
	try {
		var response = await fetch(urlStats);
		if (!response.ok) {
			throw new Error("Error: " + response.status);
		}
		
		var statsData = await response.json();
		
		if (requestId !== statsRequestId) {
			return;
		}
		staleStats = false;
		renderStats(statsData);
		
	} catch (err) {
		if (requestId !== statsRequestId) {
			return;
		}
		console.error(err);
	}
	
}

function renderStats(data) {
	
	//Updates text in stats tab
	document.getElementById("remainingGames").textContent = "Remaining Games: " + data.games.toLocaleString();
	document.getElementById("winPercentage").textContent = "White/Draw/Black Win Rate: \n" + data.white_wins.toLocaleString() + " / " + (data.games - data.white_wins - data.black_wins).toLocaleString() + " / " + data.black_wins.toLocaleString();
	if (data.avg_move_count) {
		document.getElementById("eloAverages").textContent = "Average White Elo/Average Black Elo: \n" + data.avg_white_elo.toLocaleString() + " / " + data.avg_black_elo.toLocaleString();
		document.getElementById("avgGameLength").textContent = "Average Game Length: " + data.avg_move_count.toLocaleString() + " moves";
	} else {
		document.getElementById("eloAverages").textContent = " - / - ";
		document.getElementById("avgGameLength").textContent = " - ";
	}
}

</script>

<script>
var explorerRequestId = 0;
var staleExplorer = true;

async function loadExplorer() {
	var requestId = ++explorerRequestId;
	var movesSentExplorer = game.history().join(' ');
	
	var urlExplorer = 'moveExplorer.php?moves=' + encodeURIComponent(movesSentExplorer);
	
	showExplorerMessage("Loading...");
	
	try {
		var response = await fetch(urlExplorer);
		if (!response.ok) {
			throw new Error("Error: " + response.status);
		}
		
		var explorerData = await response.json();
		
		if (requestId !== explorerRequestId) {
			return;
		}
		staleExplorer = false;
		renderExplorerTable(explorerData);
		
	} catch (err) {
		if (requestId !== explorerRequestId) {
			return;
		}
		console.error(err);
	}
	
}

function renderExplorerTable(data) {
	const body = document.getElementById('game-explorer-body');
	document.getElementById('game-explorer-body').replaceChildren();
	
	//Displays warning if no games exist
	if (data.length === 0) {
		showExplorerMessage("No Games Found");
		return;
	}
	
	showExplorerMessage('');
	
	
	data.forEach((entry) => {
		const row = document.createElement('tr');
		
		const next_move = row.insertCell();
		const numOfGames = row.insertCell();
		const winRate = row.insertCell();
		const gamePercent = row.insertCell();
		
		//Adds a button to make the specified move
		const makeMoveButton = document.createElement("button");
		makeMoveButton.textContent = entry.next_move ?? "-";
		makeMoveButton.addEventListener('click', () => playMove(entry.next_move));
		next_move.appendChild(makeMoveButton);
		
		numOfGames.textContent = entry.games ?? "-";
		winRate.textContent = entry.white_wins.toLocaleString() + " / " + (entry.games - entry.white_wins - entry.black_wins).toLocaleString() + " / " + entry.black_wins.toLocaleString();
		gamePercent.textContent = ((entry.games / entry.total) * 100).toFixed(1) + "%";
		
		body.appendChild(row);
	});
}

function showExplorerMessage(message) {
	document.getElementById('game-explorer-body').replaceChildren();
	document.getElementById("explorer-loading-message").textContent = message;
}

function playMove(requestedMove){
	if (pendingMove || game.game_over()) {
			return false;
		}
	document.getElementById('game-explorer-body').replaceChildren();
	game.move(requestedMove);
	board.position(game.fen());
	updateHistory();
	
}

</script>

</body>
</html>
