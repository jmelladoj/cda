export default {
    namespaced: true,
    state: {
        usuario: null,
        estado_saludo: 0
    },
    mutations: {
        actualizar(state, usuario){
            state.usuario = usuario
        }
    },
    actions: {
        salir({ commit }){
            commit('actualizar', null)
        }
    },
    getters: {
        saludo(state) {
            if(state.estado_saludo == 0){
                Vue.$toast.open({
                    message: '!Hola ' + state.usuario.nombre + '¡',
                    type: 'success',
                    duration: 5000
                })

                state.estado_saludo = 1
            }

        }
    }
}
