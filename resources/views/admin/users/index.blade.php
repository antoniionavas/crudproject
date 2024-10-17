@extends('adminlte::page')

@section('content_header')
    <h1>Lista de Usuarios</h1>
@stop

@section('content')
    @livewire('users-index')
@stop

@section('footer')
    <x-footer>
    </x-footer>
@stop