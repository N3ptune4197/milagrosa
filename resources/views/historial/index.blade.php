@extends('adminlte::page')

@section('template_title')
    Historials
@endsection

@foreach ($historials as $historial)
    <tr>
        <td>{{ $historial->detallePrestamo->prestamo->personal->nombres }} {{ $historial->detallePrestamo->prestamo->personal->a_paterno }}</td>
        <td>{{ $historial->detallePrestamo->recurso->nombre }}</td>
        <td>{{ $historial->detallePrestamo->prestamo->fecha_prestamo }}</td>
        <td>{{ $historial->detallePrestamo->prestamo->fecha_devolucion }}</td>
    </tr>
@endforeach
