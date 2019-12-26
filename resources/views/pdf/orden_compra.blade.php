<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Orden de Compra</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <style>
        body {
            text-align: justify;
        }

        h2 {
            font-family: 'Lucida Sans Unicode';
            font-size: 25px;
            color: #799F06;
            font-weight: bold;
        }

        h3 {
            font-family: 'Lucida Sans Unicode';
            font-size: 14px;
            color: #799F06;
            font-weight: bold;
        }

        .subTitulo {
            font-family:'Verdana';
            color: #276D9E;
            font-weight: bold;
            font-style: italic;
            font-size: 12px;
        }

        .textoHeader {
            font-family:'Myriad Pro';
            font-size:12px;
            color:#276D9E;
        }

        .header {
            width: 100%;
        }

        .cuerpo {
            margin-top: 20px;
            width: 100%;
            clear: both;
            font-size: 12px;
        }

        .izquierda {
            float: left;
            align-content: center;
            width: 20%;
        }

        .centro {
            float: left;
            align-content: center;
            width: 55%;
        }

        .derecha {
            float: left;
            width: 25%;
            align-content: center;
        }

        hr {
            clear: both;
            size: 0.5;
            color: #73A811;
            display: block;
        }

        .uno {
            float: left;
            width: 50%;
        }

        .dos {
            float: right;
            width: 50%;
        }

        table th {
            text-align: center;
        }

        footer{
            position: absolute;
            bottom: 0;
            width: 100%;
            height: 40px;
        }
    </style>
</head>
<body>
    <div class="header">
        <center><h2>ORDEN DE COMPRA {{ $orden_compra->id }}</h2></center>
        <br>
        <div class="izquierda">
            <img src="{{ asset('assets/images/icon.png') }}" alt="">
        </div>
        <div class="centro">
            <span class="subTitulo">Hotelera y Turismo Nonguén Ltda.</span><br>
            <span class="textoHeader">
                Rut: 76.657.620-6<br>
                Giro: Hotelería y Turismo<br>
                Camino Valle Nonguén 2151, Concepción, Chile<br>
                Teléfonos (56 41) 249 1749 / 249 1750
            </span>
        </div>
        <div class="derecha">
            <label for="">Fecha &nbsp;&nbsp;: <b> {{ $orden_compra->fecha }} </b></label><br>
        </div>
    </div>
    <hr>
    <div class="cuerpo">
        <h3>PROOVEDOR DE SERVICIOS</h3>
        <br>
        <div class="uno">
            <table>
                <tr>
                    <td>Razón Social</td>
                    <td>&nbsp;&nbsp;:&nbsp;&nbsp;</td>
                    <td>{{ $orden_compra->proveedor->nombre }}</td>
                </tr>
                <tr>
                    <td>Giro</td>
                    <td>&nbsp;&nbsp;:&nbsp;&nbsp;</td>
                    <td>{{ $orden_compra->proveedor->giro }}</td>
                </tr>
                <tr>
                    <td>Rut</td>
                    <td>&nbsp;&nbsp;:&nbsp;&nbsp;</td>
                    <td>{{ $orden_compra->proveedor->rut }}</td>
                </tr>
                <tr>
                    <td>Dirección</td>
                    <td>&nbsp;&nbsp;:&nbsp;&nbsp;</td>
                    <td>{{ $orden_compra->proveedor->direccion }}</td>
                </tr>
            </table>
        </div>
        <div class="dos">
            <table>
                <tr>
                    <td>Teléfono</td>
                    <td>&nbsp;&nbsp;:&nbsp;&nbsp;</td>
                    <td>{{ $orden_compra->proveedor->telefono }}</td>
                </tr>
                <tr>
                    <td>Correo</td>
                    <td>&nbsp;&nbsp;:&nbsp;&nbsp;</td>
                    <td>{{ $orden_compra->proveedor->correo }}</td>
                </tr>
                <tr>
                    <td>Comuna</td>
                    <td>&nbsp;&nbsp;:&nbsp;&nbsp;</td>
                    <td>{{ $orden_compra->proveedor->comuna }}</td>
                </tr>
                <tr>
                    <td>Contacto</td>
                    <td>&nbsp;&nbsp;:&nbsp;&nbsp;</td>
                    <td>{{ $orden_compra->proveedor->persona }}</td>
                </tr>
            </table>
        </div>
    </div>
    <hr>
    <div class="cuerpo">
        <h3>DETALLE</h3>
        <label for=""> Asunto : <b> {{ $orden_compra->asunto }} </b></label>
        <br>
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>DESCRIPCIÓN</th>
                        <th>CANTIDAD</th>
                        <th>VALOR UNITARIO</th>
                        <th>MEDIDA</th>
                        <th>TOTAL</th>
                    </tr>

                </thead>
                <tbody>
                    @for ($i = 0; $i < count($orden_compra->descripcionOrden); $i++)
                        <tr>
                            <td>{{ $orden_compra->descripcionOrden[$i] }}</td>
                            <td>{{ $orden_compra->cantidadOrden[$i] }}</td>
                            <td>${{ number_format($orden_compra->valorUnitarioOrden[$i], 0) }}</td>
                            <td>{{ $orden_compra->medidaOrden[$i] }}</td>
                            <td>${{ number_format((int)($orden_compra->cantidadOrden[$i]) * (int)($orden_compra->ValorUnitarioOrden[$i]),0) }}</td>
                        </tr>
                    @endfor
                    <tr>
                        <td align="right">NETO: <b>${{ number_format($orden_compra->valor_neto, 0) }}</b>  </td>
                        <td align="center">IVA : <b>${{ number_format($orden_compra->iva, 0) }}</b></td>
                        <td align="right"></td>
                        <td align="right"></td>
                        <td align="center">Total :<b>${{ number_format($orden_compra->total, 0) }}</b></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <hr>
    <div class="cuerpo">
        <h3>OBSERVACIONES</h3><br>
        @if(empty($orden_compra->observacion))
            Sin observaciones.
        @else
            {{ $orden_compra->observacion }}
        @endif
    </div>
    <footer>
        <center><b>{{ $orden_compra->usuario->nombre }} - {{ $orden_compra->usuario->email  }}<b/></center>
    </footer>
</body>
</html>
