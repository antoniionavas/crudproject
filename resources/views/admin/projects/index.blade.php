@extends('adminlte::page')

@section('content_header')
    <h1>Lista de Proyectos</h1>
@stop


@section('content')

    <div class="justify-content d-flex">
        @if (session()->has('message'))
            <div class="alert alert-success" id="successMessage" style="display:none;">
                {{ session('message') }}
            </div>
        @endif

        <div class="card mr-3" style="width: 49%">
            <div class="card-body">
                <table class="table table-striped">
                    @if (auth()->user()->is_admin)
                        <div class="d-flex justify-content-end space-around">
                            <button class="btn btn-primary" data-toggle="modal" data-target="#addProjectModal">+</button>
                            <button class="btn btn-danger ml-2" data-toggle="modal" data-target="#addProjectModal"> <i
                                    class="fas fa-file-pdf"></i></button>
                        </div>
                    @endif

                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Creado por</th>
                            <th>Fecha</th>
                            @if (auth()->user()->is_admin)
                                <th></th>
                                <th></th>
                            @endif
                        </tr>
                    </thead>
                    <tbody id="projects-list">

                    </tbody>
                </table>
            </div>
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
                                <label for="name">Nombre</label>
                                <input type="text" class="form-control" id="name" name="name" required>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                <button type="button" class="btn btn-primary" id="submitProject">Guardar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="card" style="width: 49%">
            <div class="card-body">
                <div id="calendar">

                </div>
            </div>
        </div>

        <div class="modal fade" id="taskModal" tabindex="-1" role="dialog" aria-labelledby="taskModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="taskModalLabel">Nueva Tarea</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="taskForm">
                            <input type="hidden" id="taskProjectId">
                            <div class="form-group">
                                <label for="taskDescription">Descripción</label>
                                <input type="text" class="form-control" id="taskDescription" name="description" required>
                            </div>
                            <div class="form-group">
                                <label for="taskStartTime">Hora de Inicio</label>
                                <input type="datetime-local" class="form-control" id="taskStartTime" name="start_time"
                                    required>
                            </div>
                            <div class="form-group">
                                <label for="taskEndTime">Hora de Fin</label>
                                <input type="datetime-local" class="form-control" id="taskEndTime" name="end_time" required>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                <button type="submit" class="btn btn-primary">Guardar Tarea</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>


    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js'></script>

    <script>
        $(document).ready(function() {
            function loadProjects() {
                $.ajax({
                    url: '{{ route('projects.list') }}',
                    type: 'GET',
                    success: function(response) {
                        $('#projects-list').empty();
                        $.each(response, function(index, project) {
                            const date = new Date(project.updated_at);

                            const options = {
                                year: 'numeric',
                                month: '2-digit',
                                day: '2-digit',
                                hour: '2-digit',
                                minute: '2-digit',
                                hour12: false
                            };
                            const formattedDate = date.toLocaleString('es-ES', options).replace(
                                ',', '');

                            let row = `<tr draggable="true" data-project-id="${project.id}">
                        <td>${project.name}</td>
                        <td>${project.user.name}</td>
                        <td>${formattedDate}</td>`;
                            @if (auth()->user()->is_admin)
                                row += `<td width="10px"><a class="btn btn-primary" href="#">Editar</a></td>
                                <td width="10px"><a class="btn btn-danger" href="#">Eliminar</a></td>`;
                            @endif
                            row += `</tr>`;
                            $('#projects-list').append(row);
                        });
                    }
                });
            }

            loadProjects();

            $('#submitProject').click(function() {
                const name = $('#name').val();
                $.ajax({
                    url: '{{ route('projects.create') }}',
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
                        loadProjects();
                    },
                    error: function(xhr) {
                        alert('Error al crear el proyecto.');
                    }
                });
            });
        });

        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');

            var calendar = new FullCalendar.Calendar(calendarEl, {
                locale: 'es',
                headerToolbar: {
                    left: 'prev,next,today',
                    center: 'title',
                    right: 'dayGridMonth,dayGridWeek,timeGridDay'
                },
                views: {
                    initialView: 'timeGridDay',
                    slotDuration: '00:30:00',
                },
                allDaySlot: false,
                editable: true,
                droppable: true,
                events: 'tasks',

                drop: function(info) {
                    const projectId = info.draggedEl.getAttribute('data-project-id');
                    const startTime = info.dateStr;
                    $('#taskProjectId').val(projectId);
                    $('#taskStartTime').val(startTime);
                    $('#taskModal').modal('show'); 

                    $('#taskForm').submit(function(e) {
                        e.preventDefault();
                        const description = $('#taskDescription').val();
                        const endTime = $('#taskEndTime').val();

                        $.ajax({
                            url: '{{ route('tasks.store') }}',
                            type: 'POST',
                            data: {
                                project_id: projectId,
                                start_time: startTime,
                                end_time: endTime,
                                description: description,
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(response) {
                                if (response.success) {
                                    calendar
                                .refetchEvents(); 
                                    alert('Tarea añadida exitosamente');
                                    $('#taskModal').modal('hide'); 
                                }
                            },
                            error: function(xhr) {
                                alert('Error al añadir la tarea.');
                            }
                        });
                    });
                }
            });

            calendar.render();

            $('#projects-list tr').each(function() {
                $(this).on('dragstart', function(event) {
                    event.originalEvent.dataTransfer.setData('text', event.target.innerText);
                });
            });
        });
    </script>
@endsection

@section('footer')
    <x-footer>
    </x-footer>
@stop
