import VueRouter from 'vue-router'
import VueProgressBar from 'vue-progressbar'
import Vue from 'vue'
import $ from 'jquery';
import axios from 'axios';
import Vuelidate from 'vuelidate'
import router from "./routes";
import Vuetify from 'vuetify'
import 'vuetify/dist/vuetify.min.css'
// Vue.mixin(Permissions);
// COMPONENT IMPORTS
import Dashboard from "./pages/Dashboard";
import Agent from "./pages/Agent";
import Login from "./pages/auth/Login";
import Register from "./pages/auth/Register";
import Bet from './pages/Bet'
import User from './pages/User'
import Role from './pages/Role'
import Permission from './pages/Permission'
import GlobalSettings from './pages/GlobalSettings'
import Game from "./pages/games/Game";
import Drawperiod from "./pages/DrawPeriod";
import Device from "./pages/Device";
import ErrorNotif from "./components/Notification/ErrorNotif";
import Booth from "./pages/Booth";
import GameCreate from "./pages/games/GameCreate";
// import Permissions from "./mixins/Permissions";

require('./bootstrap');

window.$ = window.jQuery = $;
window.Vue = require('vue');
Vue.prototype.$token = document.querySelector("meta[name='csrf-token']").getAttribute('content');

// const files = require.context('./', true, /\.vue$/i)
Vue.component('pagination', require('laravel-vue-pagination'));
Vue.component('QrcodeVue', require('qrcode.vue'));

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

const app = new Vue({
    theme: {dark: true},
    vuetify: new Vuetify(),
    el: '#app',
    router,
    components: {
        GameCreate,
        Dashboard,
        Agent,
        Login,
        Register,
        Bet,
        User,
        Role,
        Game,
        Booth,
        Drawperiod,
        Permission,
        GlobalSettings,
        Device,
        ErrorNotif,

    },
});
