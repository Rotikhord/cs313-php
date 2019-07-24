
/*These are arbitrary numbers that are a result of setting canvas size to 80%vw */
var boardWidth;

//Sets the amount of blocks that can fit either vertically or horizontally.
var blockNumber = 50;

//The calculated block size values for the game.
var blockWidth;

//Stores the last key pressed.
var lastkey;

//Controls the number of pellets on the field
var maxPellets = (blockNumber / 4);

//Stores the current game object
var game;

//Handles the game engine
var gameTick;
var baseTick = 250;

//Local highscores
var localScores = [];
var globalScores = [];

var playerColor = 'red';
var pelletColor = 'green';
var overrideColor = 'null';

function Color(lightest, lighter, light, normal, dark, darker, darkest) {
  this.lightest = '#' + lightest;
  this.lighter = '#' + lighter;
  this.light = '#' + light; 
  this.normal = '#' + normal;
  this.dark = '#' + dark;
  this.darker = '#' + darker;
  this.darkest = '#' + darkest;
}

var RED = new Color('FF5151', 'FFBDBD', 'FF8080', 'FF0000', 'D80000', '8B0000','580000')
var ORANGE = new Color('FFA351', 'FFDCBD', 'FFBC80', 'FF7800', 'D86600', '8B4200', '582A00')
var BLUE = new Color('43C1CD', 'B3ECF1', '71D5DE', '028E9B', '027883', '004D55', '003136')
var GREEN = new Color('49E649', 'B7F8B7', '77EE77', '00CC00', '00AC00', '006F00', '004700')
var COLORS = [RED, ORANGE, BLUE, GREEN];
var mainColor = 0;
var secondaryColor = 0;
var shaderVal = 0;
var isDarkMode = false;

function highScore(name, score, date) {
  this.name = name;
  this.score = score;
  this.date = date.toLocaleDateString("en-US");
}

function Snake() {
  this.direction = "right";
  this.blocks = [new GameBlock(blockWidth, blockWidth, blockWidth * parseInt(blockNumber / 2), blockWidth * parseInt(blockNumber / 2), playerColor)];
  this.getBlocks = function () { return this.blocks; }
  this.isAlive = true;

  //This function handles collisions with pellets
  this.handleCollisions = function (pelletList) {
    for (var i = 0; i < pelletList.length; i++) {
      if (this.blocks[0].checkCollision(pelletList[i])) {
        //console.log("length = " + this.blocks.length);
        this.blocks.push(new GameBlock(blockWidth, blockWidth, pelletList[i].xLoc, pelletList[i].yLoc, playerColor));
        game.points += (game.level * (game.level + 1) / 2 * 100);
        console.log("Points = " + game.points);
        pelletList = pelletList.splice(i, 1);
        //console.log("length = " + this.blocks.length);

      }
    }
  }

  //This function gets the value of the last key press and if necessary updates the snake direction.
  this.updateDirection = function () {
    switch (lastkey) {
      case 39:
        if (this.direction != 'left') {
          this.direction = 'right';
        }
        break;
      case 37:
        if (this.direction != 'right') {
          this.direction = 'left';
        }
        break;
      case 38:
        if (this.direction != 'up') {
          this.direction = 'down';
        }
        break;
      case 40:
        if (this.direction != 'down') {
          this.direction = 'up';
        }
        break;
    }
  }



  this.updateSnake = function () {
    //Only update snake if is alive
    if (this.isAlive) {
      //This updates the location of each block in the snake except for the "headblock"
      //Use the same loop to check for self-collisions to save resources.
      for (var i = this.blocks.length - 1; i > 0; i--) {

        this.blocks[i].xLoc = this.blocks[i - 1].xLoc;
        this.blocks[i].yLoc = this.blocks[i - 1].yLoc;

        if (i != 1 && this.blocks[0].checkCollision(this.blocks[i])) {
          this.isAlive = false;
          break;
        }
      }
      //console.log('blockwidth = ' + blockWidth + "  xloc =" + this.blocks[0].xLoc + " yloc =" + this.blocks[0].yLoc);
      //Updates the headblock & checks for wall collisions
      switch (this.direction) {
        case 'left':
          if (this.blocks[0].xLoc >= blockWidth * .9) {
            this.blocks[0].updateLocation(-blockWidth, 0);
          } else {
            this.isAlive = false;
          }
          break;
        case 'right':
          if (this.blocks[0].xLoc <= (blockWidth * (blockNumber - 1.9))) {
            this.blocks[0].updateLocation(blockWidth, 0);
          } else {
            this.isAlive = false;
          }
          break;
        case 'up':
          if (this.blocks[0].yLoc <= (blockWidth * (blockNumber - 1.9))) {
            this.blocks[0].updateLocation(0, blockWidth);
          } else {
            this.isAlive = false;
          }
          break;
        case 'down':
          if (this.blocks[0].yLoc >= blockWidth * .9) {
            this.blocks[0].updateLocation(0, -blockWidth);
          } else {
            this.isAlive = false;
          }
          break;
        default:
      }
    }
  }
}

