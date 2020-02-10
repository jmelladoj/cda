<template>
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
                                    v-model="$v.proveedor.nombre.$model"
                                    :serializer="p => p.nombre"
                                    placeholder="Escribe para filtrar ..."
                                    @hit="proveedor_seleccionado($event)"
                                    :state="$v.proveedor.nombre.$dirty ? !$v.proveedor.nombre.$error : null"
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
                                    v-model="$v.proveedor.rut.$model"
                                    :state="$v.proveedor.rut.$dirty ? !$v.proveedor.rut.$error : null"
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
                                    v-model="$v.proveedor.giro.$model"
                                    :state="$v.proveedor.giro.$dirty ? !$v.proveedor.giro.$error : null"
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
                                    v-model="$v.proveedor.telefono.$model"
                                    :state="$v.proveedor.telefono.$dirty ? !$v.proveedor.telefono.$error : null"
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
                                    v-model="$v.proveedor.direccion.$model"
                                    :state="$v.proveedor.direccion.$dirty ? !$v.proveedor.direccion.$error : null"
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
                                    v-model="$v.proveedor.comuna.$model"
                                    :state="$v.proveedor.comuna.$dirty ? !$v.proveedor.comuna.$error : null"
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
                                    v-model="$v.proveedor.correo.$model"
                                    :state="$v.proveedor.correo.$dirty ? !$v.proveedor.correo.$error : null"
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
                                    v-model="$v.proveedor.referencia.$model"
                                    :state="$v.proveedor.referencia.$dirty ? !$v.proveedor.referencia.$error : null"
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

            <div class="card" v-for="(o,index) in ordenes_compra" :key="index">
                <div class="card-header bg-info text-right">
                    <b-row>
                        <b-col>
                            <h6 class="m-b-0 text-white">Detalle orden de Compra {{ index + 1 }} </h6>
                        </b-col>
                        <b-col cols="1" class="text-rigth">
                            <b-button size="xs" variant="success" title="Agregar orden de compra" @click="agregar_orden_compra(index)">
                                <i class="fa fa-plus"></i>
                            </b-button>
                        </b-col>
                        <b-col cols="1" class="text-left">
                            <b-button v-show="index > 0" size="xs" variant="danger" title="Eliminar orden de compra" @click="eliminar_orden_compra(index)">
                                <i class="fa fa-remove"></i>
                            </b-button>
                        </b-col>
                    </b-row>
                </div>
                <div class="card-body">
                    <b-row>
                        <b-col xs="12" sm="12" md="3">
                            <b-form-group label="Centro de costo " label-cols-md="3" label-cols-lg="3">
                                <b-form-select
                                    v-model="$v.ordenes_compra.$each[index].centro_costo_id.$model"
                                    :state="$v.ordenes_compra.$each[index].centro_costo_id.$dirty ? !$v.ordenes_compra.$each[index].centro_costo_id.$error : null"
                                    aria-describedby="ordenes-compra-centro-costo"
                                    :options="opciones_centro_costos">
                                </b-form-select>

                                <b-form-invalid-feedback id="ordenes-compra-centro-costo">
                                    Campo de alfanúmerico, mínimo de 3 caracteres.
                                </b-form-invalid-feedback>
                            </b-form-group>
                        </b-col>
                        <b-col xs="12" sm="12" md="3">
                            <b-form-group label="Asunto " label-cols-md="3" label-cols-lg="3">
                                <b-form-input
                                    v-model="$v.ordenes_compra.$each[index].asunto.$model"
                                    :state="$v.ordenes_compra.$each[index].asunto.$dirty ? !$v.ordenes_compra.$each[index].asunto.$error : null"
                                    aria-describedby="ordenes-compra-asunto"
                                ></b-form-input>

                                <b-form-invalid-feedback id="ordenes-compra-asunto">
                                    Campo de alfanúmerico, mínimo de 3 caracteres.
                                </b-form-invalid-feedback>
                            </b-form-group>
                        </b-col>
                        <b-col xs="12" sm="12" md="3">
                            <b-form-group label="Fecha " label-cols-md="3" label-cols-lg="3">
                                <b-form-input
                                    v-model="$v.ordenes_compra.$each[index].fecha.$model"
                                    :state="$v.ordenes_compra.$each[index].fecha.$dirty ? !$v.ordenes_compra.$each[index].fecha.$error : null"
                                    aria-describedby="orden-compra-fecha"
                                    readonly
                                ></b-form-input>
                            </b-form-group>
                        </b-col>
                        <b-col xs="12" sm="12" md="3">
                            <b-form-group label=" N° Orden " label-cols-md="3" label-cols-lg="3">
                                <b-form-input
                                    :value="parseInt(num_orden_compra) + parseInt(index + 1)"
                                    readonly
                                ></b-form-input>
                            </b-form-group>
                        </b-col>
                    </b-row>
                    <b-row align-v="center">
                        <b-col>
                            <b-form-group>
                                <b-table show-empty small striped outlined stacked="sm" :items="ordenes_compra[index].detalle" :fields="orden_compra_detalle">
                                    <template v-slot:empty>
                                        <center><h6>No hay registros</h6></center>
                                    </template>

                                    <template v-slot:cell(index)="data">
                                        {{ data.index + 1 }}
                                    </template>

                                    <template v-slot:cell(descripcion)="data">
                                        <b-form-group class="mb-0">
                                            <vue-bootstrap-typeahead
                                            :ref="'typeahead_detalle_'+ index +'[' + data.index + ']'"
                                            size="sm"
                                            :data="productos"
                                            v-model="$v.ordenes_compra.$each[index].detalle.$each[data.index].producto_nombre.$model"
                                            :serializer="p => p.nombre"
                                            placeholder="Escribe para filtrar ..."
                                            @hit="producto($event, index ,data.index)"
                                                :state="$v.ordenes_compra.$each[index].detalle.$each[data.index].producto_nombre.$dirty ? !$v.ordenes_compra.$each[index].detalle.$each[data.index].producto_nombre.$error : null"
                                                :aria-describedby="'detalle-orden-producto-nombre' + index + '-' + data.index"/>

                                            <b-form-invalid-feedback :id="'detalle-orden-producto-nombre' + index + '-' + data.index">
                                                Campo de texto, mínimo de 3 caracteres.
                                            </b-form-invalid-feedback>
                                        </b-form-group>
                                    </template>

                                    <template v-slot:cell(cantidad)="data">
                                        <b-form-group class="mb-0">
                                            <b-form-input
                                                size="sm"
                                                v-model="$v.ordenes_compra.$each[index].detalle.$each[data.index].cantidad.$model"
                                                :state="$v.ordenes_compra.$each[index].detalle.$each[data.index].cantidad.$dirty ? !$v.ordenes_compra.$each[index].detalle.$each[data.index].cantidad.$error : null"
                                                :aria-describedby="'detalle-orden-cantidad' + data.index"
                                                @keyup="calcular_total_cantidad(index ,data.index)"
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
                                                v-model="$v.ordenes_compra.$each[index].detalle.$each[data.index].valor_unitario.$model"
                                                :state="$v.ordenes_compra.$each[index].detalle.$each[data.index].valor_unitario.$dirty ? !$v.ordenes_compra.$each[index].detalle.$each[data.index].valor_unitario.$error : null"
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
                                        <b-button size="xs" variant="success" title="Agregar fila" @click="agregar_fila(index)">
                                            <i class="fa fa-plus"></i>
                                        </b-button>

                                        <b-button size="xs" variant="danger" title="Eliminar fila" @click="eliminar_fila(index, row.index)" v-show="row.index > 0">
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
                                    v-model="$v.ordenes_compra.$each[index].neto.$model"
                                    :state="$v.ordenes_compra.$each[index].neto.$dirty ? !$v.ordenes_compra.$each[index].neto.$error : null"
                                    aria-describedby="orden-compra-neto"
                                    readonly
                                ></b-form-input>
                            </b-form-group>
                        </b-col>
                        <b-col cols="4">
                            <b-form-group label="IVA(19%) " label-cols-md="3" label-cols-lg="3" class="mb-1">
                                <b-form-input
                                    v-model="$v.ordenes_compra.$each[index].iva.$model"
                                    :state="$v.ordenes_compra.$each[index].iva.$dirty ? !$v.ordenes_compra.$each[index].iva.$error : null"
                                    aria-describedby="orden-compra-iva"
                                    readonly
                                ></b-form-input>
                            </b-form-group>
                        </b-col>
                        <b-col cols="4">
                            <b-form-group label="Total " label-cols-md="3" label-cols-lg="3" class="mb-1">
                                <b-form-input
                                    v-model="$v.ordenes_compra.$each[index].total.$model"
                                    :state="$v.ordenes_compra.$each[index].total.$dirty ? !$v.ordenes_compra.$each[index].total.$error : null"
                                    aria-describedby="orden-compra-total"
                                    readonly
                                ></b-form-input>
                            </b-form-group>
                        </b-col>
                        <b-col cols="12">
                            <b-form-group label="Observación " class="mb-1">
                                <b-form-textarea
                                    v-model="$v.ordenes_compra.$each[index].observacion.$model"
                                    :state="$v.ordenes_compra.$each[index].observacion.$dirty ? !$v.ordenes_compra.$each[index].observacion.$error : null"
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
            <b-spinner v-model="spinner.estado" variant="info" label="Spinning" v-show="spinner.estado == 1"></b-spinner>
            <b-button v-show="spinner.estado == 0" :disabled="$v.ordenes_compra.$invalid || $v.proveedor.$invalid" size="md" variant="success" @click="crear_orden_compra(1)" v-if="usuario && usuario.email"> Guardar y enviar </b-button>
            <b-button v-show="spinner.estado == 0" :disabled="$v.ordenes_compra.$invalid || $v.proveedor.$invalid" size="md" variant="success" @click="crear_orden_compra()"> Guardar </b-button>
            <b-button size="md" variant="danger" @click="cerrar_modal_orden_compra"> Cerrar </b-button>
        </template>
    </b-modal>
