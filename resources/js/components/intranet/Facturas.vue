<template>
    <div>
        <titulo-pagina titulo="Facturas"></titulo-pagina>

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
                        <template v-slot:empty>
                            <center><h5>No hay registros</h5></center>
                        </template>

                        <template v-slot:cell(index)="data">
                            {{ data.index + 1 }}
                        </template>

                        <template v-slot:cell(proveedor)="data">
                            {{ data.item.proveedor.nombre }}
                        </template>

                        <template v-slot:cell(orden_compra)="data">
                            N° {{ data.item.orden_compra.id }}
                        </template>

                        <template v-slot:cell(total)="data">
                            {{ data.item.orden_compra.total | currency }}
                        </template>

                        <template v-slot:cell(centro_costo)="row">
                            {{ row.item.lugar ? row.item.lugar.nombre : 'Desconocido' }}
                        </template>


                        <template v-slot:cell(acciones)="row">
                            <b-button size="xs" variant="success" title="Actualizar factura" @click="abrir_modal_factura(row.item)">
                                <i class="fa fa-plus"></i>
                            </b-button>
                        </template>
                    </b-table>
                </b-card>
            </b-col>
        </b-row>

        <b-modal ref="modal_factura" :title="modal_factura.titulo" size="xl" no-close-on-backdrop scrollable static>
            <b-form>
                <div class="card">
                    <div class="card-header bg-info text-right">
                        <h6 class="m-b-0 text-white">Datos de factura</h6>
                    </div>
                    <div class="card-body">
                        <b-row>
                            <b-col xs="12" sm="12" md="6">
                                <b-form-group label="Fecha de factura" label-cols-md="3" label-cols-lg="3" class="mb-1">
                                    <b-form-input
                                        v-model="$v.factura.fecha.$model"
                                        :state="$v.factura.fecha.$dirty ? !$v.factura.fecha.$error : null"
                                        aria-describedby="factura-fecha"
                                        type="date"
                                    ></b-form-input>

                                    <b-form-invalid-feedback id="factura-fecha">
                                        Ingresa una fecha válida.
                                    </b-form-invalid-feedback>
                                </b-form-group>
                            </b-col>
                            <b-col xs="12" sm="12" md="6">
                                <b-form-group label="Número de factura" label-cols-md="3" label-cols-lg="3" class="mb-1">
                                    <b-form-input
                                        v-model="$v.factura.numero_factura.$model"
                                        :state="$v.factura.numero_factura.$dirty ? !$v.factura.numero_factura.$error : null"
                                        aria-describedby="factura-numero-factura"
                                    ></b-form-input>

                                    <b-form-invalid-feedback id="factura-numero-factura">
                                        Campo de númerico, valor mínimo 1.
                                    </b-form-invalid-feedback>
                                </b-form-group>
                            </b-col>
                        </b-row>
                    </div>
                </div>


                <div class="card">
                    <div class="card-header bg-info text-right">
                        <h6 class="m-b-0 text-white">Detalle</h6>
                    </div>
                    <div class="card-body">
                    <b-row align-v="center">
                        <b-col>
                            <b-form-group>
                                <b-table show-empty small striped outlined stacked="sm" :items="factura.detalle" :fields="orden_compra_detalle">
                                    <template v-slot:empty>
                                        <center><h6>No hay registros</h6></center>
                                    </template>

                                    <template v-slot:cell(index)="data">
                                        {{ data.index + 1 }}
                                    </template>

                                    <template v-slot:cell(total)="data">
                                        {{ data.item.total | currency }}
                                    </template>

                                    <template v-slot:cell(acciones)="row">
                                        <b-button v-if="row.item.estado == 0" size="xs" variant="success" title="Sumar stock de producto al inventario" @click="sumar_restar_inventario(1, row.item.producto_nombre, row.item.cantidad, row.index)">
                                            <i class="fa fa-check"></i>
                                        </b-button>

                                        <b-button v-else size="xs" variant="danger" title="Elimnar stock de producto del inventario" @click="sumar_restar_inventario(2, row.item.producto_nombre, row.item.cantidad, row.index)">
                                            <i class="fa fa-trash"></i>
                                        </b-button>
                                    </template>

                                </b-table>
                            </b-form-group>
                        </b-col>
                    </b-row>
                    <b-row>
                        <b-col cols="12">
                            <b-form-group label="Observación " class="mb-1">
                                <b-form-textarea
                                    v-model="$v.factura.observacion.$model"
                                    :state="$v.factura.observacion.$dirty ? !$v.factura.observacion.$error : null"
                                    aria-describedby="factura-observacion"
                                    rows="3"
                                    max-rows="6"
                                    placeholder="Sin onbservación."
                                ></b-form-textarea>

                                <b-form-invalid-feedback id="factura-observacion">
                                    Campo de alfanúmerico, mínimo de 3 caracteres.
                                </b-form-invalid-feedback>
                            </b-form-group>
                        </b-col>
                    </b-row>
                    </div>
                </div>

            </b-form>

            <template slot="modal-footer">
                <b-button :disabled="$v.factura.$invalid" size="md" variant="success" @click="crear_actualizar_factura"> Guardar </b-button>
                <b-button size="md" variant="danger" @click="cerrar_modal_factura"> Cerrar </b-button>
            </template>
        </b-modal>
    </div>
</template>

<script>
    import { required, minValue, minLength, between, numeric } from 'vuelidate/lib/validators'
    import { mapMutations, mapGetters } from 'vuex'

    export default {
        data() {
            return {
                items: [],
                fields: [
                    { key: 'index', label: '#', sortable: true, class: 'text-center' },
                    { key: 'proveedor', label: 'Proveedor', sortable: true, class: 'text-left' },
                    { key: 'orden_compra', label: 'Orden de compra', sortable: true, class: 'text-left' },
                    { key: 'numero_factura', label: 'Número factura', sortable: true, class: 'text-left' },
                    { key: 'centro_costo', label: 'Centro de costo', sortable: true, class: 'text-left' },
                    { key: 'fecha', label: 'Fecha', sortable: true, class: 'text-left' },
                    { key: 'total', label: 'Total (con IVA)', sortable: true, class: 'text-left' },
                    { key: 'acciones', label: 'Acciones', class: 'text-center'}
                ],
                orden_compra_detalle: [
                    { key: 'index', label: '#', sortable: true, class: 'text-center' },
                    { key: 'producto_nombre', label: 'Descripción', sortable: true, class: 'text-left' },
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
                modal_factura: {
                    titulo: '',
                    accion: 0
                },
                factura: {
                    id: 0,
                    fecha: '',
                    numero_factura: '',
                    detalle: [],
                    observacion: ''
                },
                orden_compra: {
                    id: 0
                }
            }
        },
        validations:{
            factura: {
                fecha: {
                    required
                },
                numero_factura:{
                    required,
                    numeric,
                    minValue: minValue(0)
                },
                observacion: {

                }
            }
        },
        computed: {
            sortOptions() {
                return this.fields.filter(f => f.sortable).map(f => {
                    return { text: f.label, value: f.key }
                })
            }
        },
        methods: {
            ...mapMutations(['msg_success', 'msg_error']),
            onFiltered(filteredItems) {
                this.totalRows = filteredItems.length
                this.currentPage = 1
            },
            listar_facturas(){
                let me = this

                axios.get('/facturas/1').then(function (response) {
                    me.items = response.data.facturas
                    me.totalRows = me.items.length;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            abrir_modal_factura(data = []) {
                let me = this

                me.limpiar_datos_factura()
                me.modal_factura.titulo = "Actualizar datos de factura"

                me.factura.id = data.id
                me.factura.fecha = data.fecha
                me.factura.numero_factura = data.numero_factura
                me.factura.observacion = data.observacion

                me.orden_compra.id = data.orden_compra.id

                var productos = data.orden_compra.descripcion.split("@")
                var cantidades = data.orden_compra.cantidad.split("@")
                var valores_unitarios = data.orden_compra.unidad_medida.split("@")
                var totales = data.orden_compra.valor_unitario.split("@")
                var estados = data.orden_compra.estado_producto.split("@")

                for (let i = 0; i < productos.length; i++) {
                    var fila = new Object()
                    fila.producto_nombre = productos[i]
                    fila.cantidad = cantidades[i]
                    fila.medida = valores_unitarios[i]
                    fila.valor_unitario = productos[i]
                    fila.total = totales[i]
                    fila.estado = estados[i]
                    me.factura.detalle.push(fila)
                }

                this.$v.factura.$touch(true)

                this.$refs['modal_factura'].show()
            },
            cerrar_modal_factura() {
                this.modal_factura.titulo = ""
                this.$refs['modal_factura'].hide()
            },
            limpiar_datos_factura() {
                this.factura.id = 0
                this.factura.fecha = ''
                this.factura.numero_factura = ''
                this.factura.detalle = []
                this.factura.observacion = ''

                this.orden_compra.id = 0

                this.$v.$reset();
            },
            crear_actualizar_factura() {
                if(this.$v.factura.$invalid){
                    this.$v.factura.$touch()
                    return
                }

                let me = this

                axios.post('/factura/crear/actualizar',{
                        'id': me.factura.id,
                        'fecha': me.factura.fecha,
                        'numero_factura': me.factura.numero_factura,
                        'observacion': me.factura.observacion
                    }).then(function (response) {
                        me.listar_facturas()
                        me.$store.commit('msg_success', 'Registro actualizado exitosamente.')
                    }).catch(function (error) {
                        console.log(error)
                })
            },
            sumar_restar_inventario(accion, producto, cantidad, index) {
                swal.fire({
                    title: accion == 1 ? '¿Deseas sumar al inventario?' : '¿Deseas restar del inventario?',
                    text: "¡No podrás revertir esto!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: accion == 1 ? 'Sí, ¡sumar!' : 'Sí, ¡restar!'
                }).then((result) => {
                    if (result.value) {
                        let me = this

                        axios.post('/orden/restar/sumar',{
                            'id': me.orden_compra.id,
                            'accion': accion,
                            'producto': producto,
                            'cantidad': cantidad
                        }).then(function (response) {
                            me.factura.detalle[index].estado = me.factura.detalle[index].estado == 1 ? 0 : 1
                            me.listar_facturas();
                            me.$store.commit('msg_success', accion == 1 ? 'Producto sumado al inventario.' : 'Producto restado del stock')
                        }).catch(function (error) {
                            console.log(error);
                        })
                    }
                })
            }
        },
        mounted() {
            this.listar_facturas()
        }
    }
</script>
