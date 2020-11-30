import App from "./components/layout/App";
import VueRouter from 'vue-router'
import VueProgressBar from 'vue-progressbar'
import Vue from 'vue'
import $ from 'jquery';

Vue.use(VueRouter)
require('./bootstrap');

window.$ = window.jQuery = $;
window.Vue = require('vue');
import router from "./routes";
import Dashboard from "./components/Dashboard";
import ShowAgentComponent from "./components/agents/ShowAgentComponent";

// const files = require.context('./', true, /\.vue$/i)
// Vue.component('ShowAgents', require('./components/agents/ShowAgentComponent').default);
// Vue.component('dashboard', require('./components/Dashboard.vue'))
const options = {
    color: '#ffd609',
    failedColor: '#ee060e',
    thickness: '10px',
    transition: {
        speed: '0.2s',
        opacity: '0.6s',
        termination: 300
    },
    autoRevert: true,
    location: 'top',
    inverse: false
}

Vue.use(VueProgressBar, options)

// const app = new Vue({
//     el: '#app',
//     router,
//     render: h => h(app)
//     // router
// });
