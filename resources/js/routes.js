import Vue from 'vue'
import Router from 'vue-router'



Vue.use(Router)

export default new Router({
    routes:[
        { path: '/', component: require('./components/modulos/dashboard/index').default},
        { path: '/pedido', component: require('./components/modulos/pedido/index').default}
    ],
    mode: 'history'
})
