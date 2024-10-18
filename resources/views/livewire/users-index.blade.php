<div>
    @if (session()->has('message'))
        <div class="alert alert-success">
            {{ session('message') }}
        </div>
    @endif
    <div class="card-header text-center">
        <input wire:model.live="search" class="form-control" placeholder="Ingrese el nombre o el correo de un usuario">
    </div>

    <div class="card">
        <div class="card-body">
            <table class="table table-striped">
                @if (auth()->user()->is_admin)
                    <div class="d-flex justify-content-end">
                        <a class="btn-primary btn" href="{{ route('usuarios.create') }}">Crear Usuario</a>
                    </div>
                @endif
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Email</th>
                        @if (auth()->user()->is_admin)
                            <th></th>
                            <th></th>
                            @endif
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                        <tr>
                            <td>{{ $user->id }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            @if (auth()->user()->is_admin)
                                <td width="10px">
                                    <a class="btn btn-primary" href="{{ route('usuarios.edit', $user) }}">Editar</a>
                                </td>
                                <td width="10px">
                                    <form action="{{ route('usuarios.destroy', $user) }}" method="POST">
                                        @csrf
                                        @method('delete')
                                        <button type="submit" class="btn btn-danger btn-sm">Eliminar</button>
                                    </form>
                                </td>
                            @endif
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="card-footer">
        {{ $users->links() }}
    </div>
</div>
