export default () => {
    let scrollFunc = () => {
        let offset = document.documentElement.scrollTop / document.body.clientHeight * 100;
        document.querySelector('#header').style.backgroundPositionY = offset + "%";
    };

    // 给页面绑定滑轮滚动事件 firefox
    if (document.addEventListener) {
        document.addEventListener('DOMMouseScroll', scrollFunc, false);
    }

    // 滚动滑轮触发scrollFunc方法 ie 谷歌
    window.onmousewheel = document.onmousewheel = scrollFunc;

    scrollFunc();
};
