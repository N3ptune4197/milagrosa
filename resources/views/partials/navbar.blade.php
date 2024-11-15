<nav class="bg-gray-800 fixed top-0 w-full z-10">
  <div class="mx-auto max-w-7xl px-2 sm:px-6 lg:px-8">
    <div class="relative flex h-16 items-center justify-between">
      <!-- Logo -->
      <div class="flex flex-1 items-center justify-center sm:items-stretch sm:justify-start">
        <div class="flex flex-shrink-0 items-center">
          <img class="h-8 w-auto" src="public/imagenes/AIP.webp" alt="Usuario">
        </div>
      </div>

      <!-- Sección derecha con notificaciones y perfil -->
      <div class="absolute inset-y-0 right-0 flex items-center pr-2 sm:static sm:inset-auto sm:ml-6 sm:pr-0">

        <!-- Icono de Notificación -->
        <div class="relative">

          <button type="button" onclick="toggleNotifications()" class="relative rounded-full bg-gray-800 p-1 text-gray-400 hover:text-white focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-gray-800">
            <span class="sr-only">View notifications</span>
            <i class="fas fa-bell text-lg"></i> <!-- Icono de campana-->
            <span class="absolute top-0 right-0 block h-5 w-5 rounded-full bg-yellow-400 text-white text-center text-xs">
              {{ $totalNotificaciones }}
            </span>
          </button>
          <div id="notification-dropdown" class="hidden absolute right-0 mt-2 w-64 origin-top-right rounded-lg bg-white py-2 shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none z-50">
            <div class="px-4 py-2 text-sm text-gray-700 border-b">
                {{ $totalNotificaciones }} Notificaciones
            </div>
            <div class="overflow-y-auto max-h-64">
              <!-- Notificaciones para hoy -->
              @foreach ($notificacionesHoy as $notificacion)
                @php
                  // Definir las clases de color para el tiempo restante
                  $timeClass = 'text-green-500'; // Verde (sin atraso)
                  
                  if (isset($notificacion->minutos_atraso) || isset($notificacion->horas_atraso)) {
                      $timeClass = 'text-red-500'; // Rojo (atrasado)
                  } elseif (isset($notificacion->minutos_restantes) && $notificacion->minutos_restantes <= 30) {
                      $timeClass = 'text-yellow-500'; // Amarillo (menos de 30 minutos)
                  }
                @endphp

                <a href="{{ route('prestamos.index', ['highlight' => $notificacion->id]) }}" class="block px-4 py-2 text-gray-700 break-words">
                  <div class="text-sm">
                    {{ $notificacion->a_paterno }} debe devolver el recurso {{ $notificacion->categoria }} ({{ $notificacion->nro_serie }})
                    @if(isset($notificacion->horas_atraso) && $notificacion->horas_atraso > 0)
                      <span>(Atraso de <span class="{{ $timeClass }}">{{ $notificacion->horas_atraso }} horas y {{ $notificacion->minutos_atraso }} minutos</span>)</span>
                    @elseif(isset($notificacion->minutos_atraso) && $notificacion->minutos_atraso > 0)
                      <span>(Atraso de <span class="{{ $timeClass }}">{{ $notificacion->minutos_atraso }} minutos</span>)</span>
                    @elseif(isset($notificacion->dias_restantes) && $notificacion->dias_restantes > 0)
                      <span>(Faltan <span class="{{ $timeClass }}">{{ $notificacion->dias_restantes }} días y {{ $notificacion->horas_restantes }} horas</span>)</span>
                    @elseif(isset($notificacion->horas_restantes) && $notificacion->horas_restantes > 0)
                      <span>(Faltan <span class="{{ $timeClass }}">{{ $notificacion->horas_restantes }} horas y {{ $notificacion->minutos_restantes }} minutos</span>)</span>
                    @elseif(isset($notificacion->minutos_restantes) && $notificacion->minutos_restantes > 0)
                      <span>(Faltan <span class="{{ $timeClass }}">{{ $notificacion->minutos_restantes }} minutos</span>)</span>
                    @else
                      <span>(Tiempo restante hoy)</span>
                    @endif
                  </div>
                </a>
              @endforeach

              <!-- Notificaciones atrasadas -->
              @foreach ($notificacionesAtrasadas as $notificacion)
                <a href="{{ route('prestamos.index', ['highlight' => $notificacion->id]) }}" class="block px-4 py-2 text-gray-700 break-words">
                  <div class="text-sm">
                    {{ $notificacion->a_paterno }} no ha devuelto el recurso {{ $notificacion->categoria }} ({{ $notificacion->nro_serie }}) atrasado por <span class="text-red-500">{{ $notificacion->dias_atraso }} días</span>.
                  </div>
                </a>
              @endforeach
            </div>
            <div class="px-4 py-2 border-t">
              <button class="w-full px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600" onclick="openModal()">Ver todas las notificaciones</button>
            </div>
          </div>
        </div>

        <!-- Dropdown de perfil -->
        <div class="relative ml-3">
          <button type="button" onclick="toggleProfileMenu()" class="flex rounded-full bg-gray-800 text-sm focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-gray-800">
            <img class="h-8 w-8 rounded-full" src="{{ asset('imagenes/AIP.webp') }}" alt="">

          </button>
          <div id="profile-dropdown" class="hidden absolute right-0 mt-2 w-48 origin-top-right rounded-md bg-white py-1 shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none z-50">
            <a href="#" class="block px-4 py-2 text-sm text-gray-700">Your Profile</a>
            <a href="#" class="block px-4 py-2 text-sm text-gray-700">Settings</a>
            <form method="POST" action="{{ route('logout') }}">
              @csrf
              <button type="submit" class="w-full text-left px-4 py-2 text-sm text-gray-700">Cerrar sesión</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- Modal para ver todas las notificaciones -->
