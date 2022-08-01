<template>
    <div class="login-box">
        <!-- /.login-logo -->
        <div class="card card-outline card-primary">
            <div class="card-header text-center">
                <router-link class="h1" :to="{name:'login'}">
                    <b>Iniciar Sesión</b>
                </router-link>

            </div>
            <div class="card-body">
                <p class="login-box-msg">Ingresa tus credenciales para Iniciar Sesión</p>
                <form method="post">
                    <div class="input-group mb-3">
                        <input type="email" class="form-control" @keyup.enter="login" v-model="fillLogin.cEmail" placeholder="Email">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-envelope"></span>
                            </div>
                        </div>
                    </div>
                    <div class="input-group mb-3">
                        <input type="password" class="form-control" @keyup.enter="login" v-model="fillLogin.cContrasena" placeholder="Password">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>
                </form>

                <div class="row">
                    <div class="col-md-12">
                        <span v-if="error">
                            <div v-for="(e, index) in mensajeError" :key="index" v-text="e" class="callout callout-danger"></div>
                        </span>
                    </div>
                </div>

                <div class="social-auth-links text-center mt-2 mb-3">
                    <button class="btn btn-flat btn-block btn-danger" @click.prevent="login" v-loading.fullscreen.lock="fullscreenLoading">
                        Iniciar Sesión
                    </button>
                </div>
                <!-- /.social-auth-links -->
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
    </div>
</template>

<script>
import axios from 'axios';
    export default {
        data() {
            return {
                fillLogin: {
                    cEmail: '',
                    cContrasena: '',
                },
                listRolPermisosByUsuario: [],
                listRolPermisosByUsuarioFilter: [],
                fullscreenLoading: false,
                error: 0,
                mensajeError: [],
            }
        },
        methods: {
            login(){
                if (this.validarLogin()){
                    return;
                }
                this.fullscreenLoading = true;
                var url = '/authenticate/login'
                axios.post(url,{
                    'cEmail'       :  this.fillLogin.cEmail,
                    'cContrasena'  :  this.fillLogin.cContrasena,
                }).then(response =>{
                    // console.log( reponse.data );
                    if (response.data.code == 401) {
                        this.loginFailed();
                    }
                     if (response.data.code == 200) {
                        this.getListarRolPermisosByUsuario(response.data.authUser.id);
                    }
                    this.fullscreenLoading = false;
                })
            },
            getListarRolPermisosByUsuario(id){
                var ruta = '/administracion/usuario/getListarRolPermisosByUsuario';
                axios.get(ruta, {
                    params: {
                        'nIdUsuario' : id
                    }
                }).then(response => {
                    this.listRolPermisosByUsuario = response.data;
                    this.filterListarRolPermisosByUsuario();
                })
            },
            filterListarRolPermisosByUsuario(){
                let me = this;
                me.listRolPermisosByUsuario.map(function(x, y){
                    me.listRolPermisosByUsuarioFilter.push(x.slug);
                });
                sessionStorage.setItem("listRolPermisosByUsuario", JSON.stringify(me.listRolPermisosByUsuarioFilter));
                this.loginSuccess();
            },
            validarLogin(){
                this.error = 0;
                this.mensajeError = [];

                if(!this.fillLogin.cEmail){
                    this.mensajeError.push("El correo electrónico es un campo obligatorio")
                }
                if(!this.fillLogin.cContrasena){
                    this.mensajeError.push("La contraseña es un campo obligatorio")
                }

                if(this.mensajeError.length){
                    this.error = 1;
                }
                return this.error;
            },
            loginFailed(){
                this.error = 0;
                this.mensajeError = [];

                this.mensajeError.push("Estas credenciales no coinciden con nuestros registros");
                this.fillLogin.cContrasena = '';

                if(this.mensajeError.length){
                    this.error = 1;
                }
                return this.error;
            },
            loginSuccess(){
                this.$router.push({name: 'dashboard.index'});
                location.reload();
            }
        },
    }
</script>

<style>

</style>
