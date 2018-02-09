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
            app.$http.get('/init').then(resp => {
                if (resp.data.code === 0) {
                    state.menus = resp.data.data.menus;
                    state.user = resp.data.data.user;
                    state.appName = resp.data.data.app_name;

                    let route = app.$route.path;
                    let char = '/';
                    let path = route.replace(new RegExp('^\\'+char+'+|\\'+char+'+$', 'g'), '').split(char);
                    app.$store.commit('changeModule', path[0]);
                }
            }, err => {});
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