<!DOCTYPE html>
<html lang="es">
    @include('sections.head')
<body class="sidebar-mini layout-fixed sidebar-collapse">
    <div class="wrapper" id="app">
        <App ruta="{{route('basepath')}}"></App>
        {{-- @if (Auth::check())
        @else
            <Auth ruta="{{route('basepath')}}"></Auth>
        @endif --}}
    </div>
    @include('sections.script')
</body>
</html>
