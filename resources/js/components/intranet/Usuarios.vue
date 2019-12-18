<template>
    <div>
        <titulo-pagina titulo="Usuarios"></titulo-pagina>

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

                        <template v-slot:cell(index)="row">
                            {{ row.index + 1 }}
                        </template>

                       <template v-slot:cell(correo)="row">
                            <label v-if="!!row.item.email" v-text="row.item.email"></label>
                            <label v-else v-text="'Sin correo asociado'"></label>
                        </template>

                        <template v-slot:cell(acciones)="row">
                            <b-button size="xs" variant="success" title="Cambiar clave" @click="abrir_modal_clave(row.item)">
                                <i class="fa fa-key"></i>
                            </b-button>

                            <b-button size="xs" variant="warning" title="Actualizar información" @click="abrir_modal_usuario(row.item)">
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

        <b-modal ref="modal_usuario" :title="modal_usuario.titulo" size="lg" no-close-on-backdrop>
            <b-form>
                <b-row>
                    <b-col>
                        <b-form-group label="Nombre de usuario">
                            <b-form-input
                                v-model="$v.usuario.nombre.$model"
                                :state="$v.usuario.nombre.$dirty ? !$v.usuario.nombre.$error : null"
                                aria-describedby="usuario_nombre"
                            ></b-form-input>

                            <b-form-invalid-feedback id="usuario_nombre">
                                Campo de alfanúmerico, mínimo de 3 caracteres.
                            </b-form-invalid-feedback>
                        </b-form-group>
                    </b-col>
                    <b-col>
                        <b-form-group label="Usuario">
                            <b-form-input
                                v-model="$v.usuario.usuario.$model"
                                :state="$v.usuario.usuario.$dirty ? !$v.usuario.usuario.$error : null"
                                aria-describedby="usuario_usuario"
                                :readonly="modal_usuario.accion == 2"
                            ></b-form-input>

                            <b-form-invalid-feedback id="usuario_usuario">
                                Campo de alfanúmerico, mínimo de 3 caracteres y no debe de estar en la base de datos.
                            </b-form-invalid-feedback>
                        </b-form-group>
                    </b-col>
                </b-row>

                <b-row>
                    <b-col>
                        <b-form-group label="Email (En caso de enviar correos)">
                            <b-form-input
                                v-model="$v.usuario.email.$model"
                                :state="$v.usuario.email.$dirty ? !$v.usuario.email.$error : null"
                                aria-describedby="usuario_email"
                            ></b-form-input>

                            <b-form-invalid-feedback id="usuario_email">
                                Campo de alfanúmerico, mínimo de 3 caracteres y formato de email.
                            </b-form-invalid-feedback>
                        </b-form-group>
                    </b-col>
                    <b-col v-show="modal_usuario.accion == 1">
                        <b-form-group label="Contraseña">
                            <b-form-input
                                type="password"
                                v-model="$v.usuario.password.$model"
                                :state="$v.usuario.password.$dirty ? !$v.usuario.password.$error : null"
                                aria-describedby="usuario_password"
                            ></b-form-input>

                            <b-form-invalid-feedback id="usuario_password">
                                Campo de alfanúmerico, mínimo de 3 caracteres.
                            </b-form-invalid-feedback>
                        </b-form-group>
                    </b-col>
                </b-row>

                <b-row>
                    <b-col>
                        <b-form-group label="Menú de inicio">
                            <b-form-select
                                v-model="$v.usuario.ruta_inicio.$model"
                                :state="$v.usuario.ruta_inicio.$dirty ? !$v.usuario.ruta_inicio.$error : null"
                                aria-describedby="usuario_ruta_inicio">
                                <option :value="null">Selecciona una opción</option>
                                <option value="usuarios">Sección usuarios</option>
                                <option value="perfiles">Sección perfiles</option>
                                <option value="lugares">Sección lugares</option>
                                <option value="inventario">Sección inventario</option>
                            </b-form-select>

                            <b-form-invalid-feedback id="usuario_ruta_inicio">
                                Debes de seleccionar una opción.
                            </b-form-invalid-feedback>
                        </b-form-group>
                    </b-col>
                    <b-col>
                        <b-form-group label="Perfil de usuario">
                            <b-form-select
                                v-model="$v.usuario.perfil_id.$model"
                                :state="$v.usuario.perfil_id.$dirty ? !$v.usuario.perfil_id.$error : null"
                                aria-describedby="usuario_perfil_id">
                                <option :value="null">Selecciona una opción</option>
                                <option v-for="perfil in perfiles" :key="perfil.id" :value="perfil.id" v-text="perfil.nombre"></option>
                            </b-form-select>

                            <b-form-invalid-feedback id="usuario_perfil_id">
                                Debes de seleccionar una opción.
                            </b-form-invalid-feedback>
                        </b-form-group>
                    </b-col>
                </b-row>
            </b-form>

            <template slot="modal-footer">
                <b-button :disabled="$v.usuario.$invalid" v-show="modal_usuario.accion == 1" size="md" variant="success" @click="crear_actualizar_usuario"> Guardar </b-button>
                <b-button :disabled="$v.usuario.$invalid" v-show="modal_usuario.accion == 2" size="md" variant="warning" @click="crear_actualizar_usuario"> Actualizar </b-button>
                <b-button size="md" variant="danger" @click="cerrar_modal_usuario"> Cerrar </b-button>
            </template>
        </b-modal>

        <b-modal ref="modal_clave" :title="modal_clave.titulo" size="md" no-close-on-backdrop>
            <b-form>
                <b-row>
                    <b-col>
                        <b-form-group label="Nueva contraseña">
                            <b-form-input
                                type="password"
                                v-model="$v.cambio_clave.clave.$model"
                                :state="$v.cambio_clave.clave.$dirty ? !$v.cambio_clave.clave.$error : null"
                                aria-describedby="cambio_clave_clave"
                            ></b-form-input>

                            <b-form-invalid-feedback id="cambio_clave_clave">
                                Campo de alfanúmerico, mínimo de 3 caracteres.
                            </b-form-invalid-feedback>
                        </b-form-group>
                    </b-col>
                </b-row>
            </b-form>

            <template slot="modal-footer">
                <b-button :disabled="$v.cambio_clave.$invalid" size="md" variant="warning" @click="actualizar_clave"> Cambiar clave </b-button>
                <b-button size="md" variant="danger" @click="cerrar_modal_clave"> Cerrar </b-button>
            </template>
        </b-modal>


        <div class="fixed-bottom mb-5 mr-5">
            <b-button pill variant="success" size="lg" class="pull-right heigth-50" @click="abrir_modal_usuario"><i class="ti-plus"></i></b-button>
        </div>
    </div>
</template>

<script>
    import { required, minLength, email, requiredIf } from 'vuelidate/lib/validators'
    import { mapMutations, mapGetters } from 'vuex'

    export default {
        data() {
            return {
                items: [],
                perfiles: [],
                fields: [
                    { key: 'index', label: '#', sortable: true, class: 'text-center' },
                    { key: 'nombre', label: 'Nombre', sortable: true, class: 'text-left' },
                    { key: 'usuario', label: 'Nombre usuario', sortable: true, class: 'text-left' },
                    { key: 'nombre_perfil', label: 'Perfil asociado', sortable: true, class: 'text-left' },
                    { key: 'correo', label: 'Correo asociado', sortable: true, class: 'text-left' },
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
                modal_usuario: {
                    titulo: '',
                    accion: 0
                },
                modal_clave: {
                    titulo: ''
                },
                usuario: {
                    id: 0,
                    nombre: '',
                    usuario: '',
                    email: '',
                    password: '',
                    ruta_inicio: null,
                    perfil_id: null
                },
                cambio_clave: {
                    clave: ''
                }
            }
        },
        validations:{
            usuario: {
                nombre: {
                    required,
                    minLength: minLength(3)
                },
                usuario: {
                    required: requiredIf(function () {
                        return this.modal_usuario.accion == 1 ? false : true
                    }),
                    minLength: minLength(3),
                    async isUnique (value) {
                        if(this.modal_usuario.accion == 2) return true
                        if (value === '' || value.length < 3) return true

                        const response = await fetch(`/usuarios/unico/${value}`)
                        return await response.json()
                    }
                },
                email: {
                    email,
                    minLength: minLength(3)
                },
                password: {
                    required: requiredIf(function () {
                        return this.modal_usuario.accion == 2 ? false : true
                    }),
                    minLength: minLength(3)
                },
                ruta_inicio: {
                    required
                },
                perfil_id: {
                    required
                }
            },
            cambio_clave: {
                clave: {
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
            cargar_datos(){
                this.listar_usuarios()
                this.listar_perfiles()
            },
            listar_usuarios(){
                let me = this

                axios.get('/usuarios/1').then(function (response) {
                    me.items = response.data.usuarios
                    me.totalRows = me.items.length;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            listar_perfiles(){
                let me = this

                axios.get('/perfiles/1').then(function (response) {
                    me.perfiles = response.data.perfiles
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            abrir_modal_usuario(data = []) {
                let me = this

                me.limpiar_datos_usuario()
                me.modal_usuario.titulo = data.id == undefined ? "Agregar usuario" : "Modificar usuario"
                me.modal_usuario.accion = data.id == undefined ? 1 : 2

                if(me.modal_usuario.accion == 2){
                    me.usuario.id = data.id
                    me.usuario.nombre = data.nombre
                    me.usuario.usuario = data.usuario
                    me.usuario.email = data.email
                    me.usuario.ruta_inicio = data.ruta_inicio
                    me.usuario.perfil_id = data.perfil_id

                    this.$v.usuario.$touch(true)
                }

                this.$refs['modal_usuario'].show()
            },
            abrir_modal_clave(data = []) {
                let me = this

                me.limpiar_datos_clave()
                me.usuario.id = data.id
                me.modal_clave.titulo = 'Cambiar clave a ' + data.nombre

                this.$refs['modal_clave'].show()
            },
            cerrar_modal_clave() {
                this.modal_clave.titulo = ""

                this.$refs['modal_clave'].hide()
            },
            cerrar_modal_usuario() {
                this.modal_usuario.titulo = ""
                this.modal_usuario.accion = 0

                this.$refs['modal_usuario'].hide()
            },
            limpiar_datos_usuario() {
                this.usuario.id = 0
                this.usuario.nombre = ''
                this.usuario.usuario = ''
                this.usuario.email = ''
                this.usuario.password = ''
                this.usuario.ruta_inicio = null
                this.usuario.perfil_id = null

                this.$v.$reset();
            },
            limpiar_datos_clave(){
                this.usuario.id = 0
                this.cambio_clave.clave = ''

                this.$v.$reset();
            },
            crear_actualizar_usuario() {
                if(this.$v.usuario.$invalid){
                    this.$v.usuario.$touch()
                    return
                }

                let me = this

                axios.post('/usuario/crear/actualizar',{
                        'id': me.usuario.id,
                        'nombre': me.usuario.nombre,
                        'usuario': me.usuario.usuario,
                        'email': me.usuario.email,
                        'password': me.usuario.password,
                        'ruta_inicio': me.usuario.ruta_inicio,
                        'perfil_id': me.usuario.perfil_id
                    }).then(function (response) {
                        me.cargar_datos()
                        me.$store.commit('msg_success', me.usuario.id == 0 ? 'Registro agregado exitosamente.' :  'Registro actualizado exitosamente.')

                        if(me.usuario.id == 0){
                            me.limpiar_datos_usuario()
                        } else {
                            me.cerrar_modal_usuario()
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
                        axios.post('/usuario/borrar',{
                            'id': id
                        }).then(function (response) {
                            me.cargar_datos();
                            me.$store.commit('msg_success', 'Registro eliminado exitosamente.')
                        }).catch(function (error) {
                            console.log(error);
                        })
                    }
                })
            },
            actualizar_clave(){
                if(this.$v.cambio_clave.$invalid){
                    this.$v.cambio_clave.$touch()
                    return
                }

                let me = this

                axios.post('/usuario/actualizar/clave',{
                        'id': me.usuario.id,
                        'password': me.cambio_clave.clave
                    }).then(function (response) {
                        me.$store.commit('msg_success', 'Registro actualizado exitosamente.')

                        me.cerrar_modal_clave()
                    }).catch(function (error) {
                        console.log(error)
                })
            }
        },
        mounted() {
            this.cargar_datos()
        }
    }
</script>
