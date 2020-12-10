import App from "./App.vue";
import VueRouter from 'vue-router'
import VueProgressBar from 'vue-progressbar'
import Vue from 'vue'
import $ from 'jquery';
import axios from 'axios';
import Vuelidate from 'vuelidate'
import router from "./routes";
import Vuetify from 'vuetify'
import 'vuetify/dist/vuetify.min.css'




require('./bootstrap');

window.$ = window.jQuery = $;
window.Vue = require('vue');


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

// VUE USES
Vue.use(VueProgressBar, options, axios, Vuelidate, Vuetify, VueRouter)

// COMPONENT IMPORTS
import Dashboard from "./pages/Dashboard";
import Agent from "./pages/Agent";
import Login from "./pages/auth/Login";
import Register from "./pages/auth/Register";
import Bet from './pages/Bet'
import User from './pages/User'
import GlobalSettings from './pages/GlobalSettings'

const app = new Vue(    {
    theme: { dark: true },
    vuetify: new Vuetify(),
    el: '#app',
    router,
    components: {
        Dashboard,
        Agent,
        Login,
        Register,
        Bet,
        User,
        GlobalSettings
    },
});
