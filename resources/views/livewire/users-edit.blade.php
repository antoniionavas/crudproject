<div>
    @if (session()->has('message'))
        <div class="alert alert-success">
            {{ session('message') }}
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <form action="{{ route('usuarios.update', $user) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label for="name">Nombre</label>
                    <input type="text" name="name" class="form-control name"
                        placeholder="Ingrese el nombre del usuario">

                    @error('name')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="email">Correo Electrónico</label>
                    <input type="email" name="email" id="email" class="form-control"
                        placeholder="Ingrese el correo electrónico">

                    @error('email')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>


                <div class="form-group">
                    <label for="password">Contraseña</label>
                    <input type="password" name="password" id="v" class="form-control"
                        placeholder="Ingrese la contraseña">

                    @error('password')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password_confirmation">Confirmar Contraseña</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" class="form-control"
                        placeholder="Confirme la contraseña">

                    @error('password_confirmation')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group form-check">
                    <input type="checkbox" class="form-check-input" name="is_admin" id="is_admin">
                    <label class="form-check-label" for="is_admin">Administrador</label>
                </div>

                <button type="submit" class="btn btn-primary">Actualizar Usuario</button>
            </form>
        </div>
    </div>
</div>
