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
					<button onclick="resetBoard()" class="icon-button" title="Reset Board"> ⟳ </button>
					<button onclick="undoMove()" class="icon-button" title="Undo Move"> ⤺ </button>

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

	<!-- The split screen on the right displays data about the current played game.  -->

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
			<p>Centuries of Chess is an interactive way of visualising the vast amount of chess games that have been played throughout history. The moves you make on the board will be matched against a database of previously recorded games  (currently, over 2.1 million), the website allows you to explore how you play, in comparison to those who played in the past.
			There are three different ways of looking back at past games: </p>
			
			<div class="subsection">
				<h4><u>Move Explorer</u></h4>
				<p>The purpose of this tool is to show the most popular following moves based off your current board position. It displays not only how likely the next move is to be
				played, but also the white/black winrate.</p>
				
				<h4><u>Statistics</u></h4>
				<p>The Statistics page shows general statistics of the current game played (such as white/black win rate, average elo for white/black). It gives insight into the level of gameplay for both sides, though the quality of data will degrade as fewer and fewer games exist to pull the data from.</p>
				
				<h4><u>Game Database</u></h4>
				<p>This panel shows a table of data on individual games with results being able to be sorted by things like earliest games or highest ranking elo. It provides the full list of moves for games that match the moves you make so you can follow the exact path of a game. This tab is most useful for looking at specific games, using the sorting filter to find interesting games.</p>
			</div>
			
			<h3><u>Development and Design</u></h3>
			<div class="subsection">
				<h4><u>Database Design and PGN Parser</u></h4>
				<p>From the earliest stages of development, I wanted more than just a database of previous games. I realised insight could be gained from looking at the statistics of the players and the outcomes for the games they play. Whilst researching publicly available game collections to use I stumbled upon the Lichess Elite Database, a collection of public games played on the Lichess website.</p>
				<figure>
					<img src="img/PGNParser.png" alt="A screenshot of code for parse_elite_file function" width="710" height="617">
					<figcaption>Fig.1 - Code Snippet of parse_elite_file function.</figcaption>
				</figure>
				<p> To extract only the data I felt was necessary for the database I wrote a custom Python script to parse the PGN files and transfer relevant data to the database. The parser could parse multiple files from running just once. The database currently contains over 2.1 million games to serve as a proof of concept. I hope to find more data sources to grow the collection of games (particularly older games).</p>
			
			<h4><u>Creating the game</h4></u>
			<p>To create the board and game pieces I made use of the <a href="https://chessboardjs.com/" target="_blank">chessboard.js</a> library. I chose this library as it is a dedicated way of rendering the board without having to create all the chess elements. The only downside of using this library being that all logic needed to be added separately. To help with creating game logic I also used <a href="https://github.com/jhlywa/chess.js" target="_blank">chess.js</a> to manage game state and validate legal moves. </p>
			<p>Because the two libraries handle two different responsibilities, I had to implement logic to integrate user interaction and the rules of chess. One example is adding a custom pawn promotion validator which checks to see if a pawn is in the final rank (depending on its colour) and then displays a menu to select which piece to promote to.</p>
			
			
			<h4><u>Extracting data from the table</u></h4>
			<p>Once I had a playable board and a database to match games filled with data the next step was to turn the stored games into useful information. Each tab shows information in a different way: the Move Explorer highlights trends in the decision making element of the game, the Statistics page finds average information about remaining games, and the Game Database allows you look more in-depth with specific games and their moves.</p>
			<p>The first representation of data I completed was the game database, where I selected games which had moves LIKE the moves made on the website, the results could be sorted by a variety of different criteria such as the difference between players elo or the earliest game using SQL's ORDER BY parameter. Loading all games from the table which match would be impractical for early games where there are millions of results so I added a page navigation element below the table, limiting each page to a maximum of 20 results and allowing for the SQL query to LIMIT the selection to 21 results (with the 21st result indicating the need for another page of results).</p>
			
			<figure>
					<img src="img/DatabaseConnect.png" alt="Reusable database connect() function shared across database requests" width="710" height="617">
					<figcaption>Fig.2 - Reusable database connect() function shared across database requests</figcaption>
			</figure>
			
			<p>The next tab I worked on was the Statistics tab, which I improved by separating the database connection script so it can be used for multiple requests, instead of repeating code. The statistics data was fairly simple to SELECT, mainly being count()'s of rows and then calculating averages based on those results.</p>
			<p>The final tab to be completed was the Move Explorer, which shows the most common next move made by the remaining players in the games that match the board. I found the next move by counting the number of moves have been made and finding the moves made after the most recently played move. It shows how most players make similar moves even when in unfamiliar situations.</p>
			
			<h4><u>HTML, CSS and JavaScript</u></h4>
			<p>The HTML section of this project was relatively simple, this project did however teach me how to interact with a html page using JavaScript, where the tables are dynamically populated once the data gets pulled.</p>
			<p>As a computer science student, I first found the visual design of the website to be a challenge. I wanted to create a 'split screen' effect for the site, keeping the board accessible even when switching between different representations of the data. I developed my understanding of styling and layout by referring to the MDN Web Docs to build and change to match the desired aesthetic.</p>
			<p>A majority of the work done on this project was with the JavaScript portion of the project. The JavaScript code covers the actual chess gameplay, the tab switching, each database request and the rendering of the recieved data. It involved state management, only making a database call when a tab is loaded with a 'stale' game and acts as the glue which connects each piece of the site.</p>
			</div>
			
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
	
	function undoMove() {
		game.undo();
		board.position(game.fen());
		pendingMove = null;
		promotionMenu.style.display = '';
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