//This is the basic game object.
function GameBlock(xSize, ySize, xLoc, yLoc, color) {
  this.xSize = xSize;
  this.ySize = ySize,
    this.xLoc = xLoc;
  this.yLoc = yLoc;
  this.color = color;
  this.isValid

  this.updateLocation = function (xChange, yChange) {
    this.xLoc = this.xLoc + xChange;
    this.yLoc = this.yLoc + yChange;
  }

  this.checkCollision = function (block) {
    if (this.checkAxisCollision(this.xLoc, block.xLoc) && this.checkAxisCollision(this.yLoc, block.yLoc)) {
      return true;
    }
    else {
      return false;
    }
  }

  this.checkAxisCollision = function (point1, point2) {
    if (point1 - point2 > (-blockWidth * .25) && point1 - point2 < (blockWidth * .25)) {
      return true;
    }
  }
}

//This is the game object. It can be reset.
function Game() {

  this.gameBoard = document.getElementById('gameArena');
  this.context = this.gameBoard.getContext('2d');
  this.snake = new Snake();
  this.pelletList = [];
  this.points = 0;
  this.isOver = false;
  this.isPaused = false;
  this.level = 1;
  var parent = this;


  //This function acts as the game engine.
  this.updateGame = function () {
    clearBoard();
    this.points++;
    for (var i = 0; i < parent.snake.getBlocks().length; i++) {
      parent.drawBlock(parent.snake.getBlocks()[i]);
    }

    parent.snake.updateDirection();
    parent.snake.updateSnake();

    if (parent.pelletList.length < maxPellets && parseInt(Math.random() * (parent.pelletList.length * 2) + 1) == 1) {
      parent.pelletList.push(new GameBlock(blockWidth, blockWidth, blockWidth * parseInt(Math.random() * (blockNumber - 1)), blockWidth * parseInt(Math.random() * (blockNumber - 1)), pelletColor));
    }
    for (var i = 0; i < parent.pelletList.length; i++) {
      parent.drawBlock(parent.pelletList[i]);
    }

    parent.snake.handleCollisions(parent.pelletList);
    if (!this.snake.isAlive) {
      this.isOver = true;
      gameOverAnimation();
    }

  }

  //This draws each gameblock on the screen.
  this.drawBlock = function (block) {
    if (overrideColor != 'null') {
      parent.context.fillStyle = overrideColor;
    } else {
      parent.context.fillStyle = block.color;
    }
    parent.context.fillRect(block.xLoc, block.yLoc, block.xSize, block.ySize);
    parent.context.strokeRect(block.xLoc, block.yLoc, block.xSize, block.ySize);
  }

  //This resets the game board between frames
  function clearBoard() {
    parent.context.clearRect(0, 0, parent.gameBoard.width, parent.gameBoard.height);
  }

  //Handles the game speed.



}

function newGameAnimation() {
  var messageDiv = document.getElementsByClassName('messageDiv');
  if (messageDiv.length != 0) {
    while (messageDiv[0].firstChild) {
      messageDiv[0].removeChild(messageDiv[0].firstChild);
    }
    messageDiv[0].parentNode.removeChild(messageDiv[0]);
  }
}

function isHighScore() {
  if (localScores.length < 10 || globalScores.length < 10) {
    return true;
  } else {
    for (var i = 0; i < localScores.length; i++) {
      if (game.points > localScores[i].score) {
        return true;
      }
    }
    for (var i = 0; i < globalScores.length; i++) {
      if (game.points > globalScores[i].score) {
        return true;
      }
    }
  }
  return false;
}

function submitScore(element) {
  var input = document.getElementById('nameInput');
  var name = input.value;
  if (name == '') {
    name = 'Anonymous';
  }
  var newScore = new highScore(name, game.points, new Date)
  if (localScores.length == 0) {
    localScores.push(newScore);
  } else {
    for (var i = 0; i < 10; i++) {
      if (i >= localScores.length) {
        localScores.push(newScore);
        break;
      }
      if (newScore.score > localScores[i].score) {
        localScores.splice(i, 0, newScore);
        break;
      }
      if (localScores.length > 10) {
        localScores.pop();
      }
    }
  }

  if (globalScores.length == 0) {
    globalScores.push(newScore);
  } else {
    for (var i = 0; i < 10; i++) {
      console.log(globalScores.length + ' - length');
      if (i >= globalScores.length) {
        globalScores.push(newScore);
        break;
      }
      if (newScore.score > globalScores[i].score) {
        globalScores.splice(i, 0, newScore);
        break;
      }
      if (globalScores.length > 10) {
        globalScores.pop();
      }
    }
  }


  //input.style.visibility = 'hidden';
  message = document.createElement('h3');
  message.innerText = "Score Recorded!"
  element.parentNode.appendChild(message);
  element.parentNode.removeChild(input);
  element.parentNode.removeChild(element);

  saveLocalScores();
  saveGlobalScores();
  displayHighScores('local');
}

function saveLocalScores() {
  localStorage.setItem('localHighScores', JSON.stringify(localScores));
}

function saveGlobalScores() {
  var request = new XMLHttpRequest();
  request.onreadystatechange = function () {
    if (this.readyState == 4 && this.status == 200) {
      console.log(request.responseText + 'response recieved');
      loadGlobalScores();
    }
  }
  request.open("GET", "saveGlobalScores.php?scores=" + JSON.stringify(globalScores), true);
  request.send();
}

function loadLocalScores() {
  if (localStorage.getItem('localHighScores') != null) {
    localScores = JSON.parse(localStorage.getItem('localHighScores'));
  }
}

//This function gets global highscores from the server. 
function loadGlobalScores() {
  var request = new XMLHttpRequest();
  request.onreadystatechange = function () {
    if (this.readyState == 4 && this.status == 200) {
      globalScores = JSON.parse(request.responseText);
      console.log('globalscores loaded');
      displayHighScores('global');
    }
  }
  request.open("GET", "highScores.json", true);
  request.send();
}

function displayHighScores(scoreType) {
  if (scoreType == 'local') {
    loadLocalScores();
    var tempScores = localScores;
    var elementID = 'localScores';
    var title = "Local";
  } else {
    var tempScores = globalScores;
    var elementID = 'globalScores';
    var title = "Global";
  }

  var localDiv = document.getElementById(elementID);
  var tableString = "<table><tr><th colspan='4'>" + title + " High Scores</th></tr><tr><th>Rank</th><th>Player</th><th>Score</th><th>Date</th></tr>"

  for (var i = 0; i < tempScores.length; i++) {
    tableString += "<tr><td>" + (i + 1) + "</td><td>" + tempScores[i].name + "</td><td>" + tempScores[i].score + "</td><td>" + tempScores[i].date + "</td></tr>";
  }
  tableString += "</table > "

  localDiv.innerHTML = tableString;
}

function announceHighScore(parent) {
  var message = document.createElement('h2');
  var input = document.createElement("input");
  var button = document.createElement("button");
  message.innerText = 'High Score!';
  input.type = 'text';
  input.placeholder = 'Enter your name!';
  input.id = 'nameInput';
  button.addEventListener('click', function () { submitScore(button); });
  button.innerText = "Submit High Score";
  parent.appendChild(message);
  parent.appendChild(input);
  parent.appendChild(button);
}

