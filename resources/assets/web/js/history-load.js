import http from 'axios';

let HistoryLoad = (option) => {
    let $el = document.querySelector(option.el);
    let historyLoad = {
        $el,
        $main: $el.querySelector(option.main || '.main'),
        pajx(url) {
            http.get(url, {headers: {'X-Requested-With': 'XMLHttpRequest'}}).then(resp => {
                historyLoad.$main.innerHTML = resp.data;
                historyLoad.listen();// 页面载入新html后需要重新监听事件
            }, (a, b, c) => {
                console.log(a, b, c);
            });
        },
        listen() {
            // 监听a标签的点击事件并拦截
            historyLoad.$el.querySelectorAll('a').forEach(node => {
                node.onclick = (e) => {
                    window.history.pushState(null, null, node.href);
                    historyLoad.pajx(node.href);
                    e.preventDefault();
                };
            });
        }
    };

    // 构造函数
    (() => {
        historyLoad.listen();

        // 监听返回事件
        window.onpopstate = () => {
            historyLoad.pajx(location.href);
        };
    })();

    return historyLoad;
};

export default HistoryLoad;
