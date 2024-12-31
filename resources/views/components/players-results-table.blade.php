<table class="table table-light table-striped">

    <thead>
        <tr>
            <th scope="col">ID</th>
            <th scope="col">Nombre</th>
            <th scope="col">Rondas</th>
            <th scope="col">Resultado</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($players as $player)

        <tr class="{{ $player->pivot->is_winner ? 'table-success' : '' }}">

            <td>{{ $player->id }}</td>
            <td>{{ $player->name }}</td>
            <td>{{ $player->pivot->last_round  }}</td>
            <td> {{ $player->pivot->is_winner ? 'Ganador' : 'Derrotado por el jugador Nª ' . $player->pivot->last_opponent_id }}</td>

        </tr>
        @endforeach
    </tbody>
</table>