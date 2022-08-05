<template>
    <div>
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Crear Pedido</h1>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <div class="content container-fluid">
            <div class="card">
                <div class="card-header">
                    <div class="card-tools">
                        <router-link class="btn btn-info btn-sm" :to="'/pedido'">
                            <i class="fas fa-arrow-left"></i> Regresar
                        </router-link>
                    </div>
                </div>
                <div class="card-body">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="card" :class="(switchCliente) ? 'card-info' : 'card-success'">
                                    <div class="card-header">
                                        <h3 class="card-title">Formulario {{(switchCliente) ? 'Registrar' : 'Buscar'}} Cliente</h3>
                                    </div>
                                    <div class="card-body">
                                        <form role="form">
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <vs-switch square v-model="switchCliente" @change="limpiarCriterios">
                                                        <template #off>
                                                            <i class="fas fa-plus-square"></i>
                                                        </template>
                                                        <template #on>
                                                            <i class="fas fa-search"></i>
                                                        </template>
                                                    </vs-switch>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-group row">
                                                        <label class="col-md-12 col-form-label">Documento</label>
                                                        <div class="col-md-12">
                                                            <template v-if="switchCliente">
                                                                <input type="text" class="form-control" v-model="fillCrearCliente.cDocumento" @keyup.enter="setRegistrarPedido">
                                                            </template>
                                                            <template v-else>
                                                                <el-autocomplete
                                                                    class="inline-input"
                                                                    v-model="cBusqueda"
                                                                    :fetch-suggestions="querySearch"
                                                                    placeholder="Buscar..."
                                                                    :trigger-on-focus="true"
                                                                    size="medium"
                                                                    @select="clientSelect">
                                                                    <i class="el-icon-search el-input__icon" slot="suffix">
                                                                    </i>
                                                                </el-autocomplete>
                                                            </template>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-group row">
                                                        <label class="col-md-12 col-form-label">Nombre</label>
                                                        <div class="col-md-12">
                                                            <input type="text" class="form-control"
                                                            v-model="fillCrearCliente.cNombre"
                                                            @keyup.enter="setRegistrarPedido"
                                                            :disabled="(switchCliente) ? false : true">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-group row">
                                                        <label class="col-md-12 col-form-label">Apellido</label>
                                                        <div class="col-md-12">
                                                            <input type="text" class="form-control"
                                                            v-model="fillCrearCliente.cApellido"
                                                            @keyup.enter="setRegistrarPedido"
                                                            :disabled="(switchCliente) ? false : true">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-group row">
                                                        <label class="col-md-12 col-form-label">Email</label>
                                                        <div class="col-md-12">
                                                            <vs-input v-model="fillCrearCliente.cEmail"
                                                                    placeholder="correo@gmail.com"
                                                                    @keyup.enter="setRegistrarPedido"
                                                                    :disabled="(switchCliente) ? false : true">
                                                                <template v-if="validEmail" #message-success>Correo Electrónico válido</template>
                                                                <template v-if="!validEmail && fillCrearCliente.cEmail !== ''" #message-danger>Correo Electrónico inválido</template>
                                                            </vs-input>
                                                            <!-- <input type="email" class="form-control"
                                                            v-model="fillCrearCliente.cEmail"
                                                            @keyup.enter="setRegistrarPedido"
                                                            :disabled="(switchCliente) ? false : true"> -->
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-group row">
                                                        <label class="col-md-12 col-form-label">Teléfono</label>
                                                        <div class="col-md-12">
                                                            <input type="tel" class="form-control"
                                                            v-model="fillCrearCliente.cTelefono"
                                                            @keyup.enter="setRegistrarPedido"
                                                            :disabled="(switchCliente) ? false : true">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="card-footer">
                                        <div class="row">
                                            <button class="btn btn-flat btn-info btnFull" @click.prevent="setRegistrarPedido" v-loading.fullscreen.lock="fullscreenLoading">Registrar</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="card card-info">
                                    <div class="card-header">
                                        <h3 class="card-title">Listar Permisos</h3>
                                    </div>
                                    <div class="card-body table-responsive">
                                        <template v-if="listPermisosFilter.length">
                                            <div class="scrollTable">
                                                <table class="table table-hover table-head-fixed text-nowrap">
                                                    <thead>
                                                        <tr>
                                                            <th>Acción</th>
                                                            <th>Nombre</th>
                                                            <th>Url amigable</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr v-for="(item, index) in listPermisosFilter" :key="index" @click.prevent="marcarFila(index)">
                                                            <td>
                                                                <!-- Irán los checkbox para seleccionar los permisos que se le asignarán -->
                                                                <el-checkbox v-model="item.checked"></el-checkbox>
                                                            </td>
                                                            <td v-text="item.name"></td>
                                                            <td v-text="item.slug"></td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </template>
                                        <template v-else>
                                            <div class="callout callout-info">
                                                <h5>No se encontraron registros...</h5>
                                            </div>
                                        </template>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" :class="{ show: modalShow }" :style=" modalShow ? mostrarModal : ocultarModal">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Sistema Laravel y Vue</h5>
                        <button class="close" @click="abrirModal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="callout callout-danger" style="padding: 5px" v-for="(item, index) in mensajeError" :key="index" v-text="item"></div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" @click="abrirModal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios';
    export default {
        data() {
            return {
                fillCrearCliente: {
                    nIdCliente: '',
                    cDocumento: '',
                    cNombre: '',
                    cApellido: '',
                    cEmail: '',
                    cTelefono: '',
                },
                switchCliente: false,
                cBusqueda: '',
                links: [],
                listClientes: [],
                listClientesFilter: [],
                listPermisos: [],
                listPermisosFilter: [],
                fullscreenLoading: false,
                modalShow: false,
                mostrarModal: {
                    display: 'block',
                    background: '#0000006b',
                },
                ocultarModal: {
                    display: 'none',
                },
                error: 0,
                mensajeError: []
            }
        },
        computed: {
            validEmail() {
                return /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(this.fillCrearCliente.cEmail)
            }
        },
        mounted (){
            this.getListarPermisosByRol();
            this.getListarClientes();
        },
        methods: {
            querySearch(queryString, cb) {
                var links = this.listClientesFilter;
                var results = queryString ? links.filter(this.createFilter(queryString)) : links;
                // call callback function to return suggestions
                cb(results);
            },
            createFilter(queryString) {
                return (link) => {
                    return (link.value.toLowerCase().indexOf(queryString.toLowerCase()) != -1);
                };
            },
            getListarClientes(){
                var ruta = '/operacion/cliente/getListarClientes';
                axios.get(ruta).then(response => {
                    this.listClientes = response.data;
                    this.filterListarClientes();
                }).catch(error =>{
                    console.log(error.response);
                    if (error.response.status == 401) {
                        this.$router.push({name: 'login'})
                        location.reload();
                        sessionStorage.clear();
                        this.fullscreenLoading = false;
                    }
                });
            },
            filterListarClientes(){
                let me = this;
                me.listClientesFilter = [];
                me.listClientes.map(function(x, y){
                    me.listClientesFilter.push({
                        'value' :   x.document + ' - ' + x.fullname,
                        'link'  :   x.id
                    });
                });
            },
            clientSelect(item) {
                let rpta = this.listClientes.filter(cliente => {
                    return ((String(cliente.id)).indexOf(String(item.link)) != -1);
                });
                this.fillCrearCliente.nIdCliente    = rpta[0].id;
                this.fillCrearCliente.cDocumento    = rpta[0].document;
                this.fillCrearCliente.cNombre       = rpta[0].name;
                this.fillCrearCliente.cApellido     = rpta[0].lastname;
                this.fillCrearCliente.cEmail        = rpta[0].email;
                this.fillCrearCliente.cTelefono     = rpta[0].phone;
            },
            limpiarCriterios(){
                this.fillCrearCliente.nIdCliente    = '';
                this.cBusqueda                      = '';
                this.fillCrearCliente.cDocumento    = '';
                this.fillCrearCliente.cNombre       = '';
                this.fillCrearCliente.cApellido     = '';
                this.fillCrearCliente.cEmail        = '';
                this.fillCrearCliente.cTelefono     = '';
            },
            abrirModal(){
                this.modalShow = !this.modalShow;
            },
            getListarPermisosByRol(){
                var ruta = '/administracion/rol/getListarPermisosByRol';
                axios.get(ruta).then(response => {
                    this.listPermisos = response.data;
                    this.filterPermisosByRol();
                }).catch(error =>{
                    console.log(error.response);
                    if (error.response.status == 401) {
                        this.$router.push({name: 'login'})
                        location.reload();
                        sessionStorage.clear();
                        this.fullscreenLoading = false;
                    }
                });
            },
            filterPermisosByRol(){
                let me = this;
                me.listPermisos.map(function(x, y){
                    me.listPermisosFilter.push({
                        'id'        :   x.id,
                        'name'      :   x.name,
                        'slug'      :   x.slug,
                        'checked'   :   false
                    });
                });
            },
            marcarFila(index){
                this.listPermisosFilter[index].checked = !this.listPermisosFilter[index].checked;
            },
            setRegistrarPedido(){
                if (this.validarRegistrarPedido()) {
                    this.modalShow = true;
                    return;
                }
                this.fullscreenLoading = true;

                if (this.switchCliente) {
                    this.setRegistrarCliente();
                }
            },
            setRegistrarCliente(){
                var url = '/operacion/cliente/setRegistrarCliente'
                axios.post(url, {
                    'cDocumento'    :  this.fillCrearCliente.cDocumento,
                    'cNombre'       :  this.fillCrearCliente.cNombre,
                    'cApellido'     :  this.fillCrearCliente.cApellido,
                    'cEmail'        :  this.fillCrearCliente.cEmail,
                    'cTelefono'     :  this.fillCrearCliente.cTelefono,
                }).then(response => {
                    this.fullscreenLoading = false;
                    console.log( response.data );
                    this.getListarClientes();
                }).catch(error =>{
                    console.log(error.response);
                    if (error.response.status == 401) {
                        this.$router.push({name: 'login'})
                        location.reload();
                        sessionStorage.clear();
                        this.fullscreenLoading = false;
                    }
                });
            },
            validarRegistrarPedido(){
                this.error = 0;
                this.mensajeError = [];

                if (this.switchCliente) {
                    if (!this.fillCrearCliente.cDocumento) {
                        this.mensajeError.push("El Documento es un campo obligatorio");
                    }
                    if (!this.fillCrearCliente.cNombre) {
                        this.mensajeError.push("El Nombre es un campo obligatorio");
                    }
                    if (!this.fillCrearCliente.cApellido) {
                        this.mensajeError.push("El apellido es un campo obligatorio");
                    }
                    if (this.fillCrearCliente.cEmail) {
                        if (!this.validEmail) {
                            this.mensajeError.push("El correo electrónico tiene un formato inválido");
                        }
                    }
                }else{
                    if (!this.fillCrearCliente.nIdCliente) {
                        this.mensajeError.push("El Cliente es necesario cargar");
                    }
                }

                if (this.mensajeError.length){
                    this.error = 1;
                }
                return this.error;
            }
        }
    }
</script>

<style>

</style>
