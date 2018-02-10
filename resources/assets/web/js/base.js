window.getReleaseTime = (dom, t) => {
    let s = Date.parse((new Date()).toString()).toString().substring(0, 10) - t;
    let val = 0;
    let unit = '';
    if (s < 60) {
        val = s;
        unit = '秒';
    }
    else if (s < 60 * 60) {
        val = s / 60;
        unit = '分钟';
    }
    else if (s < 24 * 3600) {
        val = s / 3600;
        unit = '小时';
    }
    else if (s < 7 * 24 * 3600) {
        val = s / 24 / 3600;
        unit = '天';
    }
    else if (s < 30 * 24 * 3600) {
        val = s / 7 / 24 / 3600;
        unit = '周';
    }
    else if (s < 365 * 24 * 3600) {
        val = s / 30 / 24 / 3600;
        unit = '个月';
    }
    else {
        return document.querySelector(dom).innerHTML = '发布';
    }
    return document.querySelector(dom).innerHTML = parseInt(val).toString() + unit + '前发布';
};