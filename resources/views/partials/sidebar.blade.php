
<!-- Sidebar -->
<div class="fixed flex flex-col -top-0 left-0 w-14 hover:w-52 md:hover:w-[260px] md:w-64 bg-gray-900 h-full text-white transition-all duration-300 border-none z-10 sidebar contenedor-sidebar">
    <div class="overflow-y-auto overflow-x-hidden flex flex-col justify-between flex-grow">
      <ul class="flex flex-col py-4 space-y-1">

       <!-- Logo y titulo del colegio -->
       <li class="flex items-center justify-center md:justify-start px-4 mb-6">
        <a href="{{ route('home') }}" class="flex flex-row items-center space-x-2">
          <img src="{{ asset('imagenes/medalla-logo.png') }}" alt="Medalla Milagrosa Logo" class="w-10 h-10 object-contain">
          <span class="text-base font-semibold hidden md:block whitespace-nowrap">Medalla Milagrosa</span>
        </a>
      </li>


        <li>
          <a href="{{ route('home') }}" class="relative flex flex-row items-center h-11 focus:outline-none hover:bg-red-600 text-white-600 hover:text-white-800 border-l-4 border-transparent hover:text-white pr-6 mb-1 rounded-lg {{ request()->routeIs('home') ? 'bg-red-800 text-white border-l-4 border-red-800 hover:bg-red-800' : '' }}">
            <span class="inline-flex justify-center items-center ml-2">
               <i class="bi bi-grid-fill"></i>
            </span>
            <span class="ml-[6px] text-sm tracking-wide truncate ">Dashboard</span>
          </a>
        </li>
        




        <li class="px-5 hidden md:block">
            <div class="flex flex-row items-center h-8">
              <div class="text-sm font-semibold tracking-wide text-gray-400 uppercase">CALENDARIO</div>
            </div>
          </li>

        <li>
          <a href="{{ route('calendario.index') }}" class="relative flex flex-row items-center h-11 focus:outline-none hover:bg-red-600 text-white-600 hover:text-white-800 border-l-4 border-transparent hover:text-white pr-6 mb-1 rounded-lg {{ request()->routeIs('calendario.index') ? 'bg-red-800 text-white border-l-4 border-red-800 hover:bg-red-800' : '' }}">
            <span class="inline-flex justify-center items-center ml-2">
                <i class="bi bi-calendar3"></i>
            </span>
            <span class="ml-[6px] text-sm tracking-wide truncate">Calendario</span>
          </a>
        </li>





        <li class="px-5 hidden md:block">
            <div class="flex flex-row items-center h-8">
              <div class="text-sm font-semibold tracking-wide text-gray-400 uppercase">PRESTAMOS</div>
            </div>
          </li>

        <li>
          <a href="{{ route('prestamos.index') }}" class="relative flex flex-row items-center h-11 focus:outline-none hover:bg-red-600 text-white-600 hover:text-white-800 border-l-4 border-transparent hover:text-white pr-6 mb-1 rounded-lg {{ request()->routeIs('prestamos.index') ? 'bg-red-800 text-white border-l-4 border-red-800 hover:bg-red-800' : '' }}">
            <span class="inline-flex justify-center items-center ml-2">
                <i class="bi bi-hourglass-split"></i>
            </span>
            <span class="ml-[6px] text-sm tracking-wide truncate">Préstamos</span>
          </a>
        </li>






        <li class="px-5 hidden md:block">
            <div class="flex flex-row items-center h-8">
              <div class="text-sm font-semibold tracking-wide text-gray-400 uppercase">PERSONAL</div>
            </div>
          </li>

        <li>
          <a href="{{ route('personals.index') }}" class="relative flex flex-row items-center h-11 focus:outline-none hover:bg-red-600 text-white-600 hover:text-white-800 border-l-4 border-transparent hover:text-white pr-6 mb-1 rounded-lg {{ request()->routeIs('personals.index') ? 'bg-red-800 text-white border-l-4 border-red-800 hover:bg-red-800' : '' }}">
            <span class="inline-flex justify-center items-center ml-2">
                <i class="bi bi-people-fill"></i>
            </span>
            <span class="ml-[6px] text-sm tracking-wide truncate">Personal</span>
          </a>
        </li>

      <!-- Dropdown para Recursos -->
      <li class="px-5 hidden md:block">
          <div class="flex flex-row items-center h-8">
              <div class="text-sm font-semibold tracking-wide text-gray-400 uppercase">RECURSOS</div>
          </div>
      </li>

      <li>
        <a href="javascript:void(0)" onclick="toggleRecursos()" class="relative flex flex-row items-center h-11 focus:outline-none hover:bg-red-600 text-white hover:text-white-800 border-l-4 border-transparent hover:border-red-600 pr-6 mb-1 rounded-lg w-full">
            <span class="inline-flex justify-center items-center ml-2">
                <i class="bi bi-pc-display-horizontal"></i>
            </span>
            <span class="ml-[6px] text-sm tracking-wide truncate">Recursos</span>
            <i id="chevron-icon" class="bi bi-chevron-down ml-auto"></i>
        </a>
      </li>

      <ul id="recursosDropdown" class="hidden space-y-1 pl-4">
          <li>
            <a href="{{ route('recursos.index') }}" class="flex flex-row items-center h-11 focus:outline-none hover:bg-red-600 text-white-600 hover:text-white-800 border-l-4 border-transparent hover:text-white pr-6 rounded-lg">
              <i class="bi bi-pc-display-horizontal"></i>
              <span class="ml-2 text-sm tracking-wide truncate">Recursos</span>
            </a>
          </li>
          <li>
            <a href="{{ route('marcas.index') }}" class="flex flex-row items-center h-11 focus:outline-none hover:bg-red-600 text-white-600 hover:text-white-800 border-l-4 border-transparent hover:text-white pr-6 rounded-lg">
              <i class="bi bi-r-circle"></i>
              <span class="ml-2 text-sm tracking-wide truncate">Marcas</span>
            </a>
          </li>
          <li>
            <a href="{{ route('categorias.index') }}" class="flex flex-row items-center h-11 focus:outline-none hover:bg-red-600 text-white-600 hover:text-white-800 border-l-4 border-transparent hover:text-white pr-6 rounded-lg">
              <i class="bi bi-tags-fill"></i>
              <span class="ml-2 text-sm tracking-wide truncate">Categorías</span>
            </a>
          </li>
      </ul>

<script>
function toggleRecursos() {
    const dropdown = document.getElementById('recursosDropdown');
    const chevronIcon = document.getElementById('chevron-icon');
    dropdown.classList.toggle('hidden');

    if (dropdown.classList.contains('hidden')) {
        chevronIcon.classList.replace('bi-chevron-up', 'bi-chevron-down');
    } else {
        chevronIcon.classList.replace('bi-chevron-down', 'bi-chevron-up');
    }
}
</script>



          <li>
            <hr class="my-3 border-stone-50 border">
          </li>



@if (Auth::check() && Auth::user()->role === 'admin')
        <li>
            <a href="{{ route('admin.users.index') }}" class="relative flex flex-row items-center h-11 focus:outline-none hover:bg-blue-800 dark:hover:bg-gray-600 text-white-600 hover:text-white-800 border-l-4 border-transparent hover:border-blue-500 dark:hover:border-gray-800 pr-6">
              <span class="inline-flex justify-center items-center  ml-2">
                <i class="bi bi-person-fill-add"></i>
              </span>
              <span class="ml-2 text-sm tracking-wide truncate">Agregar Usuarios</span>
            </a>
          </li>
@endif
    </div>
  </div>