<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CatAttack</title>
    <link rel="stylesheet" href="{{ asset('css/global.css') }}">
    <link rel="stylesheet" href="{{ asset('css/table.css') }}">
</head>
<body>

    <a href="{{url('/')}}" class="play-button">Retour a l'accueil</a>
    

    <section>
        <h1 class="title">Leaderboard</h1>
        <section id="leaderboard-table">
            @include('game.leaderboard_table', ['board' => $board, 'currentLevel' => $currentLevel])
        </section>

    </section>
    
    <script>
        let currentLevel = {{$currentLevel}};
        let board={{$length}}

        function loadLeaderboard(level) {
        fetch(`/game/leaderboard/${level}`)
            .then(response => response.text()) 
            .then(html => {
                document.getElementById('leaderboard-table').innerHTML = html;  
            })
            .catch(error => console.log('Erreur lors du chargement du tableau:', error));
    }

    function previousLevel(currentLevel){
            if (currentLevel !== 0){
                currentLevel--
                loadLeaderboard(currentLevel)
            }
            return currentLevel
        }
        function nextLevel(currentLevel){
            if (currentLevel !== board-1){
                currentLevel++
                loadLeaderboard(currentLevel)
            }
            return currentLevel
        }
        
    </script>
</body>
</html>