import App from "./components/layout/App";
import VueRouter from 'vue-router'
import VueProgressBar from 'vue-progressbar'
import Vue from 'vue'
import $ from 'jquery';
// import pagination from 'laravel-vue-semantic-ui-pagination';

Vue.use(VueRouter)
require('./bootstrap');

window.$ = window.jQuery = $;
window.Vue = require('vue');
import Dashboard from "./components/Dashboard";
import ShowAgents from "./components/agents/show";
// const files = require.context('./', true, /\.vue$/i)
Vue.component('pagination', require('laravel-vue-pagination'));
// Vue.component('pagination', require('laravel-vue-semantic-ui-pagination'));
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
//
const app = new Vue({
    el: '#app',
    components: {
        Dashboard, ShowAgents
    }
});
