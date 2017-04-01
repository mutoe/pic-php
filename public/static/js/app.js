
/**
 * 弹出消息窗口
 * @author 杨栋森 mutoe@foxmail.com at 2017-03-26
 *
 * @param  {String} msg 弹出消息内容
 */
function amalert(msg) {
    $alert = $('#am-alert');
    $alert.find('.content').html(msg);
    $alert.modal('open');
}

/**
 * 页脚位置修正
 * @author 杨栋森 mutoe@foxmail.com at 2017-03-26
 *
 * 调用此函数后将页脚重新放置在页面底部
 */
function footer_replace() {
    var $prev = $('.footer').prev();
    var realHeight = $prev.offset().top + $prev.outerHeight() + $('.footer').outerHeight();
    if (realHeight < $(window).height()) {
        $('.footer').css('position', 'fixed');
    } else {
        $('.footer').css('position', 'relative');
    }
}

/**
 * 刷新验证码
 * @author 杨栋森 mutoe@foxmail.com at 2016-07-20
 *
 * 需要将验证码 以下面的方式放置
 * <div id="captcha-img"><img src="{:captcha_src()}" alt="captcha" /></div>
 * 点击div#captcha-img后会重新拉取验证码
 */
$('#captcha-img').on('click', function() {
    $.get('index/captcha', function(data) {
        $('#captcha-img img').attr('src', "{:captcha_src()}?t="+ new Date().getTime())
    });
});

/**
 * 获取 get 参数
 * 第二个参数为默认返回值
 */
function getUrlParam(name) {
    var def = arguments[1] ? arguments[1] : null;
    var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)"); //构造一个含有目标参数的正则表达式对象
    var r = window.location.search.substr(1).match(reg);  //匹配目标参数
    if (r != null) return unescape(r[2]); return def; //返回参数值
}
