@extends('admin.layout')

@section('content')

<h2 class="fw-bold mb-4">

    Lista de Postulantes

</h2>

<div class="card border-0 shadow-sm rounded-4">

    <div class="table-responsive">

        <table class="table align-middle">

            <thead>

                <tr>

                    <th>Código</th>

                    <th>Nombres</th>

                    <th>Apellidos</th>

                    <th>DNI</th>

                    <th>Teléfono</th>

                    <th>Acciones</th>

                </tr>

            </thead>

            <tbody>

                @foreach($postulantes as $postulante)

                <tr>

                    <td>

                        {{ $postulante->id_postulacion }}

                    </td>

                    <td>

                        {{ $postulante->nombres }}

                    </td>

                    <td>

                        {{ $postulante->apellidos }}

                    </td>

                    <td>

                        {{ $postulante->dni }}

                    </td>

                    <td>

                        {{ $postulante->telefono }}

                    </td>

                    <td>

                        <a
                            href="{{ url('admin/postulante/visualizar/'.$postulante->id_postulacion) }}"
                            target="_blank"
                            class="btn btn-primary btn-sm"
                        >

                            Visualizar

                        </a>

                        <a
                            href="{{ url('admin/postulante/descargar/'.$postulante->id_postulacion) }}"
                            class="btn btn-success btn-sm"
                        >

                            Descargar

                        </a>

                    </td>

                </tr>

                @endforeach

            </tbody>

        </table>

    </div>

</div>

@endsection