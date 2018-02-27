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
                app.$http.get('/init').then(resp => {
                    if (resp.data.code === 0) {
                        cbk(resp.data.data);
                        window.sessionStorage.setItem('init_data', JSON.stringify(resp.data.data));
                    }
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