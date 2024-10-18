@extends('adminlte::page')

@section('content_header')
    <h1>Editar Usuario</h1>
@stop

@section('content')
<div>


    
@section('content')
<div>
    @if (session()->has('message'))
        <div class="alert alert-success">
            {{ session('message') }}
        </div>
    @endif

    <div class="card">
        <div class="card-body">           
            <form action="{{ route('usuarios.update', $user->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label for="name">Nombre</label>
                    <input type="text" name="name" class="form-control name" placeholder="Ingrese el nombre del usuario" value="{{ old('name', $user->name) }}">

                    @error('name')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="email">Correo Electrónico</label>
                    <input type="email" name="email" id="email" class="form-control" placeholder="Ingrese el correo electrónico" value="{{ old('name', $user->email) }}">

                    @error('email')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group form-check">
                    <input type="checkbox" class="form-check-input" name="is_admin" id="is_admin" 
                        {{ old('is_admin', $user->is_admin) ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_admin">Administrador</label>
                </div>

                <button type="submit" class="btn btn-primary">Actualizar Usuario</button>
            </form>
        </div>
    </div>
</div>
@stop

    <div class="card">
        <div class="card-body">           
            <form action="{{ route('usuarios.update', $user->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label for="name">Nombre</label>
                    <input type="text" name="name" class="form-control name" placeholder="Ingrese el nombre del usuario" value="{{ old('name', $user->name) }}">

                    @error('name')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="email">Correo Electrónico</label>
                    <input type="email" name="email" id="email" class="form-control" placeholder="Ingrese el correo electrónico" value="{{ old('name', $user->email) }}">

                    @error('email')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group form-check">
                    <input type="checkbox" class="form-check-input" name="is_admin" id="is_admin" 
                        {{ old('is_admin', $user->is_admin) ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_admin">Administrador</label>
                </div>

                <button type="submit" class="btn btn-primary">Actualizar Usuario</button>
            </form>
        </div>
    </div>
</div>
@stop

@section('footer')
    <x-footer>
    </x-footer>
@stop