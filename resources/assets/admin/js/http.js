import axios from 'axios';
import { Loading } from 'element-ui';
import { Message } from 'element-ui';

let loadingConfig = {
    lock: true,
    spinner: 'el-icon-loading',
    background: 'rgba(0, 0, 0, 0.7)',
};
let isLoading = true;
let apiNotice = true;

axios.interceptors.request.use(config => {
    isLoading = typeof(config.loading) === 'undefined' ? true : !!config.loading;
    apiNotice = typeof(config.apiNotice) === 'undefined' ? true : !!config.apiNotice;

    if (isLoading) {Loading.service(loadingConfig);}

    return config;
}, error => {
    return Promise.reject(error);
});

axios.interceptors.response.use(response => {
    if (isLoading) {Loading.service(loadingConfig).close();}

    let data = response.data;

    if (apiNotice && data.code !== 0) {
        Message.error(data.msg || '数据异常');
    }

    return response;
}, error => {
    if (isLoading) {Loading.service(loadingConfig).close();}
    if (apiNotice) {Message.error('服务器异常');}

    return Promise.reject(error);
});

export default axios;