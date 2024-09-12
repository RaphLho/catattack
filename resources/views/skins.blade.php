<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Choix de Skins - CatAttack</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #1a1a2e;
            color: #e94560;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .container {
            background-color: #16213e;
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0 0 20px rgba(233, 69, 96, 0.3);
            text-align: center;
            max-width: 80%;
            width: 400px;
        }

        h1 {
            font-size: 24px;
            margin-bottom: 20px;
            text-shadow: 1px 1px #0f3460;
        }

        .skins-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
            margin-bottom: 20px;
        }

        .skin-option {
            background-color: #0f3460;
            border-radius: 8px;
            padding: 10px;
            cursor: pointer;
            transition: transform 0.2s ease;
        }

        .skin-option:hover {
            transform: scale(1.03);
        }

        .skin-option img {
            width: 100%;
            height: auto;
            border-radius: 4px;
        }

        .skin-option p {
            margin-top: 5px;
            font-weight: bold;
            font-size: 14px;
        }

        .selected {
            border: 2px solid #e94560;
        }

        .button {
            background-color: #e94560;
            color: #ffffff;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            border-radius: 25px;
            cursor: pointer;
            transition: background-color 0.2s ease;
        }

        .button:hover {
            background-color: #c81e3f;
        }

        .play-button {
            font-family: 'Arial', sans-serif;
            padding: 8px 16px;
            margin: 10px;
            display: inline-block;
            background-color: #4caf50;
            border-radius: 25px;
            cursor: pointer;
            transition: all 0.2s ease;
            font-weight: bold;
            text-transform: uppercase;
            text-align: center;
            color: #ffffff;
            text-decoration: none;
            font-size: 14px;
        }

        .play-button:hover {
            background-color: #155e24;
            transform: scale(1.03);
            box-shadow: 0 0 10px rgba(233, 69, 96, 0.5);
        }
    </style>
</head>

<body>
    <div class="container">
        <a href="{{ url('/') }}" class="play-button home-button">Retour à l'accueil</a>
        <h1>Choisissez votre Skin</h1>
        <div class="skins-grid">
            <div class="skin-option" onclick="selectSkin(this, 'chat-normal')">
                <img src="https://s3-us-west-2.amazonaws.com/mb.images/vinafrog/listing/VFSIL0095.jpg"
                    alt="Chat Normal">
                <p>Chat Normal</p>
            </div>
            <div class="skin-option" onclick="selectSkin(this, 'chat-ninja')">
                <img src="https://www.creativefabrica.com/wp-content/uploads/2022/12/25/Ninja-Cat-Portrait-Steampunk-Style-54365317-1.png"
                    alt="Chat Ninja">
                <p>Chat Ninja</p>
            </div>
            <div class="skin-option" onclick="selectSkin(this, 'chat-spatial')">
                <img src="https://www.sciencesetavenir.fr/assets/img/2016/03/31/cover-r4x3w1200-57dfbf2666447-space-chat.jpg"
                    alt="Chat Spatial">
                <p>Chat Spatial</p>
            </div>
        </div>
        <button class="button" onclick="saveSkinChoice()">Confirmer</button>
    </div>

    <script>
        let selectedSkin = '';

        function selectSkin(element, skinName) {
            document.querySelectorAll('.skin-option').forEach(option => {
                option.classList.remove('selected');
            });
            element.classList.add('selected');
            selectedSkin = skinName;
        }

        function saveSkinChoice() {
            if (selectedSkin) {
                localStorage.setItem('selectedSkin', selectedSkin);
                window.location.href = '{{ url('/') }}';
            }
        }

        window.onload = function() {
            const savedSkin = localStorage.getItem('selectedSkin');
            if (savedSkin) {
                const skinElement = document.querySelector(`.skin-option[onclick="selectSkin(this, '${savedSkin}')"]`);
                if (skinElement) {
                    selectSkin(skinElement, savedSkin);
                }
            }
        }
    </script>
</body>

</html>
