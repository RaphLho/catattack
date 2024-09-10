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
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 0 30px rgba(233, 69, 96, 0.3);
            text-align: center;
        }
        h1 {
            font-size: 36px;
            margin-bottom: 30px;
            text-shadow: 2px 2px #0f3460;
        }
        .skins-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            margin-bottom: 30px;
        }
        .skin-option {
            background-color: #0f3460;
            border-radius: 10px;
            padding: 20px;
            cursor: pointer;
            transition: transform 0.3s ease;
        }
        .skin-option:hover {
            transform: scale(1.05);
        }
        .skin-option img {
            width: 100%;
            height: auto;
            border-radius: 5px;
        }
        .skin-option p {
            margin-top: 10px;
            font-weight: bold;
        }
        .selected {
            border: 3px solid #e94560;
        }
        .button {
            background-color: #e94560;
            color: #ffffff;
            border: none;
            padding: 15px 30px;
            font-size: 18px;
            border-radius: 50px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .button:hover {
            background-color: #c81e3f;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Choisissez votre Skin</h1>
        <div class="skins-grid">
            <div class="skin-option" onclick="selectSkin(this, 'chat-normal')">
                <img src="https://s3-us-west-2.amazonaws.com/mb.images/vinafrog/listing/VFSIL0095.jpg" alt="Chat Normal">
                <p>Chat Normal</p>
            </div>
            <div class="skin-option" onclick="selectSkin(this, 'chat-ninja')">
                <img src="https://www.creativefabrica.com/wp-content/uploads/2022/12/25/Ninja-Cat-Portrait-Steampunk-Style-54365317-1.png" alt="Chat Ninja">
                <p>Chat Ninja</p>
            </div>
            <div class="skin-option" onclick="selectSkin(this, 'chat-spatial')">
                <img src="https://www.sciencesetavenir.fr/assets/img/2016/03/31/cover-r4x3w1200-57dfbf2666447-space-chat.jpg" alt="Chat Spatial">
                <p>Chat Spatial</p>
            </div>
        </div>
        <button class="button" onclick="saveSkinChoice()">Confirmer le choix</button>
    </div>

    <script>
        let selectedSkin = '';

        function selectSkin(element, skinName) {
            // Remove 'selected' class from all skin options
            document.querySelectorAll('.skin-option').forEach(option => {
                option.classList.remove('selected');
            });

            // Add 'selected' class to the clicked skin option
            element.classList.add('selected');
            selectedSkin = skinName;
        }

        function saveSkinChoice() {
            if (selectedSkin) {
                // Save the selected skin to localStorage
                localStorage.setItem('selectedSkin', selectedSkin);
                // Redirect to the game or main menu
                window.location.href = '{{ url("/") }}';
            }
        }

        // Load previously selected skin on page load
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
