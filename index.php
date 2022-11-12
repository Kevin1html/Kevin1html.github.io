<?php require('functions.php') ?>
<?php
$errorMsg = null;
$showSidebar = true;
// 获取 GET 数据
try{
    $getForm = handleGetForm();
}catch(Exception $Exception) {
    // 抓取 throw new Exception('message') 产生的错误
    $errorMsg = $Exception->getMessage();
    $showSidebar = false;
}
$formName = getDbMap('getFormNameByLable',$getForm);
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <title><?= cityName ?><?= $getForm ?>考试 成绩查询公共平台<?= afterTitle ?></title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <!--<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, user-scalable=no">-->
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="robots" content="nofollow">
    <meta name="author" content="qwqaq.com,zneiat@163.com">
    <meta name="data-desc" content="<?= $getForm ?>" />
    <link href="/common/favicon.png" rel="shortcut icon" type="image/x-icon">
    <link href="./style.css" rel="stylesheet" type="text/css">
    <link href="//cdn.bootcss.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" type="text/css">
</head>
<body>
<div class="top-header">
    <div class="main-navbar">
        <div class="inner">
            <div class="left">
                <h1 class="title"><span style="color: #FFF;"><?= cityName ?><?= $getForm ?>考试</span> 成绩查询公共平台</h1>
            </div>
            <div class="right">
                <div class="find-mode-select" title="切换搜索模式（当前模式：个人）">
                    <button onclick="changeFindMode('class',this)">班级</button>
                    <button onclick="changeFindMode('person',this)" class="active">个人</button>
                </div>
                <form class="search-form" id="searchMainForm" action="actions.php" method="get">
                    <input type="text" class="query-keywords" name="keyWords" placeholder="输入姓名或考号（敲空格来查询多个）" id="searchFormKeyWords" autocomplete="off" spellcheck="false" required="required">
                    <input type="hidden" name="op" value="query">
                    <input type="hidden" name="form" value="<?= $getForm ?>">
                    <input type="hidden" name="findClass" value="0" id="searchFormFindClass">
                    <button type="submit" class="input-btn" id="searchFormBtn"><span class="search-icon">搜索</span></button>
                </form>
            </div>
        </div>
    </div>
    <div class="secondary-navbar<?= ($_COOKIE['sidebarOp']=='hide'||!$showSidebar)?' no-sidebar':'' ?>" id="secondaryNavbar">
        <div class="inner">
            <ul>
                <?php if($showSidebar): ?>
                <li><a href="javascript:void(0)" onclick="sidebar('show')"><i class="fa fa-angle-double-right"></i></a></li>
                <?php endif; ?>
                <li><a href="javascript:alert('现在我懒得写这个功能了，以后再说 ┑(￣Д ￣)┍')">申请隐藏成绩</a></li>
                <li><a href="javascript:alert('正在开发中... 可能你需要等待很久很久 ┑(￣Д ￣)┍')">公共资源下载</a></li>
                <li><a href="https://github.com/Zneiat" target="_blank">作者 GitHub</a></li>
            </ul>
        </div>
    </div>
