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
                        <template v-slot:empty>
                            <center><h5>No hay registros</h5></center>
                        </template>

                        <template v-slot:cell(total)="row">
                            {{ row.item.total | currency }}
                        </template>

                        <template v-slot:cell(lugar_nombre)="row">
                            {{ row.item.lugar.nombre }}
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

        <div class="fixed-bottom mb-5 mr-5">
            <b-button pill variant="success" size="lg" class="pull-right heigth-50" @click="abrir_modal_orden_compra(null)"><i class="ti-plus"></i></b-button>
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
                    { key: 'lugar_nombre', label: 'Centro de costo', sortable: true, class: 'text-left' },
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
            abrir_modal_orden_compra(id_proveedor){
                Evento.$emit('cargar_modal_orden_compra', id_proveedor);
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
            obtener_registros(){
                this.listar_proveedores()
                this.listar_orden_compras()
                this.listar_productos_inventario()
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
