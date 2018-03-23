import http from 'axios';

let HistoryLoad = (option) => {
    let $el = document.querySelector(option.el);
    let historyLoad = {
        $el,
        $main: $el.querySelector(option.main || '.pajx-container'),
        animation(type) {
            this.$main.classList.add('animation-' + type);
        },
        pajx(url, scrollTop) {
            let option = {
                headers: {'X-Requested-With': 'XMLHttpRequest'},
            };

            http.interceptors.request.use(function(config) {
                historyLoad.animation('hide');
                return config;
            });

            http.get(url, option).then(resp => {
                if (resp.data.code !== 0) {
                    return;
                }

                historyLoad.$main.innerHTML = resp.data.data.view;
                document.title = resp.data.data.title;
                historyLoad.animation('show');
                document.querySelector('.circle-loader').classList.add('hide');

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

            window.addEventListener('animationend', function (e) {
                if (e.target === historyLoad.$main) {
                    if (historyLoad.$main.classList.contains('animation-hide')) {
                        // historyLoad.$main.innerHTML = '';
                        historyLoad.$main.style.opacity = 0;
                        document.querySelector('.circle-loader').classList.remove('hide');
                    }

                    if (historyLoad.$main.classList.contains('animation-show')) {
                        historyLoad.$main.style.opacity = 1;
                    }

                    historyLoad.$main.classList.remove('animation-hide');
                    historyLoad.$main.classList.remove('animation-show');
                }
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

        setInterval(() => {
            if (document.body.offsetHeight < window.innerHeight) {
                document.querySelector('.copyright').classList.add('down');
            } else {
                document.querySelector('.copyright').classList.remove('down');
            }
        }, 100);
    })();

    return historyLoad;
};

export default HistoryLoad;
