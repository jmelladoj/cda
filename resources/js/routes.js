window.Vue = require('vue');

import VueRouter from 'vue-router'

Vue.use(VueRouter)

import Usuarios from './components/intranet/Usuarios.vue'
import Perfiles from './components/intranet/Perfiles.vue'


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
        },
    ],
    mode: 'history'
})
