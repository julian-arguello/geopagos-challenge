@extends('layouts.app')

@section('title', 'Detalles del Torneo')

@section('content')
<div>
    <h2>Detalles del Torneo</h2>

    <table class="table table-light table-bordered">
        <thead>
            <tr>
                <th scope="col">ID</th>
                <th scope="col">Genero</th>
                <th scope="col">Inscriptos</th>
                <th scope="col">Estado</th>
                <th scope="col">Acciones</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ $tournament->id }}</td>
                <td>{{ $tournament->gender->title}}</td>
                <td>{{ $tournament->players->count()}}</td>
                <td>{{ $tournament->status->title}}</td>
                <td>
                    <form action="{{ route('tournament.play') }}" method="POST">
                        @csrf
                        <input type="hidden" name="tournament_id" value="{{ $tournament->id }}" />
                        <button type="submit" class="btn btn-primary w-auto">Jugar Torneo</button>
                    </form>
                </td>
            </tr>
        </tbody>
    </table>

    <h3>Jugadores Inscriptos</h3>

    <x-players-table :players="$tournament->players" :genderId="$tournament->gender->id" />

</div>
@endsection