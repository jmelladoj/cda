<template>
    <div>
        <titulo-pagina titulo="Categorías de productos"></titulo-pagina>

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

                    <b-table class="my-3" show-empty small striped outlined stacked="sm" :items="items" :fields="fields" :current-page="currentPage" :per-page="perPage" :filter="filter" :sort-by.sync="sortBy" :sort-desc.sync="sortDesc" @filtered="onFiltered">
                        <template v-slot:empty>
                            <center><h5>No hay registros</h5></center>
                        </template>

                        <template v-slot:cell(index)="data">
                            {{ data.index + 1 }}
                        </template>

                        <template v-slot:cell(valorizacion_aproximada)="data">
                            {{ obtener_valorazion(data.item.productos) | currency }}
                        </template>

                        <template v-slot:cell(acciones)="row">
                            <b-button size="xs" variant="warning" title="Actualizar información" @click="abrir_modal_categoria(row.item)">
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

        <b-modal ref="modal_categoria" :title="modal_categoria.titulo" no-close-on-backdrop scrollable static>
            <b-form>
                <b-form-group label="Nombre de categoria">
                    <b-form-input
                        v-model="$v.categoria.nombre.$model"
                        :state="$v.categoria.nombre.$dirty ? !$v.categoria.nombre.$error : null"
                        aria-describedby="categoria-nombre"
                    ></b-form-input>

                    <b-form-invalid-feedback id="categoria-nombre">
                        Campo de alfanúmerico, mínimo de 3 caracteres.
                    </b-form-invalid-feedback>
                </b-form-group>
            </b-form>

            <template slot="modal-footer">
                <b-button :disabled="$v.categoria.$invalid" v-show="modal_categoria.accion == 1" size="md" variant="success" @click="crear_actualizar_categoria"> Guardar </b-button>
                <b-button :disabled="$v.categoria.$invalid" v-show="modal_categoria.accion == 2" size="md" variant="warning" @click="crear_actualizar_categoria"> Actualizar </b-button>
                <b-button size="md" variant="danger" @click="cerrar_modal_categoria"> Cerrar </b-button>
            </template>
        </b-modal>

        <div class="fixed-bottom mb-5 mr-5">
            <b-button pill variant="success" size="lg" class="pull-right heigth-50" @click="abrir_modal_categoria"><i class="ti-plus"></i></b-button>
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
                    { key: 'valorizacion_aproximada', label: 'Valorización aproximada', sortable: true, class: 'text-left' },
                    { key: 'acciones', label: 'Acciones', class: 'text-center'}
                ],
                totalRows: 1,
                currentPage: 1,
                perPage: 15,
                pageOptions: [15, 50, 100, 150, 200, 150],
                sortBy: '',
                sortDesc: false,
                filter: null,
                modal_categoria: {
                    titulo: '',
                    accion: 0
                },
                categoria: {
                    id: 0,
                    nombre: ''
                }
            }
        },
        validations:{
            categoria: {
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
            obtener_valorazion(data  = []){
                var total = 0

                if(data.length > 0){
                    data.forEach(item => total += item.valorizacion)
                }

                return total
            },
            listar_categorias(){
                let me = this

                axios.get('/categorias/1').then(function (response) {
                    me.items = response.data.categorias
                    me.totalRows = me.items.length;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            abrir_modal_categoria(data = []) {
                let me = this

                me.limpiar_datos_categoria()
                me.modal_categoria.titulo = data.id == undefined ? "Agregar categoría" : "Modificar categoría"
                me.modal_categoria.accion = data.id == undefined ? 1 : 2

                if(me.modal_categoria.accion == 2){
                    me.categoria.id = data.id
                    me.categoria.nombre = data.nombre

                    this.$v.categoria.$touch(true)
                }

                this.$refs['modal_categoria'].show()
            },
            cerrar_modal_categoria() {
                this.modal_categoria.titulo = ""
                this.modal_categoria.accion = 0
                this.$refs['modal_categoria'].hide()
            },
            limpiar_datos_categoria() {
                this.categoria.id = 0
                this.categoria.nombre = ''

                this.$v.$reset();
            },
            crear_actualizar_categoria() {
                if(this.$v.categoria.$invalid){
                    this.$v.categoria.$touch()
                    return
                }

                let me = this

                axios.post('/categoria/crear/actualizar',{
                        'id': me.categoria.id,
                        'nombre': me.categoria.nombre
                    }).then(function (response) {
                        me.listar_categorias()
                        me.$store.commit('msg_success', me.categoria.id == 0 ? 'Registro agregado exitosamente.' :  'Registro actualizado exitosamente.')

                        if(me.categoria.id == 0){
                            me.limpiar_datos_categoria()
                        } else {
                            me.cerrar_modal_categoria()
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
                        axios.post('/categoria/borrar',{
                            'id': id
                        }).then(function (response) {
                            me.listar_categorias();
                            me.$store.commit('msg_success', 'Registro eliminado exitosamente.')
                        }).catch(function (error) {
                            console.log(error);
                        })
                    }
                })
            }
        },
        mounted() {
            this.listar_categorias()
        }
    }
</script>
