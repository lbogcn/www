import Vue from 'vue';
import Vuex from 'vuex';

Vue.use(Vuex);

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
        setUser(state, user) {
            state.user = user;
        },
        afterLogout(state) {
            state.user = {};
            state.menus = {};
            state.verticalMenus = {};
            window.sessionStorage.removeItem('user');
        },
        loadMenus(state, app) {
            let initData = null;
            try {
                initData = JSON.parse(window.sessionStorage.getItem('init_data'));
            } catch (e) {}

            let cbk = (data) => {
                state.menus = data.menus;
                state.user = data.user;
                state.appName = data.app_name;

                let route = app.$route.path;
                let char = '/';
                let path = route.replace(new RegExp('^\\'+char+'+|\\'+char+'+$', 'g'), '').split(char);
                app.$store.commit('changeModule', path[0]);
            };

            if (!initData) {
                if (!window.sessionStorage.getItem('user')) {
                    app.$router.push({path: '/login'});
                    return;
                }

                app.$http.get('/init').then(data => {
                    cbk(data);
                    window.sessionStorage.setItem('init_data', JSON.stringify(data));
                }, err => {});
            } else {
                cbk(initData);
            }
        },
        changeModule(state, index) {
            let menu = state.menus[index] || {};
            state.verticalMenus = menu.childs || {};
            state.modulePath = index;
        },
        setUploadToken(state, uploadToken) {
            state.uploadToken = uploadToken;
        },
    }
});