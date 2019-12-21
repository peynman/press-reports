// App.js

require('./bootstrap');

import VueAxios from 'vue-axios';
import VueRouter from 'vue-router';
import axios from 'axios';
import Vue from 'vue';
import Routes from './routes';

import App from './dashboard/App.vue'

Vue.use(VueAxios, axios);
Vue.use(VueRouter);

window.VueCompiler = require('vue-template-compiler');
window.EventBus = new Vue();
window.Tokens = {
    CSRF: document.head.querySelector('meta[name="csrf-token"]'),
    JWT: document.head.querySelector('meta[name="jwt-token"]'),
};

const router = new VueRouter({
    mode: 'history',
    routes: Routes,
    base: window.DashboardConfig.basePath,
});

new Vue({
    el: '#App',
    router: router,
    render: h => h(App),
});