@extends('admin.layout')

@section('content')

<h2 class="fw-bold mb-4">

    Gestión de Postulantes

</h2>

<div class="card border-0 shadow-sm rounded-4">

    <div class="table-responsive">

        <table class="table align-middle">

            <thead>

                <tr>

                    <th>Empresa</th>

                    <th>Oferta</th>

                    <th>Total Postulados</th>

                    <th>Acciones</th>

                </tr>

            </thead>

            <tbody>

                @foreach($ofertas as $oferta)

                <tr>

                    <td>

                        {{ $oferta->nombre_empresa ?? 'N/A' }}

                    </td>

                    <td>

                        {{ $oferta->titulo_puesto }}

                    </td>

                    <td>

                        {{ $oferta->total_postulados }}

                    </td>

                    <td>

                        <a
                            href="{{ url('admin/postulantes/ver/'.$oferta->id_publica) }}"
                            class="btn btn-primary btn-sm"
                        >

                            Ver

                        </a>

                        <a
                            href="{{ url('admin/postulantes/descargar-todos/'.$oferta->id_publica) }}"
                            class="btn btn-success btn-sm"
                        >

                            Descargar CVs

                        </a>

                    </td>

                </tr>

                @endforeach

            </tbody>

        </table>

    </div>

</div>

@endsection