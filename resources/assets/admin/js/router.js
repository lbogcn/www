import Vue from 'vue';
import Router from 'vue-router';

Vue.use(Router);

export default new Router({
    routes: [
        {path: '/home/dashboard', component: require('./pages/home/dashboard'),},
        {path: '/process_center/user/server', component: require('./pages/process_center/user/server'),},

        {path: '/control/permission/user', component: require('./pages/control/permission/user'),},
        {path: '/control/permission/role', component: require('./pages/control/permission/role'),},
        {path: '/control/permission/node', component: require('./pages/control/permission/node'),},

        {path: '/login', component: require('./pages/login'),},

        // 默认跳首页（404）
        {path: '*', redirect: '/home/dashboard',},
    ]
});