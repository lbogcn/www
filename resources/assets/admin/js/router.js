import Vue from 'vue';
import Router from 'vue-router';

Vue.use(Router);

// 通过菜单配置路由组件
import menu from '../../../../menu.json';

let routes = [];
let genRoute = (menus, parent) => {
    for (let module in menus) {
        if (!menus[module].childs) {
            routes.push({
                path: parent + '/' + module,
                component: require('./pages' + parent + '/' + module),
            });
        } else {
            genRoute(menus[module].childs, parent + '/' + module);
        }
    }
};

genRoute(menu, '');
routes.push(
    {path: '/login', component: require('./pages/login'),},
    {path: '/modify-password', component: require('./pages/modify-password'),},
    {path: '/article-editor/:id?', component: require('./pages/operation/content/article-editor')},
    {path: '*', redirect: '/home/dashboard',}
);

export default new Router({
    routes: routes
});