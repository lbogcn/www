import Vue from 'vue';
import ElementUI from 'element-ui'

import http from './http';
import App from './components/app';
import store from './store';

import 'element-ui/lib/theme-chalk/index.css'
import '../less/global.less';

Vue.use(ElementUI);

Vue.prototype.$http = http;

new Vue({
    el: '#app',
    store,
    render: h => h(App)
});