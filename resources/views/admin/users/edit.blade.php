@extends('adminlte::page')

@section('content_header')
    <h1>Editar Usuario</h1>
@stop

@section('content')
    @livewire('users-edit')
@stop

@section('footer')
    <x-footer>
    </x-footer>
@stop