<!-- $players -->
<!-- $gender_id -->
<!-- $genderOptions -->

@if($gender_id === $genderOptions['FEMALE'])
<table class="table table-warning table table-bordered">
    @else
    <table class="table table-primary table table-bordered">
        @endif

        <thead>
            <tr>
                <th scope="col">ID</th>
                <th scope="col">Nombre</th>
                <th scope="col">Habilidad (%)</th>

                @if($gender_id === $genderOptions['FEMALE'])
                <th scope="col">Tiempo de reacción (ms)</th>
                @endif

                @if($gender_id === $genderOptions['MALE'])
                <th scope="col">Fuerza (N)</th>
                <th scope="col">Velocidad de desplazamiento (m/s)</th>
                @endif

            </tr>
        </thead>
        <tbody>
            @foreach ($players as $player)
            <tr>
                <td>{{ $player->id }}</td>
                <td>{{ $player->name }}</td>
                <td>{{ $player->skill_level }} (%)</td>

                @if($gender_id === $genderOptions['FEMALE'])
                <td>{{ $player->femalePlayer->reaction_time }} (ms)</td>
                @endif

                @if($gender_id === $genderOptions['MALE'])
                <td>{{ $player->malePlayer->stength }} (N)</td>
                <td>{{ $player->malePlayer->movement_speed }} (m/s)</td>
                @endif

            </tr>
            @endforeach
        </tbody>
    </table>