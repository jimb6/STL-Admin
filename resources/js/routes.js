import VueRouter from "vue-router";
import GeneralBetComponents from "./components/GeneralBetComponents";
import Home from "./components/Home";

let routes = [
    {
        path: '/',
        name: 'home',
        components: Home
    },
    {
        path: '/bet-transactions',
        name: 'bet',
        components: GeneralBetComponents
    }
];




const router = new VueRouter({
    mode: 'history',
    routes
});

export default router;
