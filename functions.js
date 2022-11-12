/**
 * 成绩查询公共平台 脚本文件
 * Copyright © 2014-2017 qwqaq.com 版权所有
 * Author Email: Zneiat <zneiat@163.com>
 */
 
// 页面初始化
$(document).ready(function(){
    // 数字动画 use => jquery.animateNumber.min.js
    var numShowSumAllScore = $('#numShowSumAllScore');
    var numShowChinese = $('#numShowChinese');
    var numShowMath = $('#numShowMath');
    var numShowEnglish = $('#numShowEnglish');
    numShowSumAllScore.animateNumber({ number: Number(numShowSumAllScore.html()) });
    numShowChinese.animateNumber({ number: Number(numShowChinese.html()) });
    numShowMath.animateNumber({ number: Number(numShowMath.html()) });
    numShowEnglish.animateNumber({ number: Number(numShowEnglish.html()) });
    // 判断 sidebar 开关 （已交给php）
/*    var sidebarOp = getCookie('sidebarOp');
    if(sidebarOp!==null&&sidebarOp=='hide'){
        sidebar('hide');
    }*/
    // 绑定搜索框提交事件
    var searchMainForm = $('#searchMainForm');
    searchMainForm.submit(function() {
        var actionUrl = $(this).attr('action'),
            method = $(this).attr('method'),
            serialize = $(this).serialize();
        if($('#searchFormKeyWords').val()===''){
            $('#resultContent').html('<div class="error-message"><span class="icon"><i class="fa fa-warning"></i></span>未找到数据</div>');
            return false;
        }
        $.ajax({
            type: method,
            url: actionUrl+'?'+serialize,
            data: {},
            beforeSend: function(){},
            success: function(data){
                $('#resultContent').html(data);
                sidebar('hide-temp');
            },
            error: function () {
                alert('请求失败');
            }
        });
        return false;
    });
    var searchFormKeyWords = $('#searchFormKeyWords'),
        searchFormBtn = $('#searchFormBtn');
    searchFormKeyWords.bind('input propertychange', function () {
        setTimeout(function () {
            searchFormBtn.click();
        }, 150);
    });
});

// 搜索
function search(){
    
}

// 请求
function reqPageGet(url){
    $.ajax({
        type: 'GET',
        url: url,
        data: {},
        beforeSend: function(){},
        success: function(data){
            $('#resultContent').html(data);
        },
        error: function () {
            alert('请求失败');
        }
    });
}

// 控制边栏
function sidebar(op){
    if(!op){
        return;
    }
    var sidebar = $('#sidebar');
    var secondaryNavbar = $('#secondaryNavbar');
    var wrap = $('#wrap');
    if(op==='hide'){
        sidebar.addClass('hide');
        secondaryNavbar.addClass('no-sidebar');
        wrap.addClass('no-sidebar');
        setCookie('sidebarOp','hide');
        return;
    }
    if(op==='hide-temp'){
        sidebar.addClass('hide');
        secondaryNavbar.addClass('no-sidebar');
        wrap.addClass('no-sidebar');
        return;
    }
    if(op=='show'){
        sidebar.css('display','');
        sidebar.removeClass('hide');
        secondaryNavbar.removeClass('no-sidebar');
        wrap.removeClass('no-sidebar');
        setCookie('sidebarOp','show');
        return;
    }
}

// 新增 cookie
function setCookie(name,value){ 
    var Days = 30; 
    var exp = new Date(); 
    exp.setTime(exp.getTime() + Days*24*60*60*1000); 
    document.cookie = name + "="+ escape (value) + ";expires=" + exp.toGMTString(); 
} 

// 读取 cookie
function getCookie(name) { 
    var arr,reg=new RegExp("(^| )"+name+"=([^;]*)(;|$)");
    if(arr=document.cookie.match(reg)){
        return unescape(arr[2]); 
    }else{ 
        return null;
    }
}

// 删除 cookie
function delCookie(name){
    var exp = new Date(); 
    exp.setTime(exp.getTime() - 1); 
    var cval=getCookie(name); 
    if(cval!=null){
        document.cookie= name + "="+cval+";expires="+exp.toGMTString();
    }
}

// 修改查询模式
function changeFindMode(mode,obj){
    var searchFormFindClass = $('#searchFormFindClass'),
        searchFormKeyWords = $('#searchFormKeyWords'),
        myself = $(obj);
    if(mode=='class'){
        searchFormFindClass.val('1');
        searchFormKeyWords.attr('placeholder','输入班级（按照表格已有格式）');
        myself.parent('.find-mode-select').attr('title','切换搜索模式（当前模式：班级）');
    }else if(mode=='person'){
        searchFormFindClass.val('0');
        searchFormKeyWords.attr('placeholder','输入姓名或考号（敲空格来查询多个）');
        myself.parent('.find-mode-select').attr('title','切换搜索模式（当前模式：个人）');
    }
    myself.parent('.find-mode-select').find('button').removeClass('active');
    myself.addClass('active');
}

console.log("%c Designed by Zneiat <zneiat@163.com>", "color: #31b0d5;background: #FFF;border-radius: 2px;font-size:2em;font-family: \"Helvetica Neue\", \"PingFangSC-Light\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"WenQuanYi Micro Hei\", sans-serif;");