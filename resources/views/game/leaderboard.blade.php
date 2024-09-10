<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CatAttack</title>
</head>
<body>
    <style>
        body, html {
            margin: 0;
            padding: 0;
            background-color: #1a1a2e;
        }

        section{
            
            height: 100%;
            width: 100%;
            display: flex;
        flex-direction: column;
        align-items: center;
        }
        table {
            color:white;
            font-family: arial, sans-serif;
            border-collapse: collapse;
            width: 90%;
        }

td, th {
  border: 3px solid #dddddd;
  color:white;
  text-align: center;
  padding: 8px;
}
.title {
        font-size: 60px;
        margin-bottom: 40px;
        text-shadow: 3px 3px #0f3460;
        letter-spacing: 2px;
        color: #e94560;
        font-family: 'Arial', sans-serif;
        text-align: center;
    }
    .play-button {
        font-family: 'Arial', sans-serif;
        padding: 10px 20px;
        margin: 15px;
        display: block;
        background-color: #4caf50;
        border-radius: 50px;
        cursor: pointer;
        transition: all 0.3s ease;
        width: 80%;
        max-width: 300px;
        font-weight: bold;
        text-transform: uppercase;
        text-align: center;
    }
    .play-button:hover {
        background-color: #155e24;
        transform: scale(1.05);
        box-shadow: 0 0 15px rgba(233, 69, 96, 0.5);
    }

    a{
        text-decoration: none;
        color: #ffffff;
    }

    </style>

    <a href="{{url('/')}}" class="play-button">Retour à l'accueil</a>

    <section>
        <h1 class="title">Leaderboard</h1>
        <table>
            <tr>
                <th>#</th>
                <th>Nom</th>
                <th>Score</th>
            </tr>
    
            @forEach($board as $key => $score)
            <tr>
                <td>
                    @if($key === 0)
                    🥇​
                    @elseif($key== 1 )
                    🥈​
                    @elseif($key ==2 )
                    🥉​
                    @else
                    {{$key + 1}}
                    @endif
                </td>
                <td>{{$score->name}}</td>
                <td>{{$score->score}}</td>
            </tr>
            @endForEach
        
        </table>
    </section>
</body>
</html>