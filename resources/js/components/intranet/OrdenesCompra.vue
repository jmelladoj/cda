<template>
    <div>
        <titulo-pagina titulo="Ordenes de compra"></titulo-pagina>

        <b-row>
            <b-col>
                <b-card-group>
                    <b-card class="mt-0 mb-0">
                            <b-col cols="12">
                                <div class="d-flex no-block align-items-center">
                                    <div>
                                        <h6><i class="fa fa-usd"></i></h6>
                                        <p class="text-muted"><b>Gasto diario</b></p>
                                    </div>
                                    <div class="ml-auto">
                                        <h6 class="counter text-primary">{{ gastos.diario | currency}}</h6>
                                    </div>
                                </div>
                            </b-col>
                    </b-card>
                    <b-card class="mt-0 mb-0">

                            <b-col cols="12">
                                <div class="d-flex no-block align-items-center">
                                    <div>
                                        <h6><i class="fa fa-usd"></i></h6>
                                        <p class="text-muted"><b>Gasto semanal</b></p>
                                    </div>
                                    <div class="ml-auto">
                                        <h6 class="counter text-warning">{{ gastos.semanal | currency}}</h6>
                                    </div>
                                </div>
                            </b-col>
                    </b-card>
                    <b-card class="mt-0 mb-0">
                            <b-col cols="12">
                                <div class="d-flex no-block align-items-center">
                                    <div>
                                        <h6><i class="fa fa-usd"></i></h6>
                                        <p class="text-muted"><b>Gasto mensual</b></p>
                                    </div>
                                    <div class="ml-auto">
                                        <h6 class="counter text-success">{{ gastos.mensual | currency}}</h6>
                                    </div>
                                </div>
                            </b-col>
                    </b-card>
                    <b-card class="mt-0 mb-0">
                            <b-col cols="12">
                                <div class="d-flex no-block align-items-center">
                                    <div>
                                        <h6><i class="fa fa-usd"></i></h6>
                                        <p class="text-muted"><b>Gasto anual</b></p>
                                    </div>
                                    <div class="ml-auto">
                                        <h6 class="counter text-info">{{ gastos.anual | currency}}</h6>
                                    </div>
                                </div>
                            </b-col>
                    </b-card>
                </b-card-group>
            </b-col>
        </b-row>

        <b-row>
            <b-col>
                <b-card>
                    <b-row>
                        <b-col lg="6" class="my-1">
                            <b-form-group label="Búsqueda" label-cols-sm="2" label-align-sm="left" label-size="sm" label-for="filterInput" class="mb-0" >
                            <b-input-group size="sm">
                                <b-form-input v-model="filter" type="search" id="filterInput" placeholder="Escribe para buscar"></b-form-input>
                                <b-input-group-append>
                                    <b-button :disabled="!filter" @click="filter = ''">Limpiar</b-button>
                                </b-input-group-append>
                            </b-input-group>
                            </b-form-group>
                        </b-col>

                        <b-col lg="6" class="my-1">
                            <b-form-group label="Ordenar" label-cols-sm="2" label-align-sm="left" label-size="sm" label-for="sortBySelect" class="mb-0">
                                <b-input-group size="sm">
                                    <b-form-select v-model="sortBy" id="sortBySelect" :options="sortOptions" class="w-75">
                                        <template v-slot:first>
                                            <option value="">Sin ordenar</option>
                                        </template>
                                    </b-form-select>
                                    <b-form-select v-model="sortDesc" size="sm" :disabled="!sortBy" class="w-25">
                                        <option :value="false">Asc</option>
                                        <option :value="true">Desc</option>
                                    </b-form-select>
                                </b-input-group>
                            </b-form-group>
                        </b-col>

                        <b-col lg="6" class="my-1">
                            <b-form-group label="Por página" label-cols-sm="2" label-align-sm="left" label-size="sm" label-for="perPageSelect" class="mb-0">
                                <b-form-select v-model="perPage" id="perPageSelect" size="sm" :options="pageOptions"></b-form-select>
                            </b-form-group>
                        </b-col>

                        <b-col lg="6" class="my-1">
                            <b-pagination v-model="currentPage" :total-rows="totalRows" :per-page="perPage" align="fill" size="sm" class="my-0" ></b-pagination>
                        </b-col>
                    </b-row>

                    <b-table class="my-3" show-empty small striped outlined stacked="sm" :items="items" :fields="fields" :current-page="currentPage" :per-page="perPage" :filter="filter" :sort-by.sync="sortBy" :sort-desc.sync="sortDesc" @filtered="onFiltered" >
                        <template v-slot:empty="scope">
                            <center><h5>No hay registros</h5></center>
                        </template>

                        <template v-slot:cell(total)="row">
                            {{ row.item.total | currency }}
                        </template>

                        <template v-slot:cell(acciones)="row">
                            <b-button v-if="usuario && usuario.email" size="xs" variant="info" title="Enviar registro" @click="enviar_mail(row.item.id)">
                                <i class="fa fa-paper-plane"></i>
                            </b-button>

                            <b-button size="xs" variant="success" title="Duplicar registro" @click="abrir_modal_orden_compra(row.item, 1)">
                                <i class="fa fa-plus"></i>
                            </b-button>

                            <b-button size="xs" variant="warning" title="Descargar registro" @click="descargar(row.item.id)">
                                <i class="fa fa-file-pdf-o"></i>
                            </b-button>

                            <b-button size="xs" variant="danger" title="Eliminar registro" @click="borrar(row.item.id)">
                                <i class="fa fa-trash"></i>
                            </b-button>
                        </template>
                    </b-table>
                </b-card>
            </b-col>
        </b-row>

        <b-modal ref="modal_orden_compra" :title="modal_orden_compra.titulo" size="xl" no-close-on-backdrop scrollable static>
            <b-form>
                <div class="card">
                    <div class="card-header bg-info text-right">
                        <h6 class="m-b-0 text-white">Proveedor de Servicios</h6>
                    </div>
                    <div class="card-body">
                        <b-row>
                            <b-col xs="12" sm="12" md="6">
                                <b-form-group label="Razón Social " label-cols-md="3" label-cols-lg="3" class="mb-1">
                                    <vue-bootstrap-typeahead
                                        ref="typeahead_proveedor"
                                        :data="proveedores"
                                        v-model="$v.orden_compra.proveedor_nombre.$model"
                                        :serializer="p => p.nombre"
                                        placeholder="Escribe para filtrar ..."
                                        @hit="proveedor($event)"
                                        :state="$v.orden_compra.proveedor_nombre.$dirty ? !$v.orden_compra.proveedor_nombre.$error : null"
                                        aria-describedby="orden-compra-proveedor-nombre"
                                    />

                                    <b-form-invalid-feedback id="orden-compra-proveedor-nombre">
                                        Campo de texto, mínimo de 3 caracteres.
                                    </b-form-invalid-feedback>
                                </b-form-group>
                            </b-col>
                            <b-col xs="12" sm="12" md="6">
                                <b-form-group label="Rut " label-cols-md="3" label-cols-lg="3" class="mb-1">
                                    <b-form-input
                                        v-rut:live
                                        v-model="$v.orden_compra.proveedor_rut.$model"
                                        :state="$v.orden_compra.proveedor_rut.$dirty ? !$v.orden_compra.proveedor_rut.$error : null"
                                        aria-describedby="orden-compra-proveedor-rut"
                                        :readonly="modal_orden_compra.accion == 2"
                                    ></b-form-input>

                                    <b-form-invalid-feedback id="orden-compra-proveedor-rut">
                                        Campo de alfanúmerico, mínimo de 3 caracteres.
                                    </b-form-invalid-feedback>
                                </b-form-group>
                            </b-col>
                            <b-col xs="12" sm="12" md="6">
                                <b-form-group label="Giro " label-cols-md="3" label-cols-lg="3" class="mb-1">
                                    <b-form-input
                                        v-model="$v.orden_compra.proveedor_giro.$model"
                                        :state="$v.orden_compra.proveedor_giro.$dirty ? !$v.orden_compra.proveedor_giro.$error : null"
                                        aria-describedby="orden-compra-proveedor-giro"
                                    ></b-form-input>

                                    <b-form-invalid-feedback id="orden-compra-proveedor-giro">
                                        Campo de texto, mínimo de 3 caracteres.
                                    </b-form-invalid-feedback>
                                </b-form-group>
                            </b-col>
                            <b-col xs="12" sm="12" md="6">
                                <b-form-group label="Teléfono " label-cols-md="3" label-cols-lg="3" class="mb-1">
                                    <b-form-input
                                        v-model="$v.orden_compra.proveedor_telefono.$model"
                                        :state="$v.orden_compra.proveedor_telefono.$dirty ? !$v.orden_compra.proveedor_telefono.$error : null"
                                        aria-describedby="orden-compra-proveedor-telefono"
                                    ></b-form-input>

                                    <b-form-invalid-feedback id="orden-compra-proveedor-telefono">
                                        Campo de númerico, mínimo de 3 caracteres.
                                    </b-form-invalid-feedback>
                                </b-form-group>
                            </b-col>
                            <b-col xs="12" sm="12" md="6">
                                <b-form-group label="Dirección " label-cols-md="3" label-cols-lg="3" class="mb-1">
                                    <b-form-input
                                        v-model="$v.orden_compra.proveedor_direccion.$model"
                                        :state="$v.orden_compra.proveedor_direccion.$dirty ? !$v.orden_compra.proveedor_direccion.$error : null"
                                        aria-describedby="orden-compra-proveedor-direccion"
                                    ></b-form-input>

                                    <b-form-invalid-feedback id="orden-compra-proveedor-direccion">
                                        Campo de alfanúmerico, mínimo de 3 caracteres.
                                    </b-form-invalid-feedback>
                                </b-form-group>
                            </b-col>
                            <b-col xs="12" sm="12" md="6">
                                <b-form-group label="Comuna " label-cols-md="3" label-cols-lg="3" class="mb-1">
                                    <b-form-input
                                        v-model="$v.orden_compra.proveedor_comuna.$model"
                                        :state="$v.orden_compra.proveedor_comuna.$dirty ? !$v.orden_compra.proveedor_comuna.$error : null"
                                        aria-describedby="orden-compra-proveedor-comuna"
                                    ></b-form-input>

                                    <b-form-invalid-feedback id="orden-compra-proveedor-comuna">
                                        Campo de texto, mínimo de 3 caracteres.
                                    </b-form-invalid-feedback>
                                </b-form-group>
                            </b-col>
                            <b-col xs="12" sm="12" md="6">
                                <b-form-group label="Correo " label-cols-md="3" label-cols-lg="3" class="mb-1">
                                    <b-form-input
                                        v-model="$v.orden_compra.proveedor_correo.$model"
                                        :state="$v.orden_compra.proveedor_correo.$dirty ? !$v.orden_compra.proveedor_correo.$error : null"
                                        aria-describedby="orden-compra-proveedor-correo"
                                    ></b-form-input>

                                    <b-form-invalid-feedback id="orden-compra-proveedor-correo">
                                        Campo de alfanúmerico, mínimo de 3 caracteres y formato de email.
                                    </b-form-invalid-feedback>
                                </b-form-group>
                            </b-col>
                            <b-col xs="12" sm="12" md="6">
                                <b-form-group label="Contacto " label-cols-md="3" label-cols-lg="3" class="mb-1">
                                    <b-form-input
                                        v-model="$v.orden_compra.proveedor_referencia.$model"
                                        :state="$v.orden_compra.proveedor_referencia.$dirty ? !$v.orden_compra.proveedor_referencia.$error : null"
                                        aria-describedby="orden-compra-proveedor-referencia"
                                    ></b-form-input>

                                    <b-form-invalid-feedback id="orden-compra-proveedor-referencia">
                                        Campo de texto, mínimo de 3 caracteres.
                                    </b-form-invalid-feedback>
                                </b-form-group>
                            </b-col>
                        </b-row>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header bg-info text-right">
                        <h6 class="m-b-0 text-white">Detalle orden de Compra</h6>
                    </div>
                    <div class="card-body">
                        <b-row>
                            <b-col xs="12" sm="12" md="4">
                                <b-form-group label="Asunto " label-cols-md="3" label-cols-lg="3">
                                    <b-form-input
                                        v-model="$v.orden_compra.asunto.$model"
                                        :state="$v.orden_compra.asunto.$dirty ? !$v.orden_compra.asunto.$error : null"
                                        aria-describedby="orden-compra-asunto"
                                    ></b-form-input>

                                    <b-form-invalid-feedback id="orden-compra-asunto">
                                        Campo de alfanúmerico, mínimo de 3 caracteres.
                                    </b-form-invalid-feedback>
                                </b-form-group>
                            </b-col>
                            <b-col xs="12" sm="12" md="4">
                                <b-form-group label="Fecha " label-cols-md="3" label-cols-lg="3">
                                    <b-form-input
                                        v-model="$v.orden_compra.fecha.$model"
                                        :state="$v.orden_compra.fecha.$dirty ? !$v.orden_compra.fecha.$error : null"
                                        aria-describedby="orden-compra-fecha"
                                        readonly
                                    ></b-form-input>
                                </b-form-group>
                            </b-col>
                            <b-col xs="12" sm="12" md="4">
                                <b-form-group label=" N° Orden " label-cols-md="3" label-cols-lg="3">
                                    <b-form-input
                                        v-model="$v.orden_compra.numero.$model"
                                        :state="$v.orden_compra.numero.$dirty ? !$v.orden_compra.numero.$error : null"
                                        aria-describedby="orden-compra-numero"
                                        readonly
                                    ></b-form-input>
                                </b-form-group>
                            </b-col>
                        </b-row>
                        <b-row align-v="center">
                            <b-col>
                                <b-form-group>
                                    <b-table show-empty small striped outlined stacked="sm" :items="orden_compra.detalle_orden" :fields="orden_compra_detalle">
                                        <template v-slot:empty="scope">
                                            <center><h6>No hay registros</h6></center>
                                        </template>

                                        <template v-slot:cell(index)="data">
                                            {{ data.index + 1 }}
                                        </template>

                                        <template v-slot:cell(descripcion)="data">
                                            <b-form-group class="mb-0">
                                                <vue-bootstrap-typeahead
                                                :ref="'typeahead_detalle[' + data.index + ']'"
                                                size="sm"
                                                :data="productos"
                                                v-model="$v.orden_compra.detalle_orden.$each[data.index].producto_nombre.$model"
                                                :serializer="p => p.nombre"
                                                placeholder="Escribe para filtrar ..."
                                                @hit="producto($event, data.index)"
                                                    :state="$v.orden_compra.detalle_orden.$each[data.index].producto_nombre.$dirty ? !$v.orden_compra.detalle_orden.$each[data.index].producto_nombre.$error : null"
                                                    :aria-describedby="'detalle-orden-producto-nombre' + data.index"/>

                                                <b-form-invalid-feedback :id="'detalle-orden-producto-nombre' + data.index">
                                                    Campo de texto, mínimo de 3 caracteres.
                                                </b-form-invalid-feedback>
                                            </b-form-group>
                                        </template>

                                        <template v-slot:cell(cantidad)="data">
                                            <b-form-group class="mb-0">
                                                <b-form-input
                                                    size="sm"
                                                    v-model="$v.orden_compra.detalle_orden.$each[data.index].cantidad.$model"
                                                    :state="$v.orden_compra.detalle_orden.$each[data.index].cantidad.$dirty ? !$v.orden_compra.detalle_orden.$each[data.index].cantidad.$error : null"
                                                    :aria-describedby="'detalle-orden-cantidad' + data.index"
                                                    @keyup="calcular_total_cantidad(data.index)"
                                                ></b-form-input>
                                                <b-form-invalid-feedback :id="'detalle-orden-cantidad' + data.index">
                                                    Campo númerico, valor mínimo 0.
                                                </b-form-invalid-feedback>
                                            </b-form-group>
                                        </template>

                                        <template v-slot:cell(valor_unitario)="data">
                                            <b-form-group class="mb-0">
                                                <b-form-input
                                                    size="sm"
                                                    v-model="$v.orden_compra.detalle_orden.$each[data.index].valor_unitario.$model"
                                                    :state="$v.orden_compra.detalle_orden.$each[data.index].valor_unitario.$dirty ? !$v.orden_compra.detalle_orden.$each[data.index].valor_unitario.$error : null"
                                                    :aria-describedby="'detalle-orden-valor_unitario' + data.index"
                                                ></b-form-input>

                                                <b-form-invalid-feedback :id="'detalle-orden-valor_unitario' + data.index">
                                                    Campo númerico, valor mínimo 1.
                                                </b-form-invalid-feedback>
                                            </b-form-group>
                                        </template>

                                        <template v-slot:cell(total)="data">
                                            {{ data.item.total | currency }}
                                        </template>

                                        <template v-slot:cell(acciones)="row">
                                            <b-button size="xs" variant="success" title="Agregar fila" @click="agregar_fila">
                                                <i class="fa fa-plus"></i>
                                            </b-button>

                                            <b-button size="xs" variant="danger" title="Eliminar fila" @click="eliminar_fila(row.index)" v-show="row.index > 0">
                                                <i class="fa fa-trash"></i>
                                            </b-button>
                                        </template>

                                    </b-table>
                                </b-form-group>
                            </b-col>
                        </b-row>
                        <b-row>
                            <b-col cols="4">
                                <b-form-group label="NETO " label-cols-md="3" label-cols-lg="3" class="mb-1">
                                    <b-form-input
                                        v-model="$v.orden_compra.neto.$model"
                                        :state="$v.orden_compra.neto.$dirty ? !$v.orden_compra.neto.$error : null"
                                        aria-describedby="orden-compra-neto"
                                        readonly
                                    ></b-form-input>
                                </b-form-group>
                            </b-col>
                            <b-col cols="4">
                                <b-form-group label="IVA(19%) " label-cols-md="3" label-cols-lg="3" class="mb-1">
                                    <b-form-input
                                        v-model="$v.orden_compra.iva.$model"
                                        :state="$v.orden_compra.iva.$dirty ? !$v.orden_compra.iva.$error : null"
                                        aria-describedby="orden-compra-iva"
                                        readonly
                                    ></b-form-input>
                                </b-form-group>
                            </b-col>
                            <b-col cols="4">
                                <b-form-group label="Total " label-cols-md="3" label-cols-lg="3" class="mb-1">
                                    <b-form-input
                                        v-model="$v.orden_compra.total.$model"
                                        :state="$v.orden_compra.total.$dirty ? !$v.orden_compra.total.$error : null"
                                        aria-describedby="orden-compra-total"
                                        readonly
                                    ></b-form-input>
                                </b-form-group>
                            </b-col>
                            <b-col cols="12">
                                <b-form-group label="Observación " class="mb-1">
                                    <b-form-textarea
                                        v-model="$v.orden_compra.observacion.$model"
                                        :state="$v.orden_compra.observacion.$dirty ? !$v.orden_compra.observacion.$error : null"
                                        aria-describedby="orden-compra-observacion"
                                        rows="3"
                                        max-rows="6"
                                        placeholder="Sin onbservación."
                                    ></b-form-textarea>

                                    <b-form-invalid-feedback id="orden-compra-observacion">
                                        Campo de alfanúmerico, mínimo de 3 caracteres.
                                    </b-form-invalid-feedback>
                                </b-form-group>
                            </b-col>
                        </b-row>
                    </div>
                </div>

            </b-form>

            <template slot="modal-footer">
                <b-button :disabled="$v.orden_compra.$invalid" size="md" variant="success" @click="crear_orden_compra(1)" v-if="usuario && usuario.email"> Guardar y enviar </b-button>
                <b-button :disabled="$v.orden_compra.$invalid" size="md" variant="success" @click="crear_orden_compra()"> Guardar </b-button>
                <b-button size="md" variant="danger" @click="cerrar_modal_orden_compra"> Cerrar </b-button>
            </template>
        </b-modal>

        <div class="fixed-bottom mb-5 mr-5">
            <b-button pill variant="success" size="lg" class="pull-right heigth-50" @click="abrir_modal_orden_compra"><i class="ti-plus"></i></b-button>
        </div>
    </div>
</template>

<script>
    import { required, minLength, email, numeric, requiredIf, minValue  } from 'vuelidate/lib/validators'
    import { mapMutations, mapGetters, mapState } from 'vuex'

    export default {
        data() {
            return {
                items: [],
                proveedores: [],
                productos: [],
                fields: [
                    { key: 'id', label: '#', sortable: true, class: 'text-center' },
                    { key: 'proveedor_nombre', label: 'Proveedor', sortable: true, class: 'text-left' },
                    { key: 'asunto', label: 'Asunto', sortable: true, class: 'text-left' },
                    { key: 'total', label: 'Total', sortable: true, class: 'text-left' },
                    { key: 'emisor', label: 'Emisor', sortable: true, class: 'text-left' },
                    { key: 'created_at', label: 'Fecha', sortable: true, class: 'text-left' },
                    { key: 'acciones', label: 'Acciones', class: 'text-center'}
                ],
                orden_compra_detalle: [
                    { key: 'index', label: '#', sortable: true, class: 'text-center' },
                    { key: 'descripcion', label: 'Descripción', sortable: true, class: 'text-left' },
                    { key: 'cantidad', label: 'Cantidad', sortable: true, class: 'text-left' },
                    { key: 'valor_unitario', label: 'Valor unitario', sortable: true, class: 'text-left' },
                    { key: 'medida', label: 'Medida', sortable: true, class: 'text-left' },
                    { key: 'total', label: 'Total', sortable: true, class: 'text-left' },
                    { key: 'acciones', label: 'Acción', sortable: true, class: 'text-center' }
                ],
                totalRows: 1,
                currentPage: 1,
                perPage: 15,
                pageOptions: [15, 50, 100, 150, 200, 150],
                sortBy: '',
                sortDesc: false,
                filter: null,
                modal_orden_compra: {
                    titulo: '',
                    accion: 0
                },
                orden_compra: {
                    proveedor_id: 0,
                    proveedor_nombre: '',
                    proveedor_rut: '',
                    proveedor_giro: '',
                    proveedor_telefono: '',
                    proveedor_direccion: '',
                    proveedor_comuna: '',
                    proveedor_correo: '',
                    proveedor_referencia: '',
                    fecha: '',
                    numero: '',
                    asunto: '',
                    neto: 0,
                    iva: 0,
                    total: 0,
                    observacion: '',
                    detalle_orden: []
                },
                gastos: {
                    diario: 0,
                    semanal: 0,
                    mensual: 0,
                    anual: 0
                }
            }
        },
        validations:{
            orden_compra: {
                proveedor_nombre: {
                    required,
                    minLength: minLength(3)
                },
                proveedor_rut: {
                    required,
                    minLength: minLength(3)
                },
                proveedor_giro: {
                    required,
                    minLength: minLength(3)
                },
                proveedor_telefono: {
                    required,
                    numeric
                },
                proveedor_direccion: {
                    required,
                    minLength: minLength(3)
                },
                proveedor_comuna: {
                    required,
                    minLength: minLength(3)
                },
                proveedor_correo: {
                    required,
                    minLength: minLength(3),
                    email
                },
                proveedor_referencia: {

                },
                fecha: {
                    required
                },
                numero: {

                },
                asunto: {
                    required,
                    minLength: minLength(3),
                },
                neto: {

                },
                total: {

                },
                iva:{

                },
                observacion: {

                },
                detalle_orden: {
                    $each: {
                        id: {

                        },
                        producto_nombre: {

                        },
                        cantidad: {
                            required,
                            numeric
                        },
                        valor_unitario: {
                            required,
                            minValue: minValue(1),
                            numeric
                        }
                    }
                }
            }

        },
        computed: {
            ...mapState('usuario', ['usuario']),
            sortOptions() {
                return this.fields.filter(f => f.sortable).map(f => {
                    return { text: f.label, value: f.key }
                })
            }
        },
        methods: {
            ...mapMutations(['msg_success', 'msg_error']),
            numero_orden(){

                let max = 0;
                    this.items.forEach(item => {
                    if (item.id > max) {
                        max = item.id;
                    }
                });

                this.orden_compra.numero = max + 1

            },
            fecha_actual(){
                var f = new Date();
                this.orden_compra.fecha = f.getFullYear() + "-" + (f.getMonth() +1) + "-" + f.getDate()
            },
            onFiltered(filteredItems) {
                this.totalRows = filteredItems.length
                this.currentPage = 1
            },
            proveedor(e){
                this.orden_compra.proveedor_id = e.id
                this.orden_compra.proveedor_nombre = e.nombre
                this.orden_compra.proveedor_rut = e.rut
                this.orden_compra.proveedor_giro = e.giro
                this.orden_compra.proveedor_telefono = e.telefono
                this.orden_compra.proveedor_direccion = e.direccion
                this.orden_compra.proveedor_comuna = e.comuna
                this.orden_compra.proveedor_correo = e.correo
                this.orden_compra.proveedor_referencia = e.referencia
            },
            producto(e, index){
                this.orden_compra.detalle_orden[index].id = e.id
                this.orden_compra.detalle_orden[index].producto_nombre = e.nombre
                this.orden_compra.detalle_orden[index].valor_unitario = e.valor_actual
                this.orden_compra.detalle_orden[index].medida = e.unidad

                if(this.orden_compra.detalle_orden[index].cantidad > 0){
                    this.calcular_total_cantidad(index)
                }

            },
            calcular_total_cantidad(index){
                this.orden_compra.detalle_orden[index].total = this.orden_compra.detalle_orden[index].valor_unitario * this.orden_compra.detalle_orden[index].cantidad

                this.calcular_total()
            },
            calcular_total(){
                let total = 0;

                this.orden_compra.detalle_orden.forEach(item => {
                    total += parseInt(item.total)
                })

                this.orden_compra.neto = total
                this.orden_compra.iva = parseInt(total * 0.19)
                this.orden_compra.total = parseInt(this.orden_compra.neto + this.orden_compra.iva)
            },
            listar_proveedores(){
                let me = this

                axios.get('/proveedores/1').then(function (response) {
                    me.proveedores = response.data.proveedores
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            listar_orden_compras(){
                let me = this

                axios.get('/ordenes/1').then(function (response) {
                    me.items = response.data.ordenes
                    me.gastos.diario = response.data.gasto_diario
                    me.gastos.semanal = response.data.gasto_semanal
                    me.gastos.mensual = response.data.gasto_mensual
                    me.gastos.anual = response.data.gasto_anual

                    me.totalRows = me.items.length;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            listar_productos_inventario(){
                let me = this

                axios.get('/inventario/1').then(function (response) {
                    me.productos = response.data.productos
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            agregar_fila(){
                var fila = new Object()
                fila.id = 0
                fila.producto_nombre = ""
                fila.cantidad = null
                fila.medida = null
                fila.valor_unitario = 0
                fila.total = 0

                this.orden_compra.detalle_orden.push(fila)
            },
            eliminar_fila(index){
                this.orden_compra.detalle_orden.splice(index, 1);
            },
            obtener_registros(){
                this.listar_proveedores()
                this.listar_orden_compras()
                this.listar_productos_inventario()
                this.fecha_actual()
            },
            abrir_modal_orden_compra(data = [], accion = 0) {
                let me = this

                me.numero_orden()
                me.limpiar_datos_orden_compra()
                me.modal_orden_compra.titulo = data.id == undefined ? "Agregar orden compra" : "Modificar orden compra"

                this.$refs['modal_orden_compra'].show()

                if(accion == 1){
                    me.orden_compra.proveedor_id = data.proveedor_id
                    me.orden_compra.proveedor_nombre = data.proveedor_nombre
                    me.orden_compra.proveedor_rut = data.proveedor_rut
                    me.orden_compra.proveedor_giro = data.proveedor_giro
                    me.orden_compra.proveedor_telefono = data.proveedor_telefono
                    me.orden_compra.proveedor_direccion = data.proveedor_direccion
                    me.orden_compra.proveedor_comuna = data.proveedor_comuna
                    me.orden_compra.proveedor_correo = data.proveedor_correo
                    me.orden_compra.proveedor_referencia = data.proveedor_referencia
                    me.orden_compra.asunto = data.asunto
                    me.orden_compra.fecha = data.fecha
                    me.orden_compra.observacion = data.observacion

                    me.$refs.typeahead_proveedor.inputValue = data.proveedor_nombre

                    this.$v.orden_compra.$touch(true)

                    let productos_nombre = data.descripcion.split('@')
                    let cantidades = data.cantidad.split('@')
                    let medidas = data.unidad_medida.split('@')
                    let valores_unitario = data.valor_unitario.split('@')

                    for (var i = 0; i < productos_nombre.length; i++) {
                        var fila = new Object()

                        var producto = me.productos.find(function(p) {
                            return p.nombre == productos_nombre[i] && p.unidad == medidas[i]
                        })

                        fila.id = producto ? producto.id : 0
                        fila.producto_nombre = productos_nombre[i]
                        fila.cantidad = cantidades[i]
                        fila.medida = medidas[i]
                        fila.valor_unitario = valores_unitario[i]

                        me.orden_compra.detalle_orden.push(fila)
                        me.calcular_total_cantidad(i)
                    }

                    setTimeout(function(){ me.cambiar_nombres(productos_nombre) },2000);

                } else {
                    this.agregar_fila()
                }
            },
            cambiar_nombres(data = []){
                for(var i = 0; i < data.length; i++){
                    var nombre = 'typeahead_detalle[' + i + ']'
                    this.$refs[nombre].inputValue = data[i]
                }
            },
            cerrar_modal_orden_compra() {
                this.modal_orden_compra.titulo = ""
                this.modal_orden_compra.accion = 0
                this.$refs['modal_orden_compra'].hide()
            },
            limpiar_datos_orden_compra() {
                this.orden_compra.proveedor_id = 0
                this.orden_compra.proveedor_nombre = ''
                this.orden_compra.proveedor_rut = ''
                this.orden_compra.proveedor_giro = ''
                this.orden_compra.proveedor_telefono = ''
                this.orden_compra.proveedor_direccion = ''
                this.orden_compra.proveedor_comuna = ''
                this.orden_compra.proveedor_correo = ''
                this.orden_compra.proveedor_referencia = ''
                this.orden_compra.asunto = ''
                this.orden_compra.neto = 0
                this.orden_compra.iva = 0
                this.orden_compra.total = 0
                this.orden_compra.observacion = ''

                this.orden_compra.detalle_orden = []

                this.$refs.typeahead_proveedor.inputValue = ""

                this.$v.$reset();
            },
            crear_orden_compra(accion = 0) {
                if(this.$v.orden_compra.$invalid){
                    this.$v.orden_compra.$touch()
                    return
                }

                let me = this

                axios.post('/orden/crear',{
                    'accion': accion,
                    'proveedor_id': me.orden_compra.proveedor_id,
                    'asunto': me.orden_compra.asunto,
                    'fecha': me.orden_compra.fecha,
                    'proveedor_nombre': me.orden_compra.proveedor_nombre,
                    'proveedor_rut': me.orden_compra.proveedor_rut,
                    'proveedor_giro': me.orden_compra.proveedor_giro,
                    'proveedor_telefono': me.orden_compra.proveedor_telefono,
                    'proveedor_direccion': me.orden_compra.proveedor_direccion,
                    'proveedor_comuna': me.orden_compra.proveedor_comuna,
                    'proveedor_correo': me.orden_compra.proveedor_correo,
                    'proveedor_referencia': me.orden_compra.proveedor_referencia,
                    'detalle_orden': me.orden_compra.detalle_orden,
                    'total': me.orden_compra.detalle_orden.total,
                    'neto': me.orden_compra.neto,
                    'iva': me.orden_compra.iva,
                    'total': me.orden_compra.total,
                    'observacion': me.orden_compra.observacion
                }).then(function (response) {
                    me.$refs.typeahead_proveedor.inputValue = ""
                    me.$refs['typeahead_detalle[0]'].inputValue = ""
                    me.obtener_registros()
                    me.numero_orden()
                    me.$store.commit('msg_success', accion == 0 ? 'Registro agregado exitosamente.' :  'Registro enviado y agregado exitosamente.')
                    me.limpiar_datos_orden_compra()
                    me.agregar_fila()

                }).catch(function (error) {
                    me.$store.commit('msg_error', accion == 0 ? 'Problemas al agregar el registro.' :  'Problemas al enviar y agregar el registro.')
                    console.log(error)
                })
            },
            borrar(id) {
                swal.fire({
                    title: '¿Deseas anular el registro?',
                    text: "¡No podrás revertir esto!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Sí, ¡bórralo!'
                }).then((result) => {
                    if (result.value) {
                        let me = this
                        axios.post('/orden/borrar',{
                            'id': id
                        }).then(function (response) {
                            me.obtener_registros()
                            me.$store.commit('msg_success', 'Registro anulado exitosamente.')
                        }).catch(function (error) {
                            console.log(error);
                        })
                    }
                })
            },
            enviar_mail(id) {
                swal.fire({
                    title: '¿Deseas enviar el registro al correo?',
                    text: "¡No podrás revertir esto!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Sí, ¡enviar!'
                }).then((result) => {
                    if (result.value) {
                        let me = this
                        axios.post('/orden/enviar',{
                            'id': id
                        }).then(function (response) {
                            me.obtener_registros()
                            me.$store.commit('msg_success', 'Registro enviado exitosamente.')
                        }).catch(function (error) {
                            console.log(error);
                        })
                    }
                })
            },
            descargar(id) {
                swal.fire({
                    title: '¿Deseas descargar el registro?',
                    text: "¡No podrás revertir esto!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Sí, descargar!'
                }).then((result) => {
                    if (result.value) {
                        let me = this

                        axios.get('/orden/descargar/' + id, {responseType: 'blob'}).then(function (response){
                            const url = window.URL.createObjectURL(new Blob([response.data]))
                            const link = document.createElement('a')
                            link.href = url
                            link.setAttribute('download', 'OrdenDeCompra.pdf')
                            document.body.appendChild(link)
                            link.click()
                            me.$store.commit('msg_success', 'Registro descargado exitosamente.')
                        }).catch(function (error) {
                            console.log(error);
                        })
                    }
                })
            }
        },
        mounted() {
            this.obtener_registros()
        }
    }
</script>