<div class="fixed inset-0 hidden bg-gray-800 bg-opacity-75 flex items-center justify-center z-50" id="allNotificationsModal">
  <div class="bg-white rounded-lg shadow-lg max-w-lg w-full">
      <div class="flex justify-between items-center p-4 border-b">
          <h5 class="text-lg font-semibold">Todas las Notificaciones</h5>
          <button class="text-gray-600 hover:text-gray-900" onclick="closeModal()">&times;</button>
      </div>
      <div class="p-4 overflow-y-auto max-h-96">
          <ul class="space-y-4">
             <!-- Título para Notificaciones de Hoy -->
              @if(count($notificacionesHoy))
              <h6 class="text-md font-semibold text-gray-700 mb-2">Notificaciones de Hoy</h6>
              @foreach ($notificacionesHoy as $notificacion)
                  <a href="#" class="block px-4 py-2 text-gray-700 break-words">
                      <div class="text-sm">
                          {{ $notificacion->a_paterno }} debe devolver el recurso {{ $notificacion->categoria }} ({{ $notificacion->nro_serie }}) hoy.
                      </div>
                      <span class="text-xs text-gray-500 float-right">
                          @if (isset($notificacion->minutos_atraso))
                              Debía devolver hace 
                              <span class="text-red-500">{{ $notificacion->horas_atraso }} horas</span> y 
                              <span class="text-red-500">{{ $notificacion->minutos_atraso }} minutos</span>.
                          @elseif (isset($notificacion->minutos_restantes) && $notificacion->minutos_restantes <= 30 && $notificacion->horas_restantes == 0)
                              Faltan 
                              <span class="text-yellow-500">{{ $notificacion->minutos_restantes }} minutos</span>.
                          @elseif ($notificacion->horas_restantes == 0 && isset($notificacion->minutos_restantes))
                              Faltan 
                              <span class="text-green-500">{{ $notificacion->minutos_restantes }} minutos</span>.
                          @else
                              Faltan 
                              <span class="text-green-500">{{ $notificacion->horas_restantes }} horas</span> y 
                              <span class="text-green-500">{{ $notificacion->minutos_restantes }} minutos</span>.
                          @endif
                      </span>
                  </a>
              @endforeach
              @else
              <li class="bg-gray-100 p-3 rounded-lg">No hay notificaciones para hoy.</li>
              @endif
              
              <!-- Título para Notificaciones Atrasadas -->
              @if(count($notificacionesAtrasadas))
              <h6 class="text-md font-semibold text-gray-700 mb-2 mt-4">Notificaciones Atrasadas</h6>
              @foreach ($notificacionesAtrasadas as $notificacion)
                  <a href="#" class="block px-4 py-2 text-gray-700 break-words">
                      <div class="text-sm">
                          {{ $notificacion->a_paterno }} no ha devuelto el recurso {{ $notificacion->categoria }} ({{ $notificacion->nro_serie }}).
                      </div>
                      <span class="text-xs text-red-500 float-right">
                          Atrasado por <span class="text-red-500">{{ $notificacion->dias_atraso }} días</span>.
                      </span>
                  </a>
              @endforeach
              @else
              <li class="bg-gray-100 p-3 rounded-lg">No hay notificaciones atrasadas.</li>
              @endif
          </ul>
      </div>
      <div class="p-4 border-t text-right">
          <button class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600" onclick="closeModal()">Cerrar</button>
      </div>
  </div>
</div>
</nav>
<!-- JavaScript para alternar los desplegables -->
<script>
  function toggleNotifications() {
    document.getElementById("notification-dropdown").classList.toggle("hidden");
  }

  function toggleProfileMenu() {
    document.getElementById("profile-dropdown").classList.toggle("hidden");
  }

  function toggleMobileMenu() {
    document.getElementById("mobile-menu").classList.toggle("hidden");
  }

  // Abre o cierra el menú de notificaciones
  document.getElementById('notificationDropdown').addEventListener('click', function() {
    const menu = document.getElementById('notificationMenu');
    menu.classList.toggle('hidden');
  });

  // Abre el modal de notificaciones
  function openModal() {
    document.getElementById('allNotificationsModal').classList.remove('hidden');
    document.getElementById('notificationMenu').classList.add('hidden'); // Cierra el menú al abrir el modal
  }

  // Cierra el modal de notificaciones
  function closeModal() {
    document.getElementById('allNotificationsModal').classList.add('hidden');
  }
</script>
@section ('links')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
@stop
