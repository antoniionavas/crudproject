@extends('adminlte::page')

@section('content_header')
    <h1>Lista de Proyectos</h1>
@stop


@section('content')
    @livewire('projects-index')
@endsection

@section('footer')
    <x-footer>
    </x-footer>
@stop
