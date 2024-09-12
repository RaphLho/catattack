
<head>
    <link rel="stylesheet" href="{{ asset('css/global.css') }}">
    <link rel="stylesheet" href="{{ asset('css/welcome.css') }}">
</head>
<div class="game-menu">
    <h1 class="game-title">
        <span class="cat-icon">🐱</span>
        CatAttack
        <span class="cat-icon">😼</span>
    </h1>
    <div class="menu-buttons">
        <a href="{{ url('/game/level') }}" class="menu-button play-button">Jouer</a>
        <button onclick="enterName()"  class="menu-button levels-button">Niveaux</button>
        <a href="{{ url('/skins') }}" class="menu-button skins-button">Skins</a>
        <a href="{{ url('/game/leaderboard') }}" class="menu-button settings-button">Leaderboard</a>
        <button class="menu-button exit-button" onclick="window.location.href='https://www.google.com'">Quitter</button>
    </div>
</div>

<div id="victory">
    <form name="PlayersName"  onsubmit="return validateForm()" action="levels/SelectLevels">
        <label>Entrez votre nom</label>
        <input name="player_name" type="text">
        <input type="submit"  id="quitVictory" class="menu-button" value="Enregistrer">
    </form>
    <a href='/' id="restartVictory" class="menu-button">Retour a l'accueil</a>   
</div>
<script>
        const name = document.getElementById('victory');
    function enterName(){
        name.style.display = "block"
    }
    
function validateForm() {
  let x = document.forms["PlayersName"]["player_name"].value;
  if (x == "") {
    alert("Veuillez 0pxplir un nom");
    return false;
  } else {  
    localStorage.setItem("name", x);   

  }
}
</script>