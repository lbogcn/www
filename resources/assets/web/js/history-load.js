import http from 'axios';

let HistoryLoad = (option) => {
    let $el = document.querySelector(option.el);
    let historyLoad = {
        $el,
        $main: $el.querySelector(option.main || '.pajx-container'),
        animation(type, callback) {
            let opacity = parseFloat(this.$main.style.opacity !== '' ? this.$main.style.opacity : 1) * 100;
            let offset = 1;
            let time = 100;

            if (type === 'in') {
                offset = (100 - opacity) / time;
            } else if (type === 'out') {
                offset = (0 - opacity) / time;
            }

            offset = offset / 100;

            let timer = setInterval(() => {
                let opacity = parseFloat(this.$main.style.opacity !== '' ? this.$main.style.opacity : 1) + offset;
                opacity = opacity < 0 ? 0 : opacity;

                this.$main.style.opacity = opacity;

                if (opacity <= 0 || opacity >= 1) {
                    clearInterval(timer);

                    if (typeof callback === 'function') {
                        callback();
                    }
                }
            }, 1);
        },
        pajx(url, scrollTop) {
            let option = {
                headers: {'X-Requested-With': 'XMLHttpRequest'},
            };

            http.interceptors.request.use(function(config) {
                historyLoad.animation('out');
                return config;
            });

            http.get(url, option).then(resp => {
                if (resp.data.code !== 0) {
                    return;
                }

                historyLoad.$main.innerHTML = resp.data.data.view;
                document.title = resp.data.data.title;
                historyLoad.animation('in');

                // 页面载入新html后需要重新监听事件
                historyLoad.listen();

                // 滚动条回到顶部
                if (scrollTop) {
                    document.documentElement.scrollTop = 0;
                }

            }, (a, b, c) => {
                console.log(a, b, c);
            });
        },
        listen() {
            // 监听a标签的点击事件并拦截
            historyLoad.$el.querySelectorAll('a').forEach(node => {
                node.onclick = (e) => {
                    // 判断是否跨域
                    if (node.href.indexOf(location.protocol + '//' + location.host + (location.port === '' || location.port === '80' ? '' : location.port)) === 0) {
                        window.history.pushState(null, null, node.href);
                        historyLoad.pajx(node.href, true);
                    } else {
                        window.open(node.href);
                    }
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
            historyLoad.pajx(location.href, false);
        };
    })();

    return historyLoad;
};

export default HistoryLoad;
