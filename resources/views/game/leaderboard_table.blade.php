
<table>
    <tr>
        <th colspan="3">
            <span class="level" >
                <button  onclick="previousLevel({{$currentLevel}})" @class([
                    'nav-button',
                    'disabled' => $currentLevel==0,
                ])><</button> 
                Niveau #{{$currentLevel}}
                <button onclick="nextLevel({{$currentLevel}})" @class([
                    'nav-button',
                    'disabled' => $currentLevel==$length-1,
                ])>></button>
            </span>
        </th>
    </tr>
    <tr>
        <th>#</th>
        <th>Nom</th>
        <th>Score</th>
    </tr>
    @forEach($board[$currentLevel] as $rank => $score)
    <tr>
        <td>
            @if($rank === 0)
            🥇​
            @elseif($rank == 1)
            🥈​
            @elseif($rank == 2)
            🥉​
            @else
            {{$rank + 1}}
            @endif
        </td>
        <td @class([
            'gold' => $rank==0,
            'silver' => $rank==1,
            'bronze' => $rank==2,
        ])>{{$score->name}}</td>
        <td @class([
            'gold' => $rank==0,
            'silver' => $rank==1,
            'bronze' => $rank==2,
        ])>{{$score->score}}</td>
    </tr>
    @endForEach
</table>

<script>
    function previousLevel(currentLevel){
        currentLevel =windows.previousLevel(currentLevel)
    }

    function nextLevel(currentLevel){
        currentLevel = windows.nextLevel(currentLevel)
    }
    
</script>