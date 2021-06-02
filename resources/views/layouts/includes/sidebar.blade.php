<div class="list-group">
    <a href="{{ route('users.tareas') }}" class="list-group-item list-group-item-action">Tareas</a>
    <a href="{{ route('asignaciones') }}" class="list-group-item list-group-item-action">Asignaciones</a>
    <a href="{{ route('users.actividad') }}" class="list-group-item list-group-item-action">Actividad</a>
    @if (Auth::user()->rol == 'admin')
        <a href="{{ route('tareas.editar') }}" class="list-group-item list-group-item-action">Gestión de Tareas</a>
        <a href="{{ route('incentivos') }}" class="list-group-item list-group-item-action">Incentivos</a>
        <a href="{{ route('admin.usuarios') }}" class="list-group-item list-group-item-action">Gestión de usuarios</a>
    @endif

</div>
