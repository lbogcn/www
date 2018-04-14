import Vue from 'vue';
import ElementUI from 'element-ui'

import http from './http';
import App from './app';
import store from './store';

// 自定义组件
import SearchForm from './components/search-form';
import ElButtonSearch from './components/el-button-search';
import ElButtonCreate from './components/el-button-create';
import ElButtonDelete from './components/el-button-delete';
import ElButtonUpload from './components/el-button-upload';

import 'element-ui/lib/theme-chalk/index.css';
import '../less/global.less';
import '../../less/article-body.less';

Vue.use(ElementUI, {size: 'mini'});

Vue.prototype.$http = http;

Vue.component('search-form', SearchForm);
Vue.component('el-button-search', ElButtonSearch);
Vue.component('el-button-create', ElButtonCreate);
Vue.component('el-button-delete', ElButtonDelete);
Vue.component('el-button-upload', ElButtonUpload);

new Vue({
    el: '#app',
    store,
    render: h => h(App)
});