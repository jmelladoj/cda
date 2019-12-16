<template>
    <div>
        <titulo-pagina titulo="Productos inventario"></titulo-pagina>

        <b-row>
            <b-col>
                <b-card>
                    <b-row>
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
                            <b-form-group label="Orden inicial" label-cols-sm="2" label-align-sm="left" label-size="sm" label-for="initialSortSelect" class="mb-0">
                                <b-form-select v-model="sortDirection" id="initialSortSelect" size="sm">
                                    <option value="asc">Ascendente</option>
                                    <option value="desc">Descendente</option>
                                    <option value="last">Último</option>
                                </b-form-select>
                            </b-form-group>
                        </b-col>

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
                            <b-form-group label="Filtrar en" label-cols-sm="2" label-align-sm="left" label-size="sm" class="mb-0">
                                <b-form-checkbox-group v-model="filterOn" class="mt-1">
                                    <b-form-checkbox value="nombre">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Nombre</b-form-checkbox>
                                </b-form-checkbox-group>
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

                    <b-table class="my-3" show-empty small striped outlined stacked="md" :tbody-tr-class="clase_fila" :items="items" :fields="fields" :current-page="currentPage" :per-page="perPage" :filter="filter" :filterIncludedFields="filterOn" :sort-by.sync="sortBy" :sort-desc.sync="sortDesc" :sort-direction="sortDirection" @filtered="onFiltered" >
                        <template v-slot:empty="scope">
                            <center><h5>No hay registros</h5></center>
                        </template>

                        <template v-slot:cell(index)="data">
                            {{ data.index + 1 }}
                        </template>

                        <template v-slot:cell(valor_actual)="data">
                            <div class="text-danger" v-if="data.item.valor_actual > data.item.valor_ultimo && data.item.valor_ultimo != 0">{{ data.item.valor_actual | currency }} <i class="ml-2 ti-arrow-up"></i></div>
                            <div class="text-success" v-else-if="data.item.valor_actual < data.item.valor_ultimo && data.item.valor_ultimo != 0">{{ data.item.valor_actual | currency }} <i class="ml-2 ti-arrow-down"></i></div>
                            <div v-else>{{ data.item.valor_actual | currency }}</div>
                        </template>

                        <template v-slot:cell(valor_ultimo)="data" variant="danger">
                            {{ data.item.valor_ultimo | currency }}
                        </template>


                        <template v-slot:cell(acciones)="row">
                            <b-button size="xs" variant="info" title="Ingresar stock" @click="abrir_modal_productos_ingreso(row.item)">
                                I
                            </b-button>

                            <b-button size="xs" variant="info" title="Salida de stock" @click="abrir_modal_productos_salida(row.item)">
                                S
                            </b-button>

                            <b-button size="xs" variant="warning" title="Actualizar información" @click="abrir_modal_productos_inventario(row.item)">
                                <i class="fa fa-pencil"></i>
                            </b-button>

                            <b-button size="xs" variant="danger" title="Eliminar registro" @click="borrar(row.item.id)">
                                <i class="fa fa-trash"></i>
                            </b-button>
                        </template>

                        <template v-slot:row-details="row">
                            <b-card>
                                <ul>
                                    <li v-for="(value, key) in row.item" :key="key">{{ key }}: {{ value }}</li>
                                </ul>
                            </b-card>
                        </template>
                    </b-table>
                </b-card>
            </b-col>
        </b-row>

        <b-modal ref="modal_productos_inventario" :title="modal_productos_inventario.titulo" no-close-on-backdrop>
            <b-form>
                <b-form-group label="Nombre de producto">
                    <b-form-input
                        v-model="$v.producto_inventario.nombre.$model"
                        :state="$v.producto_inventario.nombre.$dirty ? !$v.producto_inventario.nombre.$error : null"
                        aria-describedby="producto-nombre"
                    ></b-form-input>

                    <b-form-invalid-feedback id="producto-nombre">
                        Campo de alfanúmerico, mínimo de 3 caracteres.
                    </b-form-invalid-feedback>
                </b-form-group>

                <b-row>
                    <b-col>
                        <b-form-group label="Stock de producto">
                            <b-form-input
                                v-model="$v.producto_inventario.stock.$model"
                                :state="$v.producto_inventario.stock.$dirty ? !$v.producto_inventario.stock.$error : null"
                                aria-describedby="producto-stock"
                            ></b-form-input>

                            <b-form-invalid-feedback id="producto-stock">
                                Campo númerico, valor mínimo 1.
                            </b-form-invalid-feedback>
                        </b-form-group>
                    </b-col>
                    <b-col>
                        <b-form-group label="Stock crítico">
                            <b-form-input
                                v-model="$v.producto_inventario.stock_critico.$model"
                                :state="$v.producto_inventario.stock_critico.$dirty ? !$v.producto_inventario.stock_critico.$error : null"
                                aria-describedby="producto-stock-critico"
                            ></b-form-input>

                            <b-form-invalid-feedback id="producto-stock-critico">
                                Campo númerico, valor mínimo 1.
                            </b-form-invalid-feedback>
                        </b-form-group>
                    </b-col>
                </b-row>

                <b-row>
                    <b-col>
                        <b-form-group label="Valor actual de producto">
                            <b-form-input
                                v-model="$v.producto_inventario.valor_actual.$model"
                                :state="$v.producto_inventario.valor_actual.$dirty ? !$v.producto_inventario.valor_actual.$error : null"
                                aria-describedby="producto-valor-actual"
                            ></b-form-input>

                            <b-form-invalid-feedback id="producto-valor-actual">
                                Campo númerico, valor mínimo 1.
                            </b-form-invalid-feedback>
                        </b-form-group>
                    </b-col>
                    <b-col>
                        <b-form-group label="Último valor de producto">
                            <b-form-input
                                v-model="$v.producto_inventario.valor_ultimo.$model"
                                :state="$v.producto_inventario.valor_ultimo.$dirty ? !$v.producto_inventario.valor_ultimo.$error : null"
                                aria-describedby="producto-valor-ultimo"
                            ></b-form-input>

                            <b-form-invalid-feedback id="producto-valor-ultimo">
                                Campo númerico, valor mínimo 0.
                            </b-form-invalid-feedback>
                        </b-form-group>
                    </b-col>
                </b-row>
            </b-form>

            <template slot="modal-footer">
                <b-button :disabled="$v.producto_inventario.$invalid" v-show="modal_productos_inventario.accion == 1" size="md" variant="success" @click="crear_actualizar_lugar"> Guardar </b-button>
                <b-button :disabled="$v.producto_inventario.$invalid" v-show="modal_productos_inventario.accion == 2" size="md" variant="warning" @click="crear_actualizar_lugar"> Actualizar </b-button>
                <b-button size="md" variant="danger" @click="cerrar_modal_productos_inventario"> Cerrar </b-button>
            </template>
        </b-modal>

        <b-modal ref="modal_productos_ingreso" :title="modal_productos_ingreso.titulo" no-close-on-backdrop>
            <b-form>
                <b-row>
                    <b-col>
                        <b-form-group label="Cantidad de ingreso">
                            <b-form-input
                                v-model="$v.ingreso_producto.cantidad_ingreso.$model"
                                :state="$v.ingreso_producto.cantidad_ingreso.$dirty ? !$v.ingreso_producto.cantidad_ingreso.$error : null"
                                aria-describedby="ingreso-cantidad-stock"
                            ></b-form-input>

                            <b-form-invalid-feedback id="ingreso-cantidad-stock">
                                Campo númerico, valor mínimo 1.
                            </b-form-invalid-feedback>
                        </b-form-group>
                    </b-col>
                    <b-col>
                        <b-form-group label="Valor unitario de ingreso">
                            <b-form-input
                                v-model="$v.ingreso_producto.valor_ingreso.$model"
                                :state="$v.ingreso_producto.valor_ingreso.$dirty ? !$v.ingreso_producto.valor_ingreso.$error : null"
                                aria-describedby="ingreso-valor-unitario-ingreso"
                            ></b-form-input>

                            <b-form-invalid-feedback id="ingreso-valor-unitario-ingreso">
                                Campo númerico, valor mínimo 1.
                            </b-form-invalid-feedback>
                        </b-form-group>
                    </b-col>
                </b-row>
                <b-row>
                    <b-col>
                        <b-form-group label="Detalle de ingresos">
                            <b-table show-empty small striped hover outlined :items="items_ingreso" :fields="fields_ingreso">
                                <template v-slot:empty="scope">
                                    <center><h6>No hay registros</h6></center>
                                </template>

                                <template v-slot:cell(index)="data">
                                    {{ data.index + 1 }}
                                </template>
                            </b-table>
                        </b-form-group>
                    </b-col>
                </b-row>
            </b-form>

            <template slot="modal-footer">
                <b-button :disabled="$v.ingreso_producto.$invalid" size="md" variant="success" @click="crear_actualizar_lugar"> Ingresar </b-button>
                <b-button size="md" variant="danger" @click="cerrar_modal_productos_ingreso"> Cerrar </b-button>
            </template>
        </b-modal>

        <b-modal ref="modal_productos_salida" :title="modal_productos_salida.titulo" no-close-on-backdrop>
            <b-form>
                <b-form-group label="Cantidad de retiro">
                    <b-form-input
                        v-model="$v.salida_producto.cantidad_salida.$model"
                        :state="$v.salida_producto.cantidad_salida.$dirty ? !$v.salida_producto.cantidad_salida.$error : null"
                        aria-describedby="salida-cantidad-retiro"
                    ></b-form-input>

                    <b-form-invalid-feedback id="salida-cantidad-retiro">
                        Campo númerico, valor mínimo 1.
                    </b-form-invalid-feedback>
                </b-form-group>


                <b-row>
                    <b-col>
                        <b-form-group label="Valor actual de producto">
                            <b-form-input
                                v-model="$v.producto_inventario.valor_actual.$model"
                                :state="$v.producto_inventario.valor_actual.$dirty ? !$v.producto_inventario.valor_actual.$error : null"
                                aria-describedby="producto-valor-actual"
                            ></b-form-input>

                            <b-form-invalid-feedback id="producto-valor-actual">
                                Campo númerico, valor mínimo 1.
                            </b-form-invalid-feedback>
                        </b-form-group>
                    </b-col>
                    <b-col>
                        <b-form-group label="Último valor de producto">
                            <b-form-input
                                v-model="$v.producto_inventario.valor_ultimo.$model"
                                :state="$v.producto_inventario.valor_ultimo.$dirty ? !$v.producto_inventario.valor_ultimo.$error : null"
                                aria-describedby="producto-valor-ultimo"
                            ></b-form-input>

                            <b-form-invalid-feedback id="producto-valor-ultimo">
                                Campo númerico, valor mínimo 0.
                            </b-form-invalid-feedback>
                        </b-form-group>
                    </b-col>
                </b-row>

                <b-row>
                    <b-col>
                        <b-form-group label="Lugares">
                            <b-table show-empty small striped hover outlined :items="items_ingreso" :fields="fields_ingreso">
                                <template v-slot:empty="scope">
                                    <center><h6>No hay registros</h6></center>
                                </template>

                                <template v-slot:cell(index)="data">
                                    {{ data.index + 1 }}
                                </template>
                            </b-table>
                        </b-form-group>
                    </b-col>
                </b-row>
            </b-form>

            <template slot="modal-footer">
                <b-button :disabled="$v.salida_producto.$invalid" size="md" variant="success" @click="crear_actualizar_lugar"> Retirar </b-button>
                <b-button size="md" variant="danger" @click="cerrar_modal_productos_salida"> Cerrar </b-button>
            </template>
        </b-modal>

        <div class="fixed-bottom mb-5 mr-5">
            <b-button pill variant="success" size="lg" class="pull-right heigth-50" @click="abrir_modal_productos_inventario"><i class="ti-plus"></i></b-button>
        </div>
    </div>
