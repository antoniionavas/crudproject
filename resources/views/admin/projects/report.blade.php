 <!DOCTYPE html>
 <html>
 <head>
     <style>
         body { font-family: Arial, sans-serif; font-size: 12px; }
         table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
         table, th, td { border: 1px solid black; padding: 8px; }
         th { background-color: #f2f2f2; }
         .header-table { margin-bottom: 20px; }
         .header-table td { vertical-align: top; }
     </style>
 </head>
 <body>
 
 
     <table class="header-table">
         <tr>
             <td>
                 <strong>Desde Fecha:</strong> {{ $startDate }}<br>
                 <strong>Hasta Fecha:</strong> {{ $endDate }}
             </td>
             <td>
                 <strong>Proyecto:</strong> {{ $projectName }}<br>
                 <strong>Usuario:</strong> {{ $userName }}
             </td>
         </tr>
     </table>
 
     @foreach($tasksByProject as $projectId => $tasks)
         <h2>{{ $tasks->first()->project->name }}</h2>
         <table>
             <thead>
                 <tr>
                     <th>ID</th>
                     <th>INICIO</th>
                     <th>FIN</th>
                     <th>MIN</th>
                     <th>USUARIO</th>
                     <th>TAREA REALIZADA</th>
                 </tr>
             </thead>
             <tbody>
                 @php
                     $totalDuration = 0; 
                 @endphp
 
                 @foreach($tasks as $task)
                     @php
                         $start = \Carbon\Carbon::parse($task->start_time);
                         $end = \Carbon\Carbon::parse($task->end_time);
                         $duration = $start->diffInMinutes($end); 
                         $totalDuration += $duration;
                     @endphp
 
                     <tr>
                         <td>{{ $task->id }}</td>
                         <td>{{ $start->format('d/m/Y H:i') }}</td>
                         <td>{{ $end->format('d/m/Y H:i') }}</td>
                         <td>{{ $duration }} mins</td>
                         <td>{{ $task->user->name }}</td>
                         <td>{{ $task->description }}</td>
                     </tr>
                 @endforeach
             </tbody>
         </table>
 
         <p><strong>TOTAL MINS: {{ $totalDuration }}</strong></p>
     @endforeach
 
 </body>
 </html>
 

