import App from "./components/layout/App";
import VueRouter from 'vue-router'
import Vue from 'vue'

Vue.use(VueRouter)
require('./bootstrap');

window.Vue = require('vue');
// import router from "./routes";
import Dashboard from "./components/Dashboard";

const files = require.context('./', true, /\.vue$/i)
Vue.component('example-component', require('./components/ExampleComponent.vue').default);
Vue.component('dashboard', require('./components/Dashboard.vue'))

const app = new Vue({
    el: '#app',
    components: {
        App, Dashboard
    },
    // router
});
