import axios from 'axios';
import { Loading } from 'element-ui';
import { Message } from 'element-ui';


// 生成一个loading对象
function loading(config) {
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
}

// 通过配置判断是否显示错误消息
function showError(config, msg) {
    let apiNotice = typeof(config.apiNotice) === 'undefined' ? true : !!config.apiNotice;
    if (apiNotice) {
        Message.error(msg || '出错啦');
    }
}

axios.interceptors.request.use(config => {
    config.loading = loading(config);

    return config;
}, error => {
    return Promise.reject(error);
});

axios.interceptors.response.use(function(response) {
    response.config.loading.close();

    if (response.data.code !== 0) {
        showError(response.config, response.data.msg || '响应数据异常');
    }

    return response;
}, error => {
    error.config.loading.close();

    showError(error.config, '服务器异常');

    return Promise.reject(error);
});

export default axios;