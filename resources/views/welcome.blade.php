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
        padding: 40px;
        border-radius: 20px;
        box-shadow: 0 0 30px rgba(233, 69, 96, 0.3);
        width: 80%;
        max-width: 600px;
    }
    .game-title {
        font-size: 60px;
        margin-bottom: 40px;
        text-shadow: 3px 3px #0f3460;
        letter-spacing: 2px;
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
        padding: 20px 40px;
        margin: 15px;
        font-size: 28px;
        border-radius: 50px;
        cursor: pointer;
        transition: all 0.3s ease;
        width: 80%;
        max-width: 300px;
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
        box-shadow: 0 0 15px rgba(233, 69, 96, 0.5);
    }
    
    .levels-button {
        background-color: #ff0000;
    }
    .levels-button:hover {
        background-color: rgb(146, 3, 3);
        color: #ffffff;
        transform: scale(1.05);
        box-shadow: 0 0 15px rgba(233, 69, 96, 0.5);
    }
    .settings-button {
        background-color: #ffa500;
    }
    .settings-button:hover {
        background-color: #8f5e03;
        color: #ffffff;
        transform: scale(1.05);
        box-shadow: 0 0 15px rgba(233, 69, 96, 0.5);
    }
    .exit-button {
        background-color: #353434;
    }
    .exit-button:hover {
        background-color: #000000;
        color: #ffffff;
        transform: scale(1.05);
        box-shadow: 0 0 15px rgba(233, 69, 96, 0.5);
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
        box-shadow: 0 0 15px rgba(233, 69, 96, 0.5);
    }
    @keyframes rainbow {
        0% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
        100% { background-position: 0% 50%; }
    }
    
    .cat-icon {
        font-size: 48px;
        margin: 0 15px;
        vertical-align: middle;
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
        <a href="{{ url('/levels/SelectLevels') }}" class="menu-button levels-button">Niveaux</a>
        <a href="{{ url('/skins') }}" class="menu-button skins-button">Skins</a>
        <button class="menu-button settings-button">Paramètres</button>
        <a href="{{ url('/game/leaderboard') }}" class="menu-button settings-button">Leaderboard</a>
        <button class="menu-button exit-button" onclick="window.location.href='https://www.google.com'">Quitter</button>
    </div>
</div>