@extends('layouts.app')
@section('title', 'Listado de Jugadores')

@section('content')
<div>
    <h2>Listado de jugadores Femeninos</h2>
    <x-players-table :players="$femalePlayers" :genderId="$genderOptions['FEMALE']" />

    <h2>Listado de jugadores Masculinos</h2>
    <x-players-table :players="$malePlayers" :genderId="$genderOptions['MALE']" />
</div>
@endSection