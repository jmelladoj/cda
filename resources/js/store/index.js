window.Vue = require('vue');

import Vuex from 'vuex'
import usuario from './usuario'

Vue.use(Vuex)

export default new Vuex.Store({
    state: {
        notificacion: {
            mensaje: ''
        }
    },
    mutations: {
        msg_info(state, mensaje){
            state.notificacion.mensaje = mensaje

            Vue.$toast.open({
                message: mensaje,
                type: 'info',
                duration: 5000
            })

            state.notificacion.mensaje = ''
        },
        msg_success(state, mensaje){
            state.notificacion.mensaje = mensaje

            Vue.$toast.open({
                message: mensaje,
                type: 'success',
                duration: 5000
            })

            state.notificacion.mensaje = ''
        },
        msg_warning(state, mensaje){
            state.notificacion.mensaje = mensaje

            Vue.$toast.open({
                message: mensaje,
                type: 'warning',
                duration: 5000
            })

            state.notificacion.mensaje = ''
        },
        msg_error(state, mensaje){
            state.notificacion.mensaje = mensaje

            Vue.$toast.open({
                message: state.notificacion.mensaje,
                type: 'error',
                duration: 5000
            })

            state.notificacion.mensaje = ''
        }
    },
    actions: {

    },
    getters: {

    },
    modules: {
        usuario
    }
})