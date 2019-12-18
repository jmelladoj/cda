<template>
    <div>
        <titulo-pagina titulo="Lugares spa"></titulo-pagina>

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
                            <b-button size="xs" variant="warning" title="Actualizar información" @click="abrir_modal_lugar(row.item)">
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

        <b-modal ref="modal_lugar" :title="modal_lugar.titulo" no-close-on-backdrop>
            <b-form>
                <b-form-group label="Nombre de lugar">
                    <b-form-input
                        v-model="$v.lugar.nombre.$model"
                        :state="$v.lugar.nombre.$dirty ? !$v.lugar.nombre.$error : null"
                        aria-describedby="lugar-nombre"
                    ></b-form-input>

                    <b-form-invalid-feedback id="lugar-nombre">
                        Campo de alfanúmerico, mínimo de 3 caracteres.
                    </b-form-invalid-feedback>
                </b-form-group>
            </b-form>

            <template slot="modal-footer">
                <b-button :disabled="$v.lugar.$invalid" v-show="modal_lugar.accion == 1" size="md" variant="success" @click="crear_actualizar_lugar"> Guardar </b-button>
                <b-button :disabled="$v.lugar.$invalid" v-show="modal_lugar.accion == 2" size="md" variant="warning" @click="crear_actualizar_lugar"> Actualizar </b-button>
                <b-button size="md" variant="danger" @click="cerrar_modal_lugar"> Cerrar </b-button>
            </template>
        </b-modal>

        <div class="fixed-bottom mb-5 mr-5">
            <b-button pill variant="success" size="lg" class="pull-right heigth-50" @click="abrir_modal_lugar"><i class="ti-plus"></i></b-button>
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
                modal_lugar: {
                    titulo: '',
                    accion: 0
                },
                lugar: {
                    id: 0,
                    nombre: ''
                }
            }
        },
        validations:{
            lugar: {
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
            listar_lugares(){
                let me = this

                axios.get('/lugares/1').then(function (response) {
                    me.items = response.data.lugares
                    me.totalRows = me.items.length;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            abrir_modal_lugar(data = []) {
                let me = this

                me.limpiar_datos_lugar()
                me.modal_lugar.titulo = data.id == undefined ? "Agregar lugar" : "Modificar lugar"
                me.modal_lugar.accion = data.id == undefined ? 1 : 2

                if(me.modal_lugar.accion == 2){
                    me.lugar.id = data.id
                    me.lugar.nombre = data.nombre

                    this.$v.lugar.$touch(true)
                }

                this.$refs['modal_lugar'].show()
            },
            cerrar_modal_lugar() {
                this.modal_lugar.titulo = ""
                this.modal_lugar.accion = 0
                this.$refs['modal_lugar'].hide()
            },
            limpiar_datos_lugar() {
                this.lugar.id = 0
                this.lugar.nombre = ''

                this.$v.$reset();
            },
            crear_actualizar_lugar() {
                if(this.$v.lugar.$invalid){
                    this.$v.lugar.$touch()
                    return
                }

                let me = this

                axios.post('/lugar/crear/actualizar',{
                        'id': me.lugar.id,
                        'nombre': me.lugar.nombre
                    }).then(function (response) {
                        me.listar_lugares()
                        me.$store.commit('msg_success', me.lugar.id == 0 ? 'Registro agregado exitosamente.' :  'Registro actualizado exitosamente.')

                        if(me.lugar.id == 0){
                            me.limpiar_datos_lugar()
                        } else {
                            me.cerrar_modal_lugar()
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
                        axios.post('/lugar/borrar',{
                            'id': id
                        }).then(function (response) {
                            me.listar_lugares();
                            me.$store.commit('msg_success', 'Registro eliminado exitosamente.')
                        }).catch(function (error) {
                            console.log(error);
                        })
                    }
                })
            }
        },
        mounted() {
            this.listar_lugares()
        }
    }
</script>