</div>
<div class="wrap<?= ($_COOKIE['sidebarOp']=='hide'||!$showSidebar)?' no-sidebar':'' ?>" id="wrap">
    <div class="main-content">
        <?php if($errorMsg===null&&$showSidebar): ?>
        <?php if($showSidebar): ?>
        <div class="left sidebar" id="sidebar"<?= ($_COOKIE['sidebarOp']=='hide'||!$showSidebar)?' style="display: none"':'' ?>>
            <div class="inner">
                <div class="head-action-btn">
                    <button onclick="sidebar('hide')" class="hide-btn"><i class="fa fa-angle-double-left"></i></button>
                </div>
                <?php
                $countdownDatetime1 = date_create(date('Y-m-d'));  
                $countdownDatetime2 = date_create('2017-2-19');  
                $countdownInterval = date_diff($countdownDatetime1, $countdownDatetime2);
                $countdownDays = $countdownInterval->format('%R%a');
                if(intval($countdownDays)>0||intval($countdownDays)==0):
                ?>
                <div class="widget countdown">
                    <span class="title">距离开学还有</span>
                    <?php if(intval($countdownDays)!=0): ?>
                    <?php
                    // 计算长度
                    $htmlHowLong = intval(round(intval($countdownDays)/44,2)*100);
                    $showColor = '';
                    $dontSetLong = false;
                    if($htmlHowLong>=75){
                        $showColor = '7bcfbf';
                    }else if($htmlHowLong<75&&$htmlHowLong>=50){
                        $showColor = 'caf741';
                    }else if($htmlHowLong<50&&$htmlHowLong>=25){
                        $showColor = 'f7f141';
                    }else if($htmlHowLong<25){
                        $showColor = 'ff6d5d';
                        $dontSetLong = true;
                    }
                    ?>
                    <span class="count" style="width: <?= !$dontSetLong?$htmlHowLong:'100' ?>%;<?= !$dontSetLong?'border-left-color':'border-left:0;background' ?>: #<?= $showColor ?>;color: #<?= !$dontSetLong?$showColor:'FFF' ?>;"><?= str_replace('+','',$countdownDays) ?> <span class="sub">天</span></span>
                    <?php else: ?>
                    <span class="count" style="border-left: 0;color: #ff8f00;background: #FFF;width: 100%;">骚年，你没好日子过了</span>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
                <div class="widget message">
                    <h2 class="title">公告</h2>
                    <p style="text-align: center;">"八年级冬季期末考试" 成绩已公布<br>过去的都会过去 该来的都在路上<br>春节即将到来 在此提前祝大家春节快乐<br>新的一年 大家要更加努力啊！加油<br><span style="vertical-align: middle;margin-right: 5px;margin-top: 5px;">2017-1</span></p>
                </div>
                <div class="widget select-list">
                    <h2 class="list-lable">数据选择列表</h2>
                    <ul>
                        <?php foreach(getDbMap('formListLable') as $key=>$item): ?>
                        <li<?= $item==$getForm?' class="active"':'' ?>><a href="?form=<?= $item ?>"><?= $item ?></a></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>
        <?php endif; ?>
        <div class="right" id="mainContent">
            <div class="overall-statistics">
                <div class="num-show">
                    <div class="item">
                        <?php
                        $numSumStudents = @mysqli_fetch_array(mysqli_query($con, "SELECT COUNT(*) FROM `$formName`"))[0];
                        $numSumAllScore = ceil(@mysqli_fetch_array(mysqli_query($con, "SELECT SUM(".getDbMap('getFieldName',$formName,'sumScore').") FROM `$formName`"))[0]);
                        ?>
                        <div class="inner" title="考生数 <?= $numSumStudents ?>，总成绩 <?= $numSumAllScore ?>">
                            <span class="num"><span id="numShowSumAllScore"><?= $numSumAllScore ?></span></span>
                            <span class="lable" style="cursor: pointer" onclick="alert('<?= $getForm ?>考试 考生数 <?= $numSumStudents ?>，总成绩 <?= $numSumAllScore ?>')">全市成绩总和</span>
                        </div>
                    </div>
                    <div class="item">
                        <div class="inner">
                            <?php
                            $numShowChinese = ceil(@mysqli_fetch_array(mysqli_query($con, "SELECT AVG(".getDbMap('getFieldName',$formName,'chinese').") FROM `$formName`"))[0]);
                            ?>
                            <span class="num"><span id="numShowChinese"><?= $numShowChinese ?></span></span>
                            <span class="lable">全市语文平均分</span>
                        </div>
                    </div>
                    <div class="item">
                        <div class="inner">
                            <?php
                            $numShowMath = ceil(@mysqli_fetch_array(mysqli_query($con, "SELECT AVG(".getDbMap('getFieldName',$formName,'math').") FROM `$formName`"))[0]);
                            ?>
                            <span class="num"><span id="numShowMath"><?= $numShowMath ?></span></span>
                            <span class="lable">全市数学平均分</span>
                        </div>
                    </div>
                    <div class="item">
                        <div class="inner">
                            <?php
                            $numShowEnglish = ceil(@mysqli_fetch_array(mysqli_query($con, "SELECT AVG(".getDbMap('getFieldName',$formName,'english').") FROM `$formName`"))[0]);
                            ?>
                            <span class="num"><span id="numShowEnglish"><?= $numShowEnglish ?></span></span>
                            <span class="lable">全市英语平均分</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="query-result" id="resultContent">
                <div class="result-head">
                    <h2 class="title"><i class="fa fa-diamond"></i> 全市前50名 <span class="title-sub"><?= @$getForm ?></span></h2>
                </div>
                <?php
                define('AllowExecutionRequireFile', true);
                $commonListData = mysqlToArray(mysqli_query($con, "SELECT * FROM `$formName` ORDER BY `".getDbMap('getFieldName',$formName,'sumScore')."` DESC LIMIT 0,50"));
                require('./commonList.php');
                ?>
            </div>
        </div>
        <?php else: ?>
            <div class="error-message">
                <span class="icon"><i class="fa fa-warning"></i></span>
                <?= $errorMsg ?>
            </div>
        <?php endif; ?>
    </div>
</div>
<!-- Script -->
<script src="./jquery.min.js"></script>
<script src="./jquery.animateNumber.min.js"></script>
<script src="./functions.js"></script>
</script>
</body>
</html>