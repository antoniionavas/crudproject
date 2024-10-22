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
                            <button class="btn btn-danger ml-2" data-toggle="modal" data-target="#pdfReportModal">
                                <i class="fas fa-file-pdf"></i>
                            </button>
                        </div>
                        @endif

                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Creado por</th>
                            <th>Fecha</th>
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

        <div id='calendar-container' class="card" style="width: 49%">
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
                            <div class="form-group">
                                <label for="taskStartTime">Hora de Inicio</label>
                                <p id="taskStartTime" class="form-control-static"></p>
                            </div>

                            <input type="hidden" id="taskProjectId" hidden>
                            <div class="form-group">
                                <label for="taskDescription">Descripción</label>
                                <input type="text" class="form-control" id="taskDescription" name="description" required>
                            </div>

                            <div class="form-group">
                                <label for="taskEndTime">Hora de Fin</label>
                                <p id="taskEndTime" class="form-control-static"></p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
                                <button type="submit" class="btn btn-success">Guardar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="pdfReportModal" tabindex="-1" role="dialog" aria-labelledby="pdfReportModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="pdfReportModalLabel">Opciones del informe</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('projects.generatePdf') }}" method="GET" target="_blank">
                            @csrf
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="start_date">Fecha Desde:</label>
                                    <input type="date" name="start_date" id="start_date" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="end_date">Fecha Hasta:</label>
                                    <input type="date" name="end_date" id="end_date" class="form-control">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="project_id">Proyecto:</label>
                                <select name="project_id" id="project_id" class="form-control">
                                    <option value="">Todos los proyectos</option>
                                    @foreach ($projects as $project)
                                        <option value="{{ $project->id }}">{{ $project->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="user_id">Usuario:</label>
                                <select name="user_id" id="user_id" class="form-control">
                                    <option value="">Selecciona un usuario</option>
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-success">Generar</button>
                                <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>


    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js'></script>

    {{-- <script>
        document.addEventListener('DOMContentLoaded', function() {
            var Calendar = FullCalendar.Calendar;
            var Draggable = FullCalendar.Draggable;

            var containerEl = document.getElementById('projects-list');
            var calendarEl = document.getElementById('calendar');

            loadProjects();
            loadTasks();

            function loadProjects() {
                $.ajax({
                    url: '/proyectos/list',
                    method: 'GET',
                    success: function(data) {
                        containerEl.innerHTML = '';

                        data.forEach(function(event) {
                            var rowEl = document.createElement('tr');

                            var nameTd = document.createElement('td');
                            nameTd.innerText = event.name;

                            var userTd = document.createElement('td');
                            userTd.innerText = event.user;

                            var dateTd = document.createElement('td');
                            dateTd.innerText = event.date;

                            rowEl.appendChild(nameTd);
                            rowEl.appendChild(userTd);
                            rowEl.appendChild(dateTd);

                            rowEl.classList.add('fc-event');
                            rowEl.setAttribute('data-event', JSON.stringify({
                                id: event.id,
                                title: event.name
                            }));

                            containerEl.appendChild(rowEl);
                        });

                        new Draggable(containerEl, {
                            itemSelector: '.fc-event',
                            eventData: function(eventEl) {
                                return JSON.parse(eventEl.getAttribute('data-event'));
                            }
                        });
                    },
                    error: function(xhr) {
                        console.error('Error al cargar proyectos:', xhr);
                    }
                });
            }

            function loadTasks() {
                $.ajax({
                    url: '/tasks',
                    method: 'GET',
                    success: function(data) {
                        const events = data.map(task => ({
                            title: task.title,
                            start: task.start,
                            end: task.end,
                            id: task.id,
                            description: task.description || "",
                        }));
                        calendar.addEventSource(events);
                    },
                    error: function(xhr) {
                        console.error('Error al cargar tareas:', xhr);
                    }
                });
            }

            var calendar = new Calendar(calendarEl, {
                headerToolbar: {
                    left: 'prev',
                    center: 'title',
                    right: 'next'
                },
                initialView: 'timeGridDay',
                slotMinTime: '08:00:00',
                slotMaxTime: '18:30:00',
                slotDuration: '00:30:00',
                slotLabelInterval: '00:30:00',
                editable: true,
                droppable: true,
                allDaySlot: false,
                slotLabelFormat: {
                    hour: '2-digit',
                    minute: '2-digit',
                    hour12: false
                },

                eventContent: function(arg) {
                    const {
                        event
                    } = arg;
                    return {
                        html: `<div><strong>${event.title}</strong><div>${event.extendedProps.description || ''}</div></div>`
                    };
                },

                drop: function(info) {
                    const eventId = JSON.parse(info.draggedEl.getAttribute('data-event')).id;
                    const title = JSON.parse(info.draggedEl.getAttribute('data-event')).title;
                    const startTime = info.date;
                    const endTime = new Date(info.date.getTime() + 30 * 60000);
                    $('#taskStartTime').text(startTime.toLocaleString());
                    $('#taskEndTime').text(endTime.toLocaleString());
                    $('#taskProjectId').val(eventId);
                    $('#taskModal').modal('show');

                    currentDraggingEventId = eventId;


                    $('#taskForm').off('submit').on('submit', function(e) {

                        const description = $('#taskDescription').val();
                        const data = {
                            project_id: $('#taskProjectId').val(),
                            start: startTime.toLocaleString('sv-SE', {
                                timeZone: 'Europe/Madrid'
                            }),
                            end: endTime.toLocaleString('sv-SE', {
                                timeZone: 'Europe/Madrid'
                            }),
                            description: description,
                            _token: '{{ csrf_token() }}'
                        };

                        $.ajax({
                            url: '{{ route('tasks.store') }}',
                            type: 'POST',
                            data: data,
                            success: function(response) {
                                $('#successMessage').text(response.message).show()
                                    .delay(3000).fadeOut();
                                $('#taskModal').modal('hide');

                            },
                            error: function(xhr) {
                                alert('Error al crear la tarea.');
                            }
                        });

                        $('#cancelTask').off('click').on('click', function() {
                            tempEvent.remove();
                            $('#taskModal').modal('hide');
                        });
                    });
                }
            });


            calendar.render();

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
    </script> --}}

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var Calendar = FullCalendar.Calendar;
            var Draggable = FullCalendar.Draggable;

            var containerEl = document.getElementById('projects-list');
            var calendarEl = document.getElementById('calendar');
            var currentDraggingEventId;

            loadProjects();
            loadTasks();

            function loadProjects() {
                $.ajax({
                    url: '/proyectos/list',
                    method: 'GET',
                    success: function(data) {
                        containerEl.innerHTML = '';

                        data.forEach(function(event) {
                            var rowEl = document.createElement('tr');

                            var nameTd = document.createElement('td');
                            nameTd.innerText = event.name;

                            var userTd = document.createElement('td');
                            userTd.innerText = event.user;

                            var dateTd = document.createElement('td');
                            dateTd.innerText = event.date;

                            rowEl.appendChild(nameTd);
                            rowEl.appendChild(userTd);
                            rowEl.appendChild(dateTd);

                            rowEl.classList.add('fc-event');
                            rowEl.setAttribute('data-event', JSON.stringify({
                                id: event.id,
                                title: event.name
                            }));

                            containerEl.appendChild(rowEl);
                        });

                        new Draggable(containerEl, {
                            itemSelector: '.fc-event',
                            eventData: function(eventEl) {
                                return JSON.parse(eventEl.getAttribute('data-event'));
                            }
                        });
                    },
                    error: function(xhr) {
                        console.error('Error al cargar proyectos:', xhr);
                    }
                });
            }

            function loadTasks() {
                $.ajax({
                    url: '/tasks',
                    method: 'GET',
                    success: function(data) {
                        const events = data.map(task => ({
                            title: task.title,
                            start: task.start,
                            end: task.end,
                            id: task.id,
                            description: task.description || "",
                        }));
                        calendar.addEventSource(events);
                    },
                    error: function(xhr) {
                        console.error('Error al cargar tareas:', xhr);
                    }
                });
            }

            var calendar = new Calendar(calendarEl, {
                locale:'es',
                headerToolbar: {
                    left: 'prev',
                    center: 'title',
                    right: 'next'
                },
                initialView: 'timeGridDay',
                slotMinTime: '08:00:00',
                slotMaxTime: '18:30:00',
                slotDuration: '00:30:00',
                slotLabelInterval: '00:30:00',
                editable: true,
                droppable: true,
                allDaySlot: false,
                slotLabelFormat: {
                    hour: '2-digit',
                    minute: '2-digit',
                    hour12: false
                },

                eventContent: function(arg) {
                    const {
                        event
                    } = arg;
                    return {
                        html: `<div><strong>${event.title}</strong><div>${event.extendedProps.description || ''}</div></div>`
                    };
                },

                drop: function(info) {
                    const eventId = JSON.parse(info.draggedEl.getAttribute('data-event')).id;
                    const title = JSON.parse(info.draggedEl.getAttribute('data-event')).title;
                    const startTime = info.date;
                    const endTime = new Date(info.date.getTime() + 30 * 60000);
                    $('#taskStartTime').text(startTime.toLocaleString());
                    $('#taskEndTime').text(endTime.toLocaleString());
                    $('#taskProjectId').val(eventId);
                    $('#taskModal').modal('show');

                    $('#taskForm').off('submit').on('submit', function(e) {
                        e.preventDefault();
                        const description = $('#taskDescription').val();
                        const data = {
                            project_id: $('#taskProjectId').val(),
                            start: startTime.toLocaleString('sv-SE', {
                                timeZone: 'Europe/Madrid'
                            }),
                            end: endTime.toLocaleString('sv-SE', {
                                timeZone: 'Europe/Madrid'
                            }),
                            description: description,
                            _token: '{{ csrf_token() }}'
                        };

                        $.ajax({
                            url: '{{ route('tasks.store') }}',
                            type: 'POST',
                            data: data,
                            success: function(response) {
                                $('#successMessage').text(response.message).show()
                                    .delay(3000).fadeOut();
                                $('#taskModal').modal('hide');
                            },
                            error: function(xhr) {
                                alert('Error al crear la tarea.');
                            }
                        });
                    });
                }
            });

            calendar.render();

            $('#submitProject').click(function() {
                e.preventDefault();
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
    </script>


@endsection

@section('footer')
    <x-footer>
    </x-footer>
@stop
