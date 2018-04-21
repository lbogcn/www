import axios from 'axios';
import { Loading, Message } from 'element-ui';
import resource from './resource';
import store from '../store';

// 生成一个loading对象
let loading = (config) => {
    let loadingConfig = {
        lock: true,
        fullscreen: true,
        spinner: 'el-icon-loading',
        background: 'rgba(0, 0, 0, 0.7)',
    };

    let loadTarget = config.loadTarget || null;
    if (loadTarget) {
        loadingConfig.fullscreen = false;
        loadingConfig.target = loadTarget;
        loadingConfig.background = 'hsla(0,0%,100%,.9)';
    }

    let isLoading = typeof(config.loading) === 'undefined' ? true : !!config.loading;
    if (isLoading) {
        return Loading.service(loadingConfig);
    }

    return {close() {}};
};

// 通过配置判断是否显示错误消息
let showError = (config, msg) => {
    if (Object.assign({apiNotice: true}, config).apiNotice) {
        Message.error(msg || '出错啦');
    }
};

// 全局 code 处理
let handleGlobalCode = (code) => {
    switch (parseInt(code)) {
        case -10001:
            store.commit('logout');
            break;
    }
};

let http = axios.create();

http.resource = new resource(http);

// 配置loading
http.interceptors.request.use(config => {
    config.loading = loading(config);

    return config;
}, error => {
    return Promise.reject(error);
});

http.interceptors.response.use(resp => {
    resp.config.loading.close();

    return resp;
}, error => {
    error.config.loading.close();

    return Promise.reject(error);
});

// 解析响应数据，处理错误
http.interceptors.response.use(resp => {
    if (resp.data.code === 0) {
        return resp.data.data;
    } else {
        handleGlobalCode(resp.data.code);
    }

    let msg = resp.data.msg || '响应数据异常';

    showError(resp.config, msg);

    return Promise.reject({error: msg, config: resp.config});
}, error => {
    let msg = `${error.response.status} ${error.response.statusText}`;

    showError(error.config, msg);

    return Promise.reject({error: msg, config: error.config});
});

export default http;