<template>
    <div>
        <titulo-pagina titulo="Perfiles"></titulo-pagina>

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

                    <b-table class="my-3" show-empty small striped outlined stacked="md" :items="items" :fields="fields" :current-page="currentPage" :per-page="perPage" :filter="filter" :filterIncludedFields="filterOn" :sort-by.sync="sortBy" :sort-desc.sync="sortDesc" :sort-direction="sortDirection" @filtered="onFiltered" >
                        <template v-slot:empty="scope">
                            <center><h5>No hay registros</h5></center>
                        </template>

                        <template v-slot:cell(index)="data">
                            {{ data.index + 1 }}
                        </template>


                        <template v-slot:cell(acciones)="row">
                            <b-button size="xs" variant="warning" title="Actualizar información" @click="abrir_modal_perfil(row.item)">
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

        <b-modal ref="modal_perfil" :title="modal_perfil.titulo" size="lg" no-close-on-backdrop>
            <b-form>
                <b-form-group label="Nombre de perfil">
                    <b-form-input
                        v-model="$v.perfil.nombre.$model"
                        :state="$v.perfil.nombre.$dirty ? !$v.perfil.nombre.$error : null"
                        aria-describedby="perfil-nombre"
                    ></b-form-input>

                    <b-form-invalid-feedback id="perfil-nombre">
                        Campo de alfanúmerico, mínimo de 3 caracteres.
                    </b-form-invalid-feedback>
                </b-form-group>

                <hr>

                <b-form-group label="Funciones">
                    <b-row>
                    <b-col>
                        <b-form-group label="Usuarios">
                            <b-form-checkbox v-model="perfil.menu_perfiles">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Perfiles</b-form-checkbox>
                            <b-form-checkbox v-model="perfil.menu_usuarios">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  Usuarios</b-form-checkbox>
                        </b-form-group>
                    </b-col>
                    <b-col>
                        <b-form-group label="Documentos">
                            <b-form-checkbox v-model="perfil.menu_ordenes_compra">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  Ordenes de compra</b-form-checkbox>
                        </b-form-group>
                    </b-col>
                    <b-col>
                        <b-form-group label="Inventario">
                            <b-form-checkbox v-model="perfil.menu_lugares">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  Lugares</b-form-checkbox>
                            <b-form-checkbox v-model="perfil.menu_inventario">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  Productos</b-form-checkbox>
                        </b-form-group>
                    </b-col>
                    </b-row>
                </b-form-group>
            </b-form>

            <template slot="modal-footer">
                <b-button :disabled="$v.perfil.$invalid" v-show="modal_perfil.accion == 1" size="md" variant="success" @click="crear_actualizar_perfil"> Guardar </b-button>
                <b-button :disabled="$v.perfil.$invalid" v-show="modal_perfil.accion == 2" size="md" variant="warning" @click="crear_actualizar_perfil"> Actualizar </b-button>
                <b-button size="md" variant="danger" @click="cerrar_modal_perfil"> Cerrar </b-button>
            </template>
        </b-modal>

        <div class="fixed-bottom mb-5 mr-5">
            <b-button pill variant="success" size="lg" class="pull-right heigth-50" @click="abrir_modal_perfil"><i class="ti-plus"></i></b-button>
        </div>
    </div>
</template>

<script>
    import { required, minLength, between } from 'vuelidate/lib/validators'
    import { mapMutations, mapGetters } from 'vuex'

    export default {
        data() {
            return {
                items: [],
                fields: [
                    { key: 'index', label: '#', sortable: true, class: 'text-center' },
                    { key: 'nombre', label: 'Nombre', sortable: true, class: 'text-left' },
                    { key: 'acciones', label: 'Acciones', class: 'text-center'}
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
                modal_perfil: {
                    titulo: '',
                    accion: 0
                },
                perfil: {
                    id: 0,
                    nombre: '',
                    menu_perfiles : false,
                    menu_usuarios : false,
                    menu_ordenes_compra : false,
                    menu_lugares : false,
                    menu_inventario : false
                }
            }
        },
        validations:{
            perfil: {
                nombre: {
                    required,
                    minLength: minLength(3)
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
            listar_perfiles(){
                let me = this

                axios.get('/perfiles/1').then(function (response) {
                    me.items = response.data.perfiles
                    me.totalRows = me.items.length;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            abrir_modal_perfil(data = []) {
                let me = this

                me.limpiar_datos_perfil()
                me.modal_perfil.titulo = data.id == undefined ? "Agregar perfil" : "Modificar perfil"
                me.modal_perfil.accion = data.id == undefined ? 1 : 2

                if(me.modal_perfil.accion == 2){
                    me.perfil.id = data.id
                    me.perfil.nombre = data.nombre
                    me.perfil.menu_perfiles = data.menu_perfiles == 1 ? true : false
                    me.perfil.menu_usuarios = data.menu_usuarios == 1 ? true : false
                    me.perfil.menu_ordenes_compra = data.menu_ordenes_compra == 1 ? true : false
                    me.perfil.menu_lugares = data.menu_lugares == 1 ? true : false
                    me.perfil.menu_inventario = data.menu_inventario == 1 ? true : false

                    this.$v.perfil.$touch(true)
                }

                this.$refs['modal_perfil'].show()
            },
            cerrar_modal_perfil() {
                this.modal_perfil.titulo = ""
                this.modal_perfil.accion = 0
                this.$refs['modal_perfil'].hide()
            },
            limpiar_datos_perfil() {
                this.perfil.id = 0
                this.perfil.nombre = ''
                this.perfil.menu_perfiles = false
                this.perfil.menu_usuarios = false
                this.perfil.menu_ordenes_compra = false
                this.perfil.menu_lugares = false
                this.perfil.menu_inventario = false

                this.$v.$reset();
            },
            crear_actualizar_perfil() {
                if(this.$v.perfil.$invalid){
                    this.$v.perfil.$touch()
                    return
                }

                let me = this

                axios.post('/perfil/crear/actualizar',{
                        'id': me.perfil.id,
                        'nombre': me.perfil.nombre,
                        'menu_perfiles': me.perfil.menu_perfiles,
                        'menu_usuarios': me.perfil.menu_usuarios,
                        'menu_ordenes_compra': me.perfil.menu_ordenes_compra,
                        'menu_lugares': me.perfil.menu_lugares,
                        'menu_inventario': me.perfil.menu_inventario
                    }).then(function (response) {
                        me.listar_perfiles()
                        me.$store.commit('msg_success', me.perfil.id == 0 ? 'Registro agregado exitosamente.' :  'Registro actualizado exitosamente.')

                        if(me.perfil.id == 0){
                            me.limpiar_datos_perfil()
                        } else {
                            me.cerrar_modal_perfil()
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
                        axios.post('/perfil/borrar',{
                            'id': id
                        }).then(function (response) {
                            me.listar_perfiles();
                            me.$store.commit('msg_success', 'Registro eliminado exitosamente.')
                        }).catch(function (error) {
                            console.log(error);
                        })
                    }
                })
            }
        },
        mounted() {
            this.listar_perfiles()
        }
    }
</script>
