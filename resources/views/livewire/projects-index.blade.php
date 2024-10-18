<div>
    @if (session()->has('message'))
        <div class="alert alert-success" id="successMessage" style="display:none;">
            {{ session('message') }}
        </div>
    @endif

    <div class="card-header text-center">
        <input wire:model.live="search" class="form-control" placeholder="Ingrese el nombre de un proyecto">
    </div>

    <div class="card" style="width: 50%">
        <div class="card-body">
            <table class="table table-striped">
                @if (auth()->user()->is_admin)
                    <div class="d-flex justify-content-end">
                        <button class="btn btn-primary" data-toggle="modal" data-target="#addProjectModal">Añadir
                            Proyecto</button>
                    </div>
                @endif
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Creado por</th>
                        <th>Última actualización</th>
                        @if (auth()->user()->is_admin)
                            <th></th>
                            <th></th>
                        @endif
                    </tr>
                </thead>
                <tbody id="projects-list">
                    @foreach ($projects as $project)
                        <tr>
                            <td>{{ $project->name }}</td>
                            <td>{{ $project->user->name }}</td>
                            <td>{{ $project->updated_at }}</td>
                            @if (auth()->user()->is_admin)
                                <td width="10px">
                                    <a class="btn btn-primary" href="#">Editar</a>
                                </td>
                                <td width="10px">
                                    <a class="btn btn-danger" href="#">Eliminar</a>
                                </td>
                            @endif
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="card-footer">
        {{ $projects->links() }}
    </div>

    <div class="modal fade" id="addProjectModal" tabindex="-1" role="dialog" aria-labelledby="addProjectModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addProjectModalLabel">Nuevo Proyecto</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="addProjectForm">
                        <div class="form-group">
                            <label for="name">Nombre del Proyecto</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                            <button type="button" class="btn btn-primary" id="submitProject">Guardar Proyecto</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $('#submitProject').click(function() {
            const name = $('#name').val();

            $.ajax({
                url: '{{ route('proyectos.store') }}',
                type: 'POST',
                data: {
                    name: name,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    $('#successMessage').text(response.message).show().delay(3000)
                .fadeOut();
                    $('#addProjectModal').modal('hide');
                    $('#name').val('');
                    $('#projects-list').append('<tr><td>' + name +
                        '</td><td>{{ auth()->user()->name }}</td><td>Justo Ahora</td></tr>'
                        );
                },
                error: function(xhr) {
                    let errorMessage = 'Error al crear el proyecto.';
                    alert(errorMessage);
                }
            });
        });
    });
</script>
