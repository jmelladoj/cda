<template>
    <div id="main-wrapper">
        <header class="topbar">
            <nav class="navbar top-navbar navbar-expand-md navbar-dark">
                <div class="navbar-header">
                    <a class="navbar-brand" href="javascript:void(0)">
                        <b>
                            <img src="assets/images/logo-icon.png" alt="homepage" class="dark-logo" />
                            <img src="assets/images/logo-light-icon.png" alt="homepage" class="light-logo" />
                        </b>
                        <span>
                            <img src="assets/images/logo-text.png" alt="homepage" class="dark-logo" />
                            <img src="assets/images/logo-light-text.png" class="light-logo" alt="homepage" />
                        </span>
                    </a>
                </div>

                <div class="navbar-collapse">
                    <ul class="navbar-nav mr-auto">
                        <li class="nav-item"> <a class="nav-link nav-toggler d-block d-sm-none waves-effect waves-dark" href="javascript:void(0)"><i class="ti-menu"></i></a> </li>
                        <li class="nav-item"> <a class="nav-link sidebartoggler d-lg-block d-md-block waves-effect waves-dark" href="javascript:void(0)"><i class="icon-menu"></i></a> </li>
                    </ul>

                    <ul class="navbar-nav my-lg-0">
                        <li class="nav-item" v-show="notificaciones.length > 0">
                            <router-link class="nav-link dropdown-toggle waves-effect waves-dark" to="/notificaciones"><i class="fa fa-bell-o"></i><div class="notify"> <span class="heartbit"></span> <span class="point"></span> </div></router-link>
                        </li>
                        <li class="nav-item right-side-toggle" @click="cerrar_session"> <a class="nav-link  waves-effect waves-light" href="javascript:void(0)"><i class="ti-power-off"></i></a></li>
                        <li class="nav-item" v-if="usuario"><b-button pill variant="primary" class="nav-link dropdown-toggle waves-effect waves-dark profile-pic line-height-0 margin-top-0-5" href="" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-text="usuario.nombre[0]"></b-button></li>
                    </ul>
                </div>
            </nav>
        </header>

        <aside class="left-sidebar">
            <!-- Sidebar scroll-->
            <div class="scroll-sidebar">
                <!-- Sidebar navigation-->
                <nav class="sidebar-nav" v-if="usuario">
                    <ul id="sidebarnav">
                        <li class="nav-small-cap">--- MENÚ</li>
                        <li :class="usuario.perfil.menu_perfiles == 0 && usuario.perfil.menu_usuarios == 0 ? 'd-none' : ''">
                            <a class="has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false"><i class="fa fa-users"></i><span class="hide-menu">Usuarios</span></a>
                            <ul aria-expanded="false">
                                <li :class="usuario.perfil.menu_perfiles == 0 ? 'd-none' : ''"><router-link to="/perfiles">Perfiles</router-link></li>
                                <li :class="usuario.perfil.menu_usuarios == 0 ? 'd-none' : ''"><router-link to="/usuarios">Usuarios</router-link></li>
                            </ul>
                        </li>
                        <li :class="usuario.perfil.menu_proveedores == 0 ? 'd-none' : ''">
                            <a class="has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false"><i class="fa fa-archive"></i><span class="hide-menu">Abastecimiento</span></a>
                            <ul aria-expanded="false">
                                <li :class="usuario.perfil.menu_categorias_productos == 0 ? 'd-none' : ''"><router-link to="/categoriasproducto">Categorías producto</router-link></li>
                                <li :class="usuario.perfil.menu_lugares == 0 ? 'd-none' : ''"><router-link to="/lugares">Centro de costos</router-link></li>
                                <li :class="usuario.perfil.menu_inventario == 0 ? 'd-none' : ''"><router-link to="/inventario">Productos</router-link></li>
                                <li><router-link to="/proveedores">Proveedores</router-link></li>
                                <li><router-link to="/ordenescompra">Ordenes de compra</router-link></li>
                            </ul>
                        </li>
                        <li>
                            <router-link to="/facturas"><i class="fa fa-list"></i><span class="hide-menu">Facturas</span></router-link>
                        </li>
                    </ul>
                </nav>
            </div>
        </aside>

        <div class="page-wrapper">
            <b-container fluid>
                <router-view></router-view>
                <orden-compra></orden-compra>
            </b-container>
        </div>
    </div>
</template>

<script>
    import { mapState, mapGetters, mapActions, mapMutations } from 'vuex'

    export default {
        data() {
            return {
                notificaciones: []
            }
        },
        computed: {
            ...mapGetters('usuario', ['saludo']),
            ...mapState('usuario', ['usuario'])
        },
        methods: {
            ...mapActions('usuario', ['salir']),
            ...mapMutations('usuario', ['actualizar']),
            mostrar_notificaciones(){
                let me = this

                axios.get('/notificaciones/1').then(function (response) {
                    me.notificaciones = response.data.alertas
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            actualizar_notificaciones(){
                let me = this
	            setInterval(function(){ me.mostrar_notificaciones() }, 30000);
            },
            usuario_logeado(){
                let me = this

                axios.get('/usuario/logeado').then(function (response) {
                    if(response.data.usuario){
                        me.actualizar(response.data.usuario)
                        me.saludo
                        me.cambiar_clases()

                        if(response.data.usuario.perfil_id == 1){
                            me.actualizar_notificaciones()
                        }
                    } else {
                        me.$router.push('/')
                    }
                })
            },
            cambiar_clases(){
                $('.d-none').remove()
            },
            cerrar_session(){

                let me = this

                axios.post('/logout').then(function (response) {
                    if (response.status === 302 || 401) {
                        me.salir()
                        window.location.href = "/";
                    }
                    else {
                        console.log(response)
                    }
                })
                .catch(function (error) {
                    console.log(error)
                });

            }
        },
        created() {
            this.$on('mostrar_notificaciones', function(){
                this.mostrar_notificaciones()
            });
        },
        mounted(){
            this.usuario_logeado()
            this.mostrar_notificaciones()
        }
    }
</script>