</template>

<script>
    import { required, minLength, email, numeric, requiredIf, minValue  } from 'vuelidate/lib/validators'
    import { mapMutations, mapGetters, mapState } from 'vuex'

    export default {
        data() {
            return {
                num_orden_compra: null,
                proveedores: [],
                productos: [],
                opciones_centro_costos: [],
                ordenes_compra: [],
                orden_compra_detalle: [
                    { key: 'index', label: '#', sortable: true, class: 'text-center' },
                    { key: 'descripcion', label: 'Descripción', sortable: true, class: 'text-left' },
                    { key: 'cantidad', label: 'Cantidad', sortable: true, class: 'text-left' },
                    { key: 'valor_unitario', label: 'Valor unitario', sortable: true, class: 'text-left' },
                    { key: 'medida', label: 'Medida', sortable: true, class: 'text-left' },
                    { key: 'total', label: 'Total', sortable: true, class: 'text-left' },
                    { key: 'acciones', label: 'Acción', sortable: true, class: 'text-center' }
                ],
                spinner: {
                    estado: 0
                },
                modal_orden_compra: {
                    titulo: '',
                    accion: 0
                },
                proveedor: {
                    id: null,
                    nombre: '',
                    rut: '',
                    giro: '',
                    telefono: '',
                    direccion: '',
                    comuna: '',
                    correo: '',
                    referencia: ''
                }
            }
        },
        validations:{
            proveedor: {
                nombre: {
                    required,
                    minLength: minLength(3)
                },
                rut: {
                    required,
                    minLength: minLength(3)
                },
                giro: {
                    required,
                    minLength: minLength(3)
                },
                telefono: {
                    required,
                    numeric
                },
                direccion: {
                    required,
                    minLength: minLength(3)
                },
                comuna: {
                    required,
                    minLength: minLength(3)
                },
                correo: {
                    required,
                    minLength: minLength(3),
                    email
                },
                referencia: {

                }
            },
            ordenes_compra: {
                $each: {
                    centro_costo_id: {
                        required
                    },
                    asunto: {
                        required,
                        minLength: minLength(3)
                    },
                    fecha: {
                        required
                    },
                    detalle: {
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
                    },
                    neto: {
                        required,
                        numeric
                    },
                    total: {
                        required,
                        numeric
                    },
                    iva:{
                        required,
                        numeric
                    },
                    observacion: {

                    },
                }
            }
        },
        computed: {
            ...mapState('usuario', ['usuario'])
        },
        methods: {
            ...mapMutations(['msg_success', 'msg_error']),
            agregar_orden_compra(index){
                var oc = new Object();
                oc.centro_costo_id = null
                oc.asunto = ''
                oc.fecha = this.fecha_actual()
                oc.detalle = []
                oc.neto = 0
                oc.iva = 0
                oc.total = 0
                oc.observacion = 'Sin observaciones.'

                this.ordenes_compra.push(oc)

                this.agregar_fila(this.ordenes_compra.length - 1)
            },
            eliminar_orden_compra(index){
                this.ordenes_compra.splice(index, 1);
            },
            fecha_actual(){
                var f = new Date();
                return f.getFullYear() + "-" + (f.getMonth() +1) + "-" + f.getDate()
            },
            proveedor_seleccionado(e){
                this.proveedor.id = e.id
                this.proveedor.nombre = e.nombre
                this.proveedor.rut = e.rut
                this.proveedor.giro = e.giro
                this.proveedor.telefono = e.telefono
                this.proveedor.direccion = e.direccion
                this.proveedor.comuna = e.comuna
                this.proveedor.correo = e.correo
                this.proveedor.referencia = e.referencia
            },
            producto(e, index_orden, index_fila){
                this.ordenes_compra[index_orden].detalle[index_fila].id = e.id
                this.ordenes_compra[index_orden].detalle[index_fila].producto_nombre = e.nombre
                this.ordenes_compra[index_orden].detalle[index_fila].valor_unitario = e.valor_actual
                this.ordenes_compra[index_orden].detalle[index_fila].medida = e.unidad

                if(this.ordenes_compra[index_orden].detalle[index_fila].cantidad > 0){
                    this.calcular_total_cantidad(index_orden, index_fila)
                }
            },
            calcular_total_cantidad(index_orden, index_fila){
                this.ordenes_compra[index_orden].detalle[index_fila].total = this.ordenes_compra[index_orden].detalle[index_fila].valor_unitario * this.ordenes_compra[index_orden].detalle[index_fila].cantidad

                this.calcular_total(index_orden)
            },
            calcular_total(index_orden){
                let total = 0;

                this.ordenes_compra[index_orden].detalle.forEach(item => {
                    total += parseInt(item.total)
                })

                this.ordenes_compra[index_orden].neto = total
                this.ordenes_compra[index_orden].iva = parseInt(total * 0.19)
                this.ordenes_compra[index_orden].total = parseInt(this.ordenes_compra[index_orden].neto + this.ordenes_compra[index_orden].iva)
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

                axios.get('/ordenes/3').then(function (response) {
                    me.num_orden_compra = response.data.num_orden
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
            listar_centro_costos(){
                let me = this

                axios.get('/lugares/1').then(function (response) {
                    me.lugares = response.data.lugares

                    me.opciones_centro_costos = []

                    var item = new Object()
                    item.value = null
                    item.text = 'Selecciona una opción...'
                    me.opciones_centro_costos.push(item)

                    response.data.lugares.forEach( function(lugar, index) {
                        var item = new Object()
                        item.value = lugar.id
                        item.text = lugar.nombre
                        me.opciones_centro_costos.push(item)
                    })

                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            agregar_fila(index){
                var fila = new Object()
                fila.id = 0
                fila.producto_nombre = ""
                fila.cantidad = null
                fila.medida = null
                fila.valor_unitario = 0
                fila.total = 0

                this.ordenes_compra[index].detalle.push(fila)
            },
            eliminar_fila(index_oc, index_fila){
                this.ordenes_compra[index_oc].detalle.splice(index_fila, 1);
            },
            obtener_registros(){
                this.listar_proveedores()
                this.listar_orden_compras()
                this.listar_productos_inventario()
                this.fecha_actual()
                this.listar_centro_costos()
            },
            abrir_modal_orden_compra(data = [], accion = 0) {
                let me = this

                me.limpiar_datos_orden_compra()
                me.agregar_orden_compra()
                me.modal_orden_compra.titulo = "Agregar orden compra"

                this.$refs['modal_orden_compra'].show()

                if(me.proveedor.id != null || me.proveedor.id > 0){
                    let pro = me.proveedores.find(function(p) {
                        return p.id == parseInt(me.proveedor.id)
                    })

                    if(pro){
                        me.proveedor.id = pro.id
                        me.proveedor.nombre = pro.nombre
                        me.proveedor.rut = pro.rut
                        me.proveedor.giro = pro.giro
                        me.proveedor.telefono = pro.telefono
                        me.proveedor.direccion = pro.direccion
                        me.proveedor.comuna = pro.comuna
                        me.proveedor.correo = pro.correo
                        me.proveedor.referencia = pro.referencia

                        me.$refs.typeahead_proveedor.inputValue = me.proveedor.nombre

                        this.$v.proveedor.$touch(true)
                    }

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
                this.proveedor.id = null
                this.$refs['modal_orden_compra'].hide()
            },
            limpiar_datos_orden_compra() {
                this.ordenes_compra = []
                this.proveedor.nombre = ''
                this.proveedor.rut = ''
                this.proveedor.giro = ''
                this.proveedor.telefono = ''
                this.proveedor.direccion = ''
                this.proveedor.comuna = ''
                this.proveedor.correo = ''
                this.proveedor.referencia = ''

                this.spinner.estado = 0

                this.$refs.typeahead_proveedor.inputValue = ""

                this.$v.proveedor.$reset();
                this.$v.ordenes_compra.$reset();
            },
            crear_orden_compra(accion = 0) {
                //if(this.$v.ordenes_compra.$invalid && $v.proveedor.$invalid){
                //    this.$v.ordenes_compra.$touch()
                //    this.$v.proveedor.touch()
                //    return
                //}

                let me = this

                me.spinner.estado = 1

                axios.post('/orden/crear',{
                    'accion': accion,
                    'id': me.proveedor.id,
                    'nombre': me.proveedor.nombre,
                    'rut': me.proveedor.rut,
                    'giro': me.proveedor.giro,
                    'telefono': me.proveedor.telefono,
                    'direccion': me.proveedor.direccion,
                    'comuna': me.proveedor.comuna,
                    'correo': me.proveedor.correo,
                    'referencia': me.proveedor.referencia,
                    'ordenes_compra': me.ordenes_compra
                }).then(function (response) {
                    me.$refs.typeahead_proveedor.inputValue = ""
                    me.obtener_registros()
                    me.$store.commit('msg_success', accion == 0 ? 'Registro agregado exitosamente.' :  'Registro enviado y agregado exitosamente.')
                    me.limpiar_datos_orden_compra()
                    me.agregar_orden_compra()
                }).catch(function (error) {
                    me.$store.commit('msg_error', accion == 0 ? 'Problemas al agregar el registro.' :  'Problemas al enviar y agregar el registro.')
                    me.spinner.estado = 0
                    console.log(error)
                })
            }
        },
        mounted() {
            Evento.$on('cargar_modal_orden_compra', (id_proveedor) => {
                this.obtener_registros()
                this.proveedor.id = id_proveedor
                this.abrir_modal_orden_compra()
            })
        }
    }
</script>

