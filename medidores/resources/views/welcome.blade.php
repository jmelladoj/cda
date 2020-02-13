<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Inicio - Registros históricos</title>

        <!-- Fonts -->
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

    </head>
    <body>
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <a class="navbar-brand" href="{{ url('/') }}">Inicio</a>
        </nav>

        <div class="container">
            <div class="row">
                <div class="col-md-12 text-center">
                    <br>
                    <h3>Registro histórico de Totalizador</h3>
                    <br>
                </div>
            </div>
            <div class="row col-md-12">
                <div class="table table-responsive table-striped">
                    <table class="table">
                        <thead align="center">
                            <th>#</th>
                            <th>Voltaje</th>
                            <th>Corriente</th>
                            <th>Potencia</th>
                            <th>Factor Potencia</th>
                            <th>Frecuencia</th>
                            <th>Energía Entrega</th>
                            <th>Fecha Registro</th>
                        </thead>
                        <tbody>
                            @forelse ($datos as $item)
                                <tr align="center">
                                    <td> {{ $item->id }} </td>
                                    <td> {{ $item->voltaje }} </td>
                                    <td> {{ $item->corriente }} </td>
                                    <td> {{ $item->potencia }} </td>
                                    <td> {{ $item->factor_potencia }} </td>
                                    <td> {{ $item->frecuencia }} </td>
                                    <td> {{ $item->energia_entrega }} </td>
                                    <td> {{ $item->created_at }} </td>
                                </tr>
                            @empty
                                <tr align="center">
                                    <td colspan="8">Sin registros</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </body>
</html>

