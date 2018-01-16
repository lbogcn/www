import Vue from 'vue';
import ElementUI from 'element-ui'
import Vuex from 'vuex';
import VueResource from 'vue-resource';
import 'element-ui/lib/theme-chalk/index.css'
import '../less/global.less';
import App from './components/app';

Vue.use(Vuex);
Vue.use(ElementUI);
Vue.use(VueResource);

const store = new Vuex.Store({
    state: {
    }
});

new Vue({
    el: '#app',
    store,
    render: h => h(App)
});