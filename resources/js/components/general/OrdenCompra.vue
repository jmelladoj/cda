<template>
    <span>
        <b-button size="xs" variant="success" title="Generar orden de compra" @click="abrir_modal_orden_compra">
            <i class="fa fa-plus"></i>
        </b-button>

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
    </span>
</template>

<script>
    import { required, minLength, email, numeric, requiredIf, minValue  } from 'vuelidate/lib/validators'
    import { mapMutations, mapGetters, mapState } from 'vuex'

    export default {
        props: [
            'id_proveedor'
        ],
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
                me.modal_orden_compra.titulo = "Agregar orden compra"

                this.$refs['modal_orden_compra'].show()

                if(me.id_proveedor != undefined){
                    var proveedor = me.proveedores.find(function(p) {
                        return p.id == me.id_proveedor
                    })

                    me.orden_compra.proveedor_id = proveedor.id
                    me.orden_compra.proveedor_nombre = proveedor.nombre
                    me.orden_compra.proveedor_rut = proveedor.rut
                    me.orden_compra.proveedor_giro = proveedor.giro
                    me.orden_compra.proveedor_telefono = proveedor.telefono
                    me.orden_compra.proveedor_direccion = proveedor.direccion
                    me.orden_compra.proveedor_comuna = proveedor.comuna
                    me.orden_compra.proveedor_correo = proveedor.correo
                    me.orden_compra.proveedor_referencia = proveedor.referencia

                    me.$refs.typeahead_proveedor.inputValue = proveedor.nombre

                    this.$v.orden_compra.$touch(true)
                }

                this.agregar_fila()
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
                    me.cerrar_modal_orden_compra()

                }).catch(function (error) {
                    me.$store.commit('msg_error', accion == 0 ? 'Problemas al agregar el registro.' :  'Problemas al enviar y agregar el registro.')
                    console.log(error)
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
                            me.cerrar_modal_orden_compra()
                            me.$store.commit('msg_success', 'Registro enviado exitosamente.')
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