function displayFinalScore() {
  var messageDiv = document.createElement("div");
  messageDiv.classList.add('messageDiv');
  var gameOverMessage = document.createElement("h2");
  var scoreMessage = document.createElement("h3");
  gameOverMessage.innerText = "Game Over!";
  scoreMessage.innerText = "Final Score: " + game.points;
  messageDiv.appendChild(gameOverMessage);
  messageDiv.appendChild(scoreMessage);

  if (isHighScore()) {
    announceHighScore(messageDiv);
  }

  messageDiv.style.display = 'flex';
  messageDiv.style.flexDirection = 'column';
  var canvas = document.getElementById('gameArena');
  canvas.parentNode.insertBefore(messageDiv, canvas);
}

function gameOverAnimation() {
  setTimeout(function () {
    //Let the animation run for 1 second before turning off.
    var canvas = document.getElementById('gameArena');
    canvas.classList.toggle('gameOver');
    canvas.style.width = '0px';
    canvas.style.height = '0px';
    displayFinalScore();
  }, 2000);
  document.getElementById('gameArena').classList.toggle('gameOver');
  document.getElementById('gameButton').innerText = "Start New Game";
}

function levelUpAnimation() {
  setTimeout(function () {
    //Let the animation run for 1 second before turning off.
    document.getElementById('gameArena').classList.toggle('levelUp');
    overrideColor = 'null';
  }, 1500);
  overrideColor = 'lightgreen'
  document.getElementById('gameArena').classList.toggle('levelUp');
}

function increaseLevel() {
  levelUpAnimation();
  game.level++;
  console.log("Level " + game.level + " Reached. ");
  clearInterval(gameTick);
  gameTick = setInterval(updateGameboard, (baseTick - (game.level * ((baseTick - 30) / 20))));
  document.getElementById('levelLabel').innerText = "Current Level: " + game.level;
}

function updateGameboard() {
  if (game != undefined && !game.isOver && !game.isPaused) {
    game.updateGame();
    if (game.level != 20 && game.points >= (game.level * game.level * 1000)) {
      increaseLevel();
    }
  }
}

function resizeGameBoard() {
  canvas = document.getElementById('gameArena');
  canvas.style.width = '90%';
  canvas.style.height = canvas.offsetWidth + "px";
  canvas.width = canvas.offsetWidth;
  canvas.height = canvas.offsetWidth;
  document.getElementById('arenaParent').style.minHeight = canvas.offsetHeight + 'px';
  //reset boardvariables.
  boardWidth = canvas.offsetWidth;
  blockWidth = boardWidth / blockNumber;
}


function loadGame() {
  if (game == undefined || game.isOver) {
    setTimeout(function () {
      game = new Game();
      clearInterval(gameTick);
      newGameAnimation();
      resizeGameBoard();
      gameTick = setInterval(updateGameboard, baseTick - (game.level * ((baseTick - 30) / 20)));  // Must be a number less than 20. )));
      document.getElementById('gameButton').innerText = "Pause Game";
      document.getElementById('levelLabel').innerText = "Current Level: 1";
    }, 200);
  } else if (game.isPaused) {
    game.isPaused = false;
    document.getElementById('gameButton').innerText = "Pause Game";
  } else {
    game.isPaused = true;
    document.getElementById('gameButton').innerText = "Resume Game";
  }
}

//This function gets the user keyboard input.
function getInput() {
  lastkey = event.keyCode;
  if (lastkey == 32) {
    game.points += 500;
    // document.getElementById('content').classList.toggle('levelUp');
    document.getElementById('gameArena').classList.toggle('gameOver');
  }
}

function setUp() {
  resizeGameBoard();
  contentBlocks = document.getElementsByClassName('contentBlock');
  contentElement = document.getElementById('content');
  for (var i = 0; i < contentBlocks.length; i++) {
    contentBlocks[i].style.minHeight = contentBlocks[i].offsetWidth + "px";
  }
  displayHighScores('local');
  loadGlobalScores();
}

function downloadScores() {
  if (document.getElementById('swapScoreCard').innerText == 'View Global Scores') {
    var tempScores = localScores;
    var filename = 'localScores';
  } else {
    var tempScores = globalScores;
    var filename = 'globalScores';
  }
  var download = document.createElement('a');
  if (document.getElementById('downloadJSON').checked) {
    download.setAttribute('href', 'data:text/plain;charset=utf-8,' + encodeURIComponent(JSON.stringify(tempScores)));
    filename += '.json';
  } else {
    var text = '';
    for (var i = 0; i < tempScores.length; i++) {
      text += "Name:" + tempScores[i].name + ", Score:" + tempScores[i].score + ", Date:" + tempScores[i].date + "\r\n";
    }
    download.setAttribute('href', 'data:text/plain;charset=utf-8,' + encodeURIComponent(text));
    filename += '.txt';
  }

  download.setAttribute('download', filename);

  download.style.display = 'none';
  document.body.appendChild(download);

  download.click();

  document.body.removeChild(download);
}

function swapScoreCard(button) {
  if (button.innerText == 'View Global Scores') {
    var visible = document.getElementById("localScores");
    var invisible = document.getElementById("globalScores");
    var transformModifier = '';
  } else {
    var visible = document.getElementById("globalScores");
    var invisible = document.getElementById("localScores");
    var transformModifier = '-';
  }
  visible.classList.add('oneSecTransition');
  invisible.classList.add('oneSecTransition');
  visible.style.transform = 'rotateY(' + transformModifier + '90deg)';
  if (button.innerText == 'View Global Scores') {
    visible.addEventListener("transitionend", showGlobalScores);
    button.innerText = 'View Local Scores';
  } else {
    visible.addEventListener("transitionend", showLocalScores);
    button.innerText = 'View Global Scores';
  }
}

function showGlobalScores() {
  var global = document.getElementById("globalScores");
  var local = document.getElementById("localScores");
  local.style.display = 'none';
  global.style.display = 'block';
  //A small timeout needed to allow the transistion to propogate effectively.
  setTimeout(function () {
    global.style.transform = 'rotateY(0deg)';
    local.removeEventListener('transitionend', showGlobalScores);
  }, 1);
}

function showLocalScores() {
  var global = document.getElementById("globalScores");
  var local = document.getElementById("localScores");
  global.style.display = 'none';
  local.style.display = 'block';
  setTimeout(function () {
    local.style.transform = 'rotateY(0deg)';
    global.removeEventListener('transitionend', showLocalScores);
  }, 1);
}

function changeThumbnail(image, hover) {
  if (hover) {
    image.setAttribute('src', 'thumbnail_hoverlarge.png');
  } else {
    image.setAttribute('src', 'thumbnail.png');
  }
}

function rickRoll(videoArea) {
  var height = videoArea.clientHeight - 16;
  var width = videoArea.clientWidth - 16;
  console.log(height);
  console.log(width);
  //<iframe width="789" height="444" src="https://www.youtube.com/embed/dQw4w9WgXcQ" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
  videoArea.innerHTML = "<iframe width='" + width + "' height='" + height + "' id='rickRoll' src='https://www.youtube.com/embed/dQw4w9WgXcQ?start=19&autoplay=1' ></iframe >";
}

window.addEventListener("keydown", function (e) {
  // space and arrow keys
  if ([32, 37, 38, 39, 40].indexOf(e.keyCode) > -1) {
    e.preventDefault();
  }
}, false);

function spinColors() {
  mainColor = Math.floor(Math.random() * 4);
  secondaryColor = Math.floor(Math.random() * 4);
  if (Math.random() > .5) {
    isDarkMode = true;
  } else {
    isDarkMode = false;
  }
  shaderVal = Math.floor(Math.random() * 2);
  updateColors();
  setGameColors();
}

function setGameColors() {
  if (!isDarkMode) {
    playerColor = COLORS[secondaryColor].lightest;
    pelletColor = COLORS[mainColor].lightest;
  } else {
    playerColor = COLORS[secondaryColor].darkest;
    pelletColor = COLORS[mainColor].darkest;
  }
}

function updateColors() {
  bodyCard = document.getElementsByClassName('bodyCard');
  scoreCard = document.getElementsByClassName('scoreCard');
  contentCard = document.getElementsByClassName('contentCard');
  headerCard = document.getElementsByClassName('headerCard');
  columnCard = document.getElementsByClassName('columnCard');
  gameCard = document.getElementsByClassName('gameCard');
  buttons = document.getElementsByClassName('btn');
  console.log(scoreCard);
  updateBodyCards(bodyCard);
  updateSectionCards(headerCard);
  updateSectionCards(columnCard);
  updateScoreCard(scoreCard);

  updateGameCard(gameCard);
  updateContentCard(contentCard);
  updateButtons(buttons);
}

function updateButtons(buttons) {
  for (var i = 0; i < buttons.length; i++) {
    btnHover(buttons[i], false);
  }
}

function btnHover(button, isHovering) {
  if ((isDarkMode && !isHovering) || (!isDarkMode && isHovering)) {
    button.style.backgroundColor = COLORS[mainColor].lightest;
    button.style.color = COLORS[secondaryColor].darkest;
    button.style.borderColor = COLORS[mainColor].darkest;
  } else{
    button.style.backgroundColor = COLORS[mainColor].darkest;
    button.style.color = COLORS[secondaryColor].lightest;
    button.style.borderColor = COLORS[mainColor].lightest;
  }
}

function updateGameCard(cards) {
  for (var i = 0; i < cards.length; i++) {
    if (isDarkMode) {
      cards[i].style.backgroundColor = COLORS[secondaryColor].darkest;
    } else {
      cards[i].style.backgroundColor = COLORS[secondaryColor].lightest;
    }
  }
}

function updateScoreCard(cards) {
  for (var i = 0; i < cards.length; i++) {
    if (isDarkMode) {
      cards[i].style.backgroundColor = COLORS[mainColor].darkest;
      cards[i].style.borderColor = COLORS[secondaryColor].lightest;
    } else {
      cards[i].style.backgroundColor = COLORS[mainColor].lightest;
      cards[i].style.borderColor = COLORS[secondaryColor].darkest;
    }
  }
}

function updateContentCard(cards) {
  for (var i = 0; i < cards.length; i++) {
    if (isDarkMode) {
      if (shaderVal = 0) //Darker
        cards[i].style.backgroundColor = COLORS[secondaryColor].darkest;
      else {
        cards[i].style.backgroundColor = COLORS[secondaryColor].darker;
      }
    } else {
      if (shaderVal = 0) //Darker
        cards[i].style.backgroundColor = COLORS[secondaryColor].dark;
      else {
        cards[i].style.backgroundColor = COLORS[secondaryColor].normal;
      }
    }
  }
}

function updateSectionCards(cards) {
  for (var i = 0; i < cards.length; i++) {
    if (isDarkMode) {
      if (shaderVal = 0) //Darker
        cards[i].style.backgroundColor = COLORS[mainColor].darker;
      else {
        cards[i].style.backgroundColor = COLORS[mainColor].dark;
      }
      cards[i].style.borderColor = COLORS[secondaryColor].lightest;
    } else {
      if (shaderVal = 0) //Darker
        cards[i].style.backgroundColor = COLORS[mainColor].lighter;
      else {
        cards[i].style.backgroundColor = COLORS[mainColor].light;
      }
      cards[i].style.borderColor = COLORS[secondaryColor].darkest;
    }
  }
}

function updateBodyCards(cards) {
  for (var i = 0; i < cards.length; i++) {
    if (isDarkMode) {
      if (shaderVal = 0) //Darker
        cards[i].style.backgroundColor = COLORS[mainColor].dark;
      else {
        cards[i].style.backgroundColor = COLORS[mainColor].normal;
      }
      cards[i].style.color = COLORS[secondaryColor].lightest;
    } else {
      if (shaderVal = 0) //Darker
        cards[i].style.backgroundColor = COLORS[mainColor].light;
      else {
        cards[i].style.backgroundColor = COLORS[mainColor].normal;
      }
      cards[i].style.color = COLORS[secondaryColor].darkest;
    }
  }
}
