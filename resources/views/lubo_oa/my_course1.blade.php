<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>录播公众号</title>
    <meta name="viewport" content="width=640,user-scalable=no"/>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0, user-scalable=0, minimum-scale=1.0, maximum-scale=1.0">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="format-detection" content="telephone=no">
    <!-- 插件 -->
    <link rel="stylesheet" href="{{ URL::asset('css/style.css') }}">
    <!-- 项目页样式文件 -->
    <link rel="stylesheet" href="{{ URL::asset('css/index.css') }}">
</head>

<body>
<header class="header">
    <a href="javascript: void(0);" onClick="javascript :history.go(-1);" class="icon icon_back"></a>
    <p class="title">我的微课</p>
    <p class="icon icon_share"></p>
</header>

<div class="root_wrap">
    <script type="text/html" id="tpl">
        <ul class="course_list">
            [[each data.subject v i]]
            <li class="course_item">
                [[if v.notify > 0 ]]
                <p class="notice text_ellipsis"> [[v.notify]]</p>
                [[/if]]
                <a href="{{ URL::route('wx.video-list',['openid' => $openid]) }}?subid=[[v.id]]">
                    <img class="icon_cover" src="[[v.path]]" alt="[[v.name]]?>">
                </a>
                <p class="text_ellipsis">[[v.name]]</p>
            </li>
            [[/each]]
        </ul>
    </script>
</div>
<input type="text" value="{{ $yj_wx_token }}" id="yj_token_wx" style="display: none;">
<input type="text" value="{{ $yj_wx_name }}" id="yj_token_user_name" style="display: none;">

<footer class="footer">
    <a href="{{ URL::route('wx.bind-sucess',['openid' => $openid]) }}" class="stuName">陈晓梅</a><a class="my_video_course" href="javascript:void(0)">我的微课</a>
</footer>

<script src="{{ URL::asset('js/zeptojs1.2.comzepto.min.js') }}"></script>
<script src="{{ URL::asset('js/index.js') }}"></script>
<script src="{{ URL::asset('js/template-web.js') }}"></script>
<script>

    //如果已经绑定，跳转到成功绑定的页面，设置token值
    var yj_token_wx = $("#yj_token_wx").val();
    var yj_token_user_name = $("#yj_token_user_name").val();
    if (yj_token_wx) {
        window.localStorage.setItem('yj_wx_token',yj_token_wx);
        window.localStorage.setItem('yj_wx_user_name',yj_token_user_name);
    }


    $('.stuName').text(window.localStorage.getItem('yj_wx_user_name'));
    //重写js模板语法
    var rule = template.defaults.rules[1];
    rule.test = new RegExp(rule.test.source.replace('\{\{', '\\[\\[').replace('\}\}', '\\]\\]'));

    $.ajax({
        type: 'GET',
        url: window.MAIN_CONFIG.USEFULL_API + '/api/wx/stu/index',
        dataType: 'json',
        beforeSend: function (request) {
            request.setRequestHeader('Authorization', "Bearer " + window.localStorage.getItem('yj_wx_token'));
        },
        success: function (res) {
            if ('successful' === res.status) {
                render(res)
            } else {
                alert('网络错误！');
                return false;
            }
        }
    });

    // 渲染
    function render(res) {
        var htm = template('tpl', res);
        $('.root_wrap').html(htm)
    }
</script>

</body>

</html>
