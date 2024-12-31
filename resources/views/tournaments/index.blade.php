@extends('layouts.app')
@section('title', 'Torneos')

@section('content')
<div>
    <h2>Listado de Torneos</h2>
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
            @foreach ($tournaments as $tournament)
            <tr>
                <td>{{ $tournament->id }}</td>
                <td>{{ $tournament->gender->title}}</td>
                <td>{{ $tournament->players->count()}}</td>
                <td>{{ $tournament->status->title}}</td>
                <td>
                    <a href="{{ route('tournament.show', ['id' => $tournament->id]) }}" class="btn btn-primary w-auto">Detalle</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

@endSection