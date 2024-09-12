<style>
    body, html {
        margin: 0;
        padding: 0;
        height: 100%;
        width: 100%;
        display: flex;
        justify-content: center;
        align-items: center;
        background-color: #1a1a2e;
    }
    .game-menu {
        background-color: #16213e;
        color: #e94560;
        font-family: 'Arial', sans-serif;
        text-align: center;
        padding: 30px;
        border-radius: 15px;
        box-shadow: 0 0 20px rgba(233, 69, 96, 0.3);
        width: 70%;
        max-width: 500px;
    }
    .game-title {
        font-size: 50px;
        margin-bottom: 30px;
        text-shadow: 2px 2px #0f3460;
        letter-spacing: 1px;
    }
    .menu-buttons {
        display: flex;
        flex-direction: column;
        align-items: center;
    }
    .menu-button {
        background-color: #0f3460;
        color: #ffffff;
        border: none;
        padding: 15px 30px;
        margin: 10px;
        font-size: 22px;
        border-radius: 40px;
        cursor: pointer;
        transition: all 0.3s ease;
        width: 70%;
        max-width: 250px;
        font-weight: bold;
        text-transform: uppercase;
    }
    .play-button {
        background-color: #4caf50;
    }
    .play-button:hover {
        background-color: #155e24;
        color: #ffffff;
        transform: scale(1.05);
        box-shadow: 0 0 10px rgba(233, 69, 96, 0.5);
    }
    
    .levels-button {
        background-color: #ff0000;
    }
    .levels-button:hover {
        background-color: rgb(146, 3, 3);
        color: #ffffff;
        transform: scale(1.05);
        box-shadow: 0 0 10px rgba(233, 69, 96, 0.5);
    }
    .settings-button {
        background-color: #ffa500;
    }
    .settings-button:hover {
        background-color: #8f5e03;
        color: #ffffff;
        transform: scale(1.05);
        box-shadow: 0 0 10px rgba(233, 69, 96, 0.5);
    }
    .exit-button {
        background-color: #353434;
    }
    .exit-button:hover {
        background-color: #000000;
        color: #ffffff;
        transform: scale(1.05);
        box-shadow: 0 0 10px rgba(233, 69, 96, 0.5);
    }

    .skins-button {
        background: linear-gradient(45deg, #ff0000, #ff7f00, #ffff00, #00ff00, #0000ff, #8b00ff);
        background-size: 600% 600%;
        animation: rainbow 6s ease infinite;
    }
    .skins-button:hover {
        background: linear-gradient(45deg, #ff0000, #ff7f00, #ffff00, #00ff00, #0000ff, #8b00ff);
        background-size: 600% 600%;
        animation: rainbow 3s ease infinite;
        color: #ffffff;
        transform: scale(1.05);
        box-shadow: 0 0 10px rgba(233, 69, 96, 0.5);
    }
    @keyframes rainbow {
        0% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
        100% { background-position: 0% 50%; }
    }
    
    .cat-icon {
        font-size: 40px;
        margin: 0 10px;
        vertical-align: middle;
    }

    form{
        display: flex;
        flex-direction: column;
        align-items: center;
        font-size: 22px;
    }
    input{
        border-radius: 40px;
        max-width : 250px;
        padding: 8px 30px;
        margin: 10px;
        font-size: 22px;
    }
    a{
        text-decoration: none;
        font-family: 'Arial', sans-serif;    
    }
    #victory {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: #16213e;
            box-shadow: 0 0 20px rgba(233, 69, 96, 0.3);
            color: white;
            padding: 40px;
            text-align: center;
            display: none;
            width: 80%;
            max-width: 600px;
        }
    label{        
        color: #e94560;
    }
</style>

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
    <a href='/' id="restartVictory" class="menu-button">Retour à l'accueil</a>   
</div>
<script>
        const name = document.getElementById('victory');
    function enterName(){
        name.style.display = "block"
    }
    
function validateForm() {
  let x = document.forms["PlayersName"]["player_name"].value;
  if (x == "") {
    alert("Veuillez remplir un nom");
    return false;
  } else {  
    localStorage.setItem("name", x);   

  }
}
</script>