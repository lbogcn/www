import Vue from 'vue';
import Vuex from 'vuex';
import router from './router';
import http from './http/index';

Vue.use(Vuex);

let changeModule = (state, index) => {
    let menu = state.menus[index] || {};
    state.verticalMenus = menu.childs || {};
    state.modulePath = index;
};

let init = (state) => {
    if (!window.sessionStorage.getItem('login')) {
        router.push({path: '/login'});
        return;
    }

    let initData = null;
    try {
        initData = JSON.parse(window.sessionStorage.getItem('init_data'));
    } catch (e) {}

    let cbk = (data) => {
        state.menus = data.menus;
        state.appName = data.app_name;
        state.user = data.user;

        let char = '/';
        let path = router.app.$route.path;
        path = path.replace(new RegExp('^\\'+char+'+|\\'+char+'+$', 'g'), '').split(char);
        changeModule(state, path[0]);
    };

    if (!initData) {
        http.get('/init').then(data => {
            cbk(data);
            window.sessionStorage.setItem('init_data', JSON.stringify(data));
        }, err => {});
    } else {
        cbk(initData);
    }
};

export default new Vuex.Store({
    state: {
        menus: {},
        verticalMenus: {},
        modulePath: '',
        user: {},
        appName: '',
        uploadToken: null,
        resources: Object.assign({}, {
            KeyValue: '/key-value',
            LogOperation: '/log/operation',
            LogError: '/log/error',
            PermissionNode: '/permission/node',
            PermissionRole: '/permission/role',
            PermissionUser: '/permission/user',
            PermissionMenu: '/permission/menu',
            Article: '/article',
            ArticleCategory: '/article/category',
            Cover: '/cover',
            Message: '/message',
            Questionnaires: '/questionnaires',
        }),
    },
    mutations: {
        setMenus(state, menus) {
            state.menus = menus;
        },
        setVerticalMenus(state, verticalMenus) {
            state.verticalMenus = verticalMenus;
        },
        setModulePath(state, modulePath) {
            state.modulePath = modulePath;
        },
        logout(state) {
            state.menus = {};
            state.verticalMenus = {};
            state.user = {};
            window.sessionStorage.removeItem('login');
            window.sessionStorage.removeItem('init_data');
            router.push({path: '/login'});
        },
        login(state) {
            window.sessionStorage.removeItem('login');
            window.sessionStorage.setItem('login', '1');
            init(state);
        },
        init,
        changeModule,
        setUploadToken(state, uploadToken) {
            state.uploadToken = uploadToken;
        },
    }
});