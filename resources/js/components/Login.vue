<template>
    <section id="wrapper">
        <div class="login-register" style="background-image:url(../assets/images/background/login-register.jpg);">
            <div class="login-box card mt-5">
                <div class="card-body">
                    <form class="form-horizontal form-material" id="loginform" action="index.html">
                        <h3 class="box-title m-b-20"><center>Ingresar</center></h3>
                        <b-form-group>
                            <b-form-input
                                placeholder="Usuario"
                                v-model="$v.login.usuario.$model"
                                :state="$v.login.usuario.$dirty ? !$v.login.usuario.$error : null"
                                aria-describedby="usuario-input"
                            ></b-form-input>

                            <b-form-invalid-feedback id="usuario-input">
                                Ingresa tu usuario y un mínimo de 4 caracteres.
                            </b-form-invalid-feedback>
                        </b-form-group>

                        <b-form-group>
                            <b-form-input
                                type="password"
                                placeholder="Contraseña"
                                v-model="$v.login.password.$model"
                                :state="$v.login.password.$dirty ? !$v.login.password.$error : null"
                                aria-describedby="password-input"
                            ></b-form-input>

                            <b-form-invalid-feedback id="password-input">
                                Ingresa tu contraseña y un mínimo de 4 caracteres.
                            </b-form-invalid-feedback>
                        </b-form-group>


                        <div class="form-group text-center">
                            <div class="col-xs-12 p-b-20">
                                <button @click="ingresar" :disabled="$v.login.$invalid" class="btn btn-block btn-lg btn-info btn-rounded" type="button">Ingresar</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</template>

<script>
    import { required, minLength, between } from 'vuelidate/lib/validators'
    import { mapMutations, mapGetters } from 'vuex'

    export default {
        data(){
            return {
                login: {
                    usuario: '',
                    password: ''
                }
            }
        },
        validations:{
            login: {
                usuario: {
                    required,
                    minLength: minLength(4)
                },
                password: {
                    required,
                    minLength: minLength(4)
                }
            }
        },
        computed: {
            ...mapGetters('usuario', ['saludo']),
        },
        methods: {
            ...mapMutations(['msg_success', 'msg_error']),
            ingresar(){
                if(this.$v.login.$invalid){
                    this.$v.login.$touch()
                    return
                }

                let me = this

                axios.post('/login',{
                    'usuario': me.login.usuario,
                    'password': me.login.password
                }).then(function (response) {
                    window.location.href = "/home";
                }).catch(function (error) {
                    if (error.response.status == 422){
                        me.$store.commit('msg_error', 'Las credenciales introducidas son incorrectas.')
                    }
                })
            }
        }
    }
</script>