export default {
    namespaced: true,
    state: {
        usuario: null
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
            Vue.$toast.open({
                message: '!Hola ' + state.usuario.nombre + '¡',
                type: 'success',
                duration: 5000
            })
        }
    }
}