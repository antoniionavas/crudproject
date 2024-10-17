<div>   
    @if (session()->has('message'))
        <div class="alert alert-success">
            {{ session('message') }}
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <form action="{{ route('usuarios.store') }}" method="POST">
                @csrf 
                    <div class="form-group">
                    <label for="name">Nombre</label>
                    <input type="text" name="name" class="form-control name" placeholder="Ingrese el nombre del usuario" 
                           value="{{ old('name', $user->name) }}">
        
                    @error('name')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
        
                <div class="form-group">
                    <label for="email">Correo Electrónico</label>
                    <input type="email" name="email" id="email" class="form-control" placeholder="Ingrese el correo electrónico"
                           value="{{ old('email', $user->email) }}" readonly>
        
                    @error('email')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
        
                <button type="submit" class="btn btn-primary">Actualizar Usuario</button>
            </form>
        </div>        
    </div>
</div>
