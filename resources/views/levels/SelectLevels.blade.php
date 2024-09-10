<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Choix des Niveaux - CatAttack</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #1a1a2e;
            color: #e94560;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
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
        .levels-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
            margin-bottom: 30px;
        }
        .level-option {
            background-color: #0f3460;
            border-radius: 10px;
            padding: 20px;
            cursor: pointer;
            transition: transform 0.3s ease;
        }
        .level-option:hover {
            transform: scale(1.05);
        }
        .level-number {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .difficulty {
            font-size: 18px;
        }
        .easy { color: #4CAF50; }
        .medium { color: #FFC107; }
        .hard { color: #F44336; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Choix des Niveaux</h1>
        <div class="levels-grid">
            @for ($i = 1; $i <= 10; $i++)
                <div class="level-option" onclick="selectLevel({{ $i }})">
                    <div class="level-number">Niveau {{ $i }}</div>
                    <div class="difficulty {{ getDifficultyClass($i) }}">
                        {{ getDifficultyText($i) }}
                    </div>
                </div>
            @endfor
        </div>
    </div>

    <script>
        function selectLevel(level) {
            // Ici, vous pouvez ajouter la logique pour démarrer le niveau sélectionné
            // Par exemple, rediriger vers une URL spécifique au niveau
            window.location.href = `/${level}`;
        }
    </script>
</body>
</html>

@php
function getDifficultyClass($level) {
    if ($level <= 3) return 'easy';
    if ($level <= 7) return 'medium';
    return 'hard';
}

function getDifficultyText($level) {
    if ($level <= 3) return 'Facile';
    if ($level <= 7) return 'Moyen';
    return 'Difficile';
}
@endphp
