<template>
    <div>
        <titulo-pagina titulo="Proveedores"></titulo-pagina>

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

                        <template v-slot:cell(gasto_mensual)="data">
                            {{ data.item.gasto_mensual | currency }}
                        </template>

                        <template v-slot:cell(productos)="data">
                            {{ obtener_nombre_productos(data.item.productos) }}
                        </template>

                        <template v-slot:cell(acciones)="row">
                            <b-button size="xs" variant="success" title="Generar orden de compra" @click="abrir_modal_orden_compra(row.item.id)">
                                <i class="fa fa-plus"></i>
                            </b-button>

                            <b-button size="xs" variant="warning" title="Actualizar información" @click="abrir_modal_proveedor(row.item)">
                                <i class="fa fa-pencil"></i>
                            </b-button>

                            <b-button size="xs" variant="danger" title="Eliminar registro" @click="borrar(row.item.id)">
                                <i class="fa fa-trash"></i>
                            </b-button>
                        </template>
                    </b-table>
                </b-card>
            </b-col>
        </b-row>

        <b-modal ref="modal_proveedor" :title="modal_proveedor.titulo" size="xl" no-close-on-backdrop scrollable static>
            <b-form>
                <b-row>
                    <b-col xs="12" sm="12" md="6">
                        <b-form-group label="Razón Social " label-cols-md="3" label-cols-lg="3" class="mb-1">
                            <b-form-input
                                v-model="$v.proveedor.nombre.$model"
                                :state="$v.proveedor.nombre.$dirty ? !$v.proveedor.nombre.$error : null"
                                aria-describedby="proveedor-nombre"
                            ></b-form-input>

                            <b-form-invalid-feedback id="proveedor-nombre">
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
                                aria-describedby="proveedor-rut"
                                :readonly="modal_proveedor.accion == 2"
                            ></b-form-input>

                            <b-form-invalid-feedback id="proveedor-rut">
                                Campo de alfanúmerico, mínimo de 3 caracteres.
                            </b-form-invalid-feedback>
                        </b-form-group>
                    </b-col>
                    <b-col xs="12" sm="12" md="6">
                        <b-form-group label="Giro " label-cols-md="3" label-cols-lg="3" class="mb-1">
                            <b-form-input
                                v-model="$v.proveedor.giro.$model"
                                :state="$v.proveedor.giro.$dirty ? !$v.proveedor.giro.$error : null"
                                aria-describedby="proveedor-giro"
                            ></b-form-input>

                            <b-form-invalid-feedback id="proveedor-giro">
                                Campo de texto, mínimo de 3 caracteres.
                            </b-form-invalid-feedback>
                        </b-form-group>
                    </b-col>
                    <b-col xs="12" sm="12" md="6">
                        <b-form-group label="Teléfono " label-cols-md="3" label-cols-lg="3" class="mb-1">
                            <b-form-input
                                v-model="$v.proveedor.telefono.$model"
                                :state="$v.proveedor.telefono.$dirty ? !$v.proveedor.telefono.$error : null"
                                aria-describedby="proveedor-telefono"
                            ></b-form-input>

                            <b-form-invalid-feedback id="proveedor-telefono">
                                Campo de númerico, mínimo de 3 caracteres.
                            </b-form-invalid-feedback>
                        </b-form-group>
                    </b-col>
                    <b-col xs="12" sm="12" md="6">
                        <b-form-group label="Dirección " label-cols-md="3" label-cols-lg="3" class="mb-1">
                            <b-form-input
                                v-model="$v.proveedor.direccion.$model"
                                :state="$v.proveedor.direccion.$dirty ? !$v.proveedor.direccion.$error : null"
                                aria-describedby="proveedor-direccion"
                            ></b-form-input>

                            <b-form-invalid-feedback id="proveedor-direccion">
                                Campo de alfanúmerico, mínimo de 3 caracteres.
                            </b-form-invalid-feedback>
                        </b-form-group>
                    </b-col>
                    <b-col xs="12" sm="12" md="6">
                        <b-form-group label="Comuna " label-cols-md="3" label-cols-lg="3" class="mb-1">
                            <b-form-input
                                v-model="$v.proveedor.comuna.$model"
                                :state="$v.proveedor.comuna.$dirty ? !$v.proveedor.comuna.$error : null"
                                aria-describedby="proveedor-comuna"
                            ></b-form-input>

                            <b-form-invalid-feedback id="proveedor-comuna">
                                Campo de texto, mínimo de 3 caracteres.
                            </b-form-invalid-feedback>
                        </b-form-group>
                    </b-col>
                    <b-col xs="12" sm="12" md="6">
                        <b-form-group label="Correo " label-cols-md="3" label-cols-lg="3" class="mb-1">
                            <b-form-input
                                v-model="$v.proveedor.correo.$model"
                                :state="$v.proveedor.correo.$dirty ? !$v.proveedor.correo.$error : null"
                                aria-describedby="proveedor-correo"
                            ></b-form-input>

                            <b-form-invalid-feedback id="proveedor-correo">
                                Campo de alfanúmerico, mínimo de 3 caracteres y formato de email.
                            </b-form-invalid-feedback>
                        </b-form-group>
                    </b-col>
                    <b-col xs="12" sm="12" md="6">
                        <b-form-group label="Contacto " label-cols-md="3" label-cols-lg="3" class="mb-1">
                            <b-form-input
                                v-model="$v.proveedor.referencia.$model"
                                :state="$v.proveedor.referencia.$dirty ? !$v.proveedor.referencia.$error : null"
                                aria-describedby="proveedor-referencia"
                            ></b-form-input>

                            <b-form-invalid-feedback id="proveedor-referencia">
                                Campo de texto, mínimo de 3 caracteres.
                            </b-form-invalid-feedback>
                        </b-form-group>
                    </b-col>
                </b-row>
            </b-form>

            <template slot="modal-footer">
                <b-button :disabled="$v.proveedor.$invalid" v-show="modal_proveedor.accion == 1" size="md" variant="success" @click="crear_actualizar_proveedor"> Guardar </b-button>
                <b-button :disabled="$v.proveedor.$invalid" v-show="modal_proveedor.accion == 2" size="md" variant="warning" @click="crear_actualizar_proveedor"> Actualizar </b-button>
                <b-button size="md" variant="danger" @click="cerrar_modal_proveedor"> Cerrar </b-button>
            </template>
        </b-modal>

        <div class="fixed-bottom mb-5 mr-5">
            <b-button pill variant="success" size="lg" class="pull-right heigth-50" @click="abrir_modal_proveedor"><i class="ti-plus"></i></b-button>
        </div>
    </div>
</template>

<script>
    import { required, minLength, email, numeric, requiredIf  } from 'vuelidate/lib/validators'
    import { mapMutations, mapGetters } from 'vuex'

    export default {
        data() {
            return {
                items: [],
                fields: [
                    { key: 'index', label: '#', sortable: true, class: 'text-center' },
                    { key: 'rut', label: 'Rut', sortable: true, class: 'text-left' },
                    { key: 'nombre', label: 'Nombre', sortable: true, class: 'text-left' },
                    { key: 'giro', label: 'Giro', sortable: true, class: 'text-left' },
                    { key: 'telefono', label: 'Teléfono', sortable: true, class: 'text-left' },
                    { key: 'correo', label: 'Correo', sortable: true, class: 'text-left' },
                    { key: 'gasto_mensual', label: 'Gasto mensual', sortable: true, class: 'text-left' },
                    { key: 'productos', label: 'Productos', sortable: true, class: 'text-left' },
                    { key: 'acciones', label: 'Acciones', class: 'text-center'}
                ],
                totalRows: 1,
                currentPage: 1,
                perPage: 15,
                pageOptions: [15, 50, 100, 150, 200, 150],
                sortBy: '',
                sortDesc: false,
                filter: null,
                modal_proveedor: {
                    titulo: '',
                    accion: 0
                },
                proveedor: {
                    id: 0,
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
                    required: requiredIf(function () {
                        return this.modal_proveedor.accion == 1 ? false : true
                    }),
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
            abrir_modal_orden_compra(id_proveedor){
                Evento.$emit('cargar_modal_orden_compra', id_proveedor);
            },
            onFiltered(filteredItems) {
                this.totalRows = filteredItems.length
                this.currentPage = 1
            },
            obtener_nombre_productos(data  = []){
                var productos = ''

                if(data.length > 0){
                    data.forEach(item => productos += item.nombre + ' - ')
                } else {
                    productos = 'Sin productos'
                }

                return data.length > 0 ? productos.substr(0, productos.length - 2) : productos
            },
            listar_proveedores(){
                let me = this

                axios.get('/proveedores/1').then(function (response) {
                    me.items = response.data.proveedores
                    me.totalRows = me.items.length;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            abrir_modal_proveedor(data = []) {
                let me = this

                me.limpiar_datos_proveedor()
                me.modal_proveedor.titulo = data.id == undefined ? "Agregar proveedor" : "Modificar proveedor"
                me.modal_proveedor.accion = data.id == undefined ? 1 : 2

                if(me.modal_proveedor.accion == 2){
                    me.proveedor.id = data.id
                    me.proveedor.nombre = data.nombre
                    me.proveedor.rut = data.rut
                    me.proveedor.giro = data.giro
                    me.proveedor.telefono = data.telefono
                    me.proveedor.direccion = data.direccion
                    me.proveedor.comuna = data.comuna
                    me.proveedor.correo = data.correo
                    me.proveedor.referencia = data.referencia

                    me.$v.proveedor.$touch(true)
                }

                this.$refs['modal_proveedor'].show()
            },
            cerrar_modal_proveedor() {
                this.modal_proveedor.titulo = ""
                this.modal_proveedor.accion = 0
                this.$refs['modal_proveedor'].hide()
            },
            limpiar_datos_proveedor() {
                this.proveedor.id = 0
                this.proveedor.nombre = ''
                this.proveedor.rut = ''
                this.proveedor.giro = ''
                this.proveedor.telefono = ''
                this.proveedor.direccion = ''
                this.proveedor.comuna = ''
                this.proveedor.correo = ''
                this.proveedor.referencia = ''


                this.$v.$reset();
            },
            crear_actualizar_proveedor() {
                if(this.$v.proveedor.$invalid){
                    this.$v.proveedor.$touch()
                    return
                }

                let me = this

                axios.post('/proveedor/crear/actualizar',{
                        'id': me.proveedor.id,
                        'nombre': me.proveedor.nombre,
                        'rut': me.proveedor.rut,
                        'giro': me.proveedor.giro,
                        'telefono': me.proveedor.telefono,
                        'direccion': me.proveedor.direccion,
                        'comuna': me.proveedor.comuna,
                        'correo': me.proveedor.correo,
                        'referencia': me.proveedor.referencia
                    }).then(function (response) {
                        me.listar_proveedores()
                        me.$store.commit('msg_success', me.proveedor.id == 0 ? 'Registro agregado exitosamente.' :  'Registro actualizado exitosamente.')

                        if(me.proveedor.id == 0){
                            me.limpiar_datos_proveedor()
                        } else {
                            me.cerrar_modal_proveedor()
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
                        axios.post('/proveedor/borrar',{
                            'id': id
                        }).then(function (response) {
                            me.listar_proveedores();
                            me.$store.commit('msg_success', 'Registro eliminado exitosamente.')
                        }).catch(function (error) {
                            console.log(error);
                        })
                    }
                })
            }
        },
        mounted() {
            this.listar_proveedores()
        }
    }
</script>
