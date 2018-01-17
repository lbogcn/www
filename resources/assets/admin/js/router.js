import Vue from 'vue';
import Router from 'vue-router';

Vue.use(Router);

export default new Router({
    routes: [
        {path: '/home/dashboard', component: require('./pages/home/dashboard'),},
        {path: '/process_center/user/server', component: require('./pages/process_center/user/server'),},

        {path: '/login', component: require('./pages/login'),},

        // 默认跳首页（404）
        {path: '*', redirect: '/home/dashboard',},
    ]
});