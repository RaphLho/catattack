<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Choix des Niveaux - CatAttack</title>
    <link rel="stylesheet" href="{{ asset('css/global.css') }}">
    <link rel="stylesheet" href="{{ asset('css/select.css') }}">   
</head>

<body>
    <a href="{{ url('/') }}" class="play-button home-button">Retour a l'accueil</a>
    <div class="container">
        <h1>Choix des Niveaux</h1>
        <div class="levels-grid">
            @for ($i = 1; $i <= 8; $i++)
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
            // Ici, vous pouvez ajouter la logique pour demarrer le niveau selectionne
            // Par exemple, rediriger vers une URL specifique au niveau
            window.location.href = `/${level}`;
        }
    </script>
</body>

</html>

@php
    function getDifficultyClass($level)
    {
        if ($level <= 3) {
            return 'easy';
        }   
        if ($level <= 6) {
            return 'medium';
        }
        return 'hard';
    }

    function getDifficultyText($level)
    {
        if ($level <= 3) {
            return 'Facile';
        }
        if ($level <= 6) {
            return 'Moyen';
        }
        return 'Difficile';
    }
@endphp