</template>

<script>
    import { required, minLength, minValue, numeric, decimal } from 'vuelidate/lib/validators'
    import { mapMutations, mapGetters } from 'vuex'

    export default {
        data() {
            return {
                items: [],
                items_ingreso: [],
                lugares: [],
                tabla_salida: [],
                fields: [
                    { key: 'index', label: '#', sortable: true, class: 'text-center' },
                    { key: 'nombre', label: 'Nombre', sortable: true, class: 'text-left' },
                    { key: 'stock', label: 'Stock', sortable: true, class: 'text-left' },
                    { key: 'stock_critico', label: 'Stock crítico', sortable: true, class: 'text-left' },
                    { key: 'valor_actual', label: 'Valor actual', sortable: true, class: 'text-left' },
                    { key: 'valor_ultimo', label: 'Último valor', sortable: true, class: 'text-left' },
                    { key: 'acciones', label: 'Acciones', class: 'text-center'}
                ],
                fields_ingreso: [
                    { key: 'index', label: '#', sortable: true, class: 'text-center' },
                    { key: 'detalle', label: 'Detalle', sortable: true, class: 'text-left' },
                    { key: 'encargado', label: 'Usuario', sortable: true, class: 'text-left' },
                ],
                totalRows: 1,
                currentPage: 1,
                perPage: 15,
                pageOptions: [15, 50, 100, 150, 200, 150],
                sortBy: '',
                sortDesc: false,
                sortDirection: 'asc',
                filter: null,
                filterOn: [],
                modal_productos_inventario: {
                    titulo: '',
                    accion: 0
                },
                modal_productos_ingreso: {
                    titulo: ''
                },
                modal_productos_salida: {
                    titulo: ''
                },
                producto_inventario: {
                    id: 0,
                    nombre: '',
                    stock: null,
                    stock_critico: null,
                    valor_actual: null,
                    valor_ultimo: 0
                },
                ingreso_producto: {
                    cantidad_ingreso: null,
                    valor_ingreso: null
                },
                salida_producto:{
                    cantidad_salida: null
                }
            }
        },
        validations:{
            producto_inventario: {
                nombre: {
                    required,
                    minLength: minLength(4)
                },
                stock: {
                    required,
                    numeric,
                    minValue: minValue(1)
                },
                stock_critico: {
                    required,
                    minValue: minValue(1)
                },
                valor_actual: {
                    required,
                    minValue: minValue(1)
                },
                valor_ultimo: {
                    required,
                    minValue: minValue(0)
                }
            },
            ingreso_producto: {
                cantidad_ingreso: {
                    required,
                    minValue: minValue(1)
                },
                valor_ingreso: {
                    required,
                    minValue: minValue(1)
                }
            },
            salida_producto: {
                cantidad_salida: {
                    required,
                    minValue: minValue(1)
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
            clase_fila(item, type) {
                if (!item) return
                if (item.stock < item.stock_critico) return 'table-danger'
            },
            onFiltered(filteredItems) {
                this.totalRows = filteredItems.length
                this.currentPage = 1
            },
            obtener_registros(){
                this.listar_productos_inventario()
                this.listar_lugares()
            },
            listar_lugares(){
                let me = this

                axios.get('/lugares/1').then(function (response) {
                    me.items = response.data.lugares
                    me.totalRows = me.items.length

                    me.tabla_salida = []

                    me.lugares.forEach( function(lugar) {
                        var fila = new Object()
                        fila.lugar_id = lugar.id
                        fila.nombre = lugar.nombre
                        fila.cantidad_retiro = 0
                        me.tabla_salida.push(fila)
                    })
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            listar_productos_inventario(){
                let me = this

                axios.get('/inventario/1').then(function (response) {
                    me.items = response.data.productos
                    me.totalRows = me.items.length;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            abrir_modal_productos_inventario(data = []) {
                let me = this

                me.limpiar_datos_producto_inventario()
                me.modal_productos_inventario.titulo = data.id == undefined ? "Agregar producto" : "Modificar producto"
                me.modal_productos_inventario.accion = data.id == undefined ? 1 : 2

                if(me.modal_productos_inventario.accion == 2){
                    me.producto_inventario.id = data.id
                    me.producto_inventario.nombre = data.nombre
                    me.producto_inventario.stock = data.stock
                    me.producto_inventario.stock_critico = data.stock_critico
                    me.producto_inventario.valor_actual = data.valor_actual
                    me.producto_inventario.valor_ultimo = data.valor_ultimo
                }

                this.$refs['modal_productos_inventario'].show()
            },
            cerrar_modal_productos_inventario() {
                this.modal_productos_inventario.titulo = ""
                this.modal_productos_inventario.accion = 0
                this.$refs['modal_productos_inventario'].hide()
            },
            abrir_modal_productos_ingreso(data = []){
                this.modal_productos_ingreso.titulo = "Ingresar stock a : " + data.nombre

                this.ingreso_producto.valor_ingreso = data.valor_actual

                this.$refs['modal_productos_ingreso'].show()
            },
            abrir_modal_productos_salida(data = []){
                this.modal_productos_salida.titulo = "Retirar stock de : " + data.nombre
                this.$refs['modal_productos_salida'].show()
            },
            cerrar_modal_productos_ingreso() {
                this.$refs['modal_productos_ingreso'].hide()
            },
            cerrar_modal_productos_salida() {
                this.$refs['modal_productos_salida'].hide()
            },
            limpiar_datos_producto_inventario() {
                this.producto_inventario.id = 0
                this.producto_inventario.nombre = ''
                this.producto_inventario.stock = null
                this.producto_inventario.stock_critico = null
                this.producto_inventario.valor_actual = null
                this.producto_inventario.valor_ultimo = 0

                this.$v.$reset();
            },
            crear_actualizar_lugar() {
                if(this.$v.producto_inventario.$invalid){
                    this.$v.producto_inventario.$touch()
                    return
                }

                let me = this

                axios.post('/inventario/crear/actualizar',{
                        'id': me.producto_inventario.id,
                        'nombre': me.producto_inventario.nombre,
                        'stock': me.producto_inventario.stock,
                        'stock_critico': me.producto_inventario.stock_critico,
                        'valor_actual': me.producto_inventario.valor_actual,
                        'valor_ultimo': me.producto_inventario.valor_ultimo
                    }).then(function (response) {
                        me.obtener_registros()
                        me.$store.commit('msg_success', me.producto_inventario.id == 0 ? 'Registro agregado exitosamente.' :  'Registro actualizado exitosamente.')

                        if(me.producto_inventario.id == 0){
                            me.limpiar_datos_producto_inventario()
                        }

                    }).catch(function (error) {
                        console.log(error)
                })
            },
            borrar(id) {
                swal.fire({
                    title: '¿Deseas borrar el registro?',
                    text: "¡No podrás revertir esto!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Sí, ¡bórralo!'
                }).then((result) => {
                    if (result.value) {
                        let me = this
                        axios.post('/inventario/borrar',{
                            'id': id
                        }).then(function (response) {
                            me.obtener_registros();
                            me.$store.commit('msg_success', 'Registro eliminado exitosamente.')
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
