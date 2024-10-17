<div>
    <div class="card-header text-center">
        <input wire:model.live="search" class="form-control" placeholder="Ingrese el nombre o el correo de un usuario">
    </div>

    <div class="card">
        <div class="card-body">
            <table class="table table-striped">
                <div class="d-flex justify-content-end">
                    <a class="btn-primary btn" href="{{route('usuarios.create')}}">Crear Usuario</a>
                </div>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Email</th>
                        <th></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                        <tr>
                            <td>{{$user->id}}</td>
                            <td>{{$user->name}}</td>
                            <td>{{$user->email}}</td>
                            <td width="10px">
                                <a class="btn btn-primary" href="{{route('usuarios.edit', $user)}}">Editar</a>
                            </td>
                            <td width="10px">
                                <form action="{{ route('usuarios.destroy', $user) }}" method="POST">
                                    @csrf
                                    @method('delete')
                                    <button type="submit" class="btn btn-danger btn-sm">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="card-footer">
        {{$users->links()}}
    </div>
</div>
