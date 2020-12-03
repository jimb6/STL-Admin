import VueRouter from "vue-router";
import Dashboard from "./pages/Dashboard";

let routes = [
    {
        path: '/',
        name: 'home',
        components: Dashboard
    },
];

const router = new VueRouter({
    mode: 'history',
    routes
});

export default router;
