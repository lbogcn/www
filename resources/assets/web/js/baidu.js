// 百度统计
let _hmt = _hmt || [];
(function () {
    let hm = document.createElement("script");
    hm.src = "https://hm.baidu.com/hm.js?d0754ebee2c0ba0c2983368f42b0462c";
    let s = document.getElementsByTagName("script")[0];
    s.parentNode.insertBefore(hm, s);
})();

// 百度站长自动推送
(function () {
    let bp = document.createElement('script');
    let curProtocol = window.location.protocol.split(':')[0];
    if (curProtocol === 'https') {
        bp.src = 'https://zz.bdstatic.com/linksubmit/push.js';
    }
    else {
        bp.src = 'http://push.zhanzhang.baidu.com/push.js';
    }
    let s = document.getElementsByTagName("script")[0];
    s.parentNode.insertBefore(bp, s);
})();