

require('./bootstrap');

window.Vue = require('vue');
import index from "./index";

const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.component('example-component', require('./components/ExampleComponent.vue').default);
Vue.use(VueRouter)

const app = new Vue({
    el: '#app',
    index
});
