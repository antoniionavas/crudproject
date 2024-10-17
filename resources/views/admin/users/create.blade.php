@extends('adminlte::page')

@section('content_header')
    <h1>Crear Usuario</h1>
@stop

@section('content')
    @livewire('users-create')
@stop

@section('footer')
    <x-footer>
    </x-footer>
@stop