// App.js
require('./bootstrap');

import Vue from 'vue'
import App from './dashboard/App'

new Vue({
    el: '#App',
    render: h => h(App),
});