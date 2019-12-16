window.Vue = require('vue');

import VueRouter from 'vue-router'

Vue.use(VueRouter)

import Usuarios from './components/intranet/Usuarios.vue'
import Perfiles from './components/intranet/Perfiles.vue'
import Lugares from './components/intranet/Lugares.vue'
import ProductosInventario from './components/intranet/ProductosInventario.vue'


export default new VueRouter({
    routes: [
        {
            path: '/usuarios',
            name: 'usuarios',
            component: Usuarios
        },{
            path: '/perfiles',
            name: 'perfiles',
            component: Perfiles
        },{
            path: '/lugares',
            name: 'lugares',
            component: Lugares
        },{
            path: '/inventario',
            name: 'inventario',
            component: ProductosInventario
        },
    ],
    mode: 'history'
})
