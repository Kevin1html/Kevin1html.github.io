<?php
require('config.php');

// 连接 Mysql 数据库
$con = mysqli_connect(mysqlHost,mysqlUser,mysqlPasswd,mysqlDb); 
if (mysqli_connect_errno($con)){ 
    returnError("连接 MySQL 失败: " . mysqli_connect_error()); 
}

// 检测数据映射表文件是否存在
if(!file_exists(dbMapJsonPath)){
    returnError("数据映射表文件丢失"); 
}
// 解析数据映射表文件
$dbMapJson = @json_decode(file_get_contents(dbMapJsonPath),true);
if(!is_array($dbMapJson)){
    returnError("数据映射表文件解析失败"); 
}

// 快速查询
function fastQuery(mysqli $con,$from,$where=null,$limit=null,$count=false,$after=''){
    if((!is_null($where)&&!is_array($where))){
        return false;
    }
    if(!is_null($where)){
        $whereStr = 'WHERE ';
        foreach($where as $key=>$val){
            $whereStr .= "(`$key` = '$val') AND";
        }
        $whereStr = trim($whereStr,' AND');
    }
    if(!is_null($limit)&&is_array($limit)){
        $limitStr = 'LIMIT '.$limit[0].','.$limit[1];
    }
    if(!is_null($limit)&&!is_array($limit)){
        $limitStr = "LIMIT 0,$limit";
    }
    if($count){
        return @mysqli_fetch_array(mysqli_query($con, "SELECT COUNT(*) FROM `$from` $whereStr"))[0];
    }
    return mysqlToArray(mysqli_query($con, "SELECT * FROM `$from` $whereStr $limitStr $after"));
}

// 获取数据库映射
// op = formList/formListLable/getField
// getDbMap('getFieldName','7thGradeFinalTest_201607','ciRanking'); return '市排名';
function getDbMap($op=null,$p1=null,$p2=null,$p3=null){
    global $dbMapJson;
    switch ($op) {
        case 'formList':
            $arr = [];
            foreach($dbMapJson['data'] as $key=>$val){
                $arr[$key] =  $val['lable'];
            }
            return $arr;
            break;
        case 'formListTurnOver':
            $arr = [];
            foreach($dbMapJson['data'] as $key=>$val){
                $arr[$val['lable']] =  $key;
            }
            return $arr;
            break;
        case 'formListLable':
            $arr = [];
            foreach($dbMapJson['data'] as $key=>$val){
                $arr[] = $val['lable'];
            }
            return $arr;
            break;
        case 'getFormNameByLable':
            if(is_null($p1)){
                return null;
            }
            return @getDbMap('formListTurnOver')[$p1];
            break;
        case 'getField':
            if(is_null($p1)){
                return null;
            }
            return @$dbMapJson['data'][$p1]['field'];
            break;
        case 'getFieldName':
            if(is_null($p1)||is_null($p2)){
                return null;
            }
            return @$dbMapJson['data'][$p1]['field'][$p2];
            break;
        case null:
            return $dbMapJson['data'];
            break;
        default:
            return null;
            break;
    }
}

// 输出错误并结束继续运行
function returnError($content){
    if(isAjax()){
        header("Content-type:text/json;charset=utf-8");
        echo json_encode(['success'=>false,'msg'=>'程序内部错误']);
        die();
    }
    header("Content-type:text/html;charset=utf-8");
    echo '<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, user-scalable=no"><div style="text-align: center;font-family: &quot;Helvetica Neue&quot;, &quot;PingFangSC-Light&quot;, &quot;Hiragino Sans GB&quot;, &quot;Microsoft YaHei&quot;, &quot;WenQuanYi Micro Hei&quot;, sans-serif;font-size: 29px;color: #ff0000;padding: 90px 10px;">程序错误：'.$content.'<p style="color: #a1a8af;font-size: 15px;border-top: 1px solid #F4F4F4;padding-top: 15px;">若错误持续存在，请向我反馈，QQ: 1149527164</p></div>';
    die();
}

// 判断是不是 Ajax 请求
function isAjax() {
    if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) ) {
        if('xmlhttprequest' == strtolower($_SERVER['HTTP_X_REQUESTED_WITH']))
            return true;
    }
    return false;
}

// 数据查询转数组
function mysqlToArray($query){
    $data = [];
    while($row = @mysqli_fetch_array($query)){
        $data[] = $row;
    }
    return $data;
}

// 处理 $_GET['form']
function handleGetForm(){
    $getForm = @addslashes($_GET['form']);
    if(!empty($getForm)&&empty(getDbMap('getFormNameByLable',$getForm))){
        throw new Exception('未找到数据');
    }
    if(empty($getForm)&&!empty(@getDbMap('formListLable')[0])){
        $getForm = getDbMap('formListLable')[0];
    }
    return $getForm;
}

// 构建市排名字段 解决并列问题 handleDbCity('8thGradeHalfTest_201610','总分','市排名')
function buildDbCityRankingNum($from,$fieldSumScore,$fieldCiRanking){
    global $con;
    $Data = mysqlToArray(mysqli_query($con, "SELECT * FROM `$from` ORDER BY `$fieldSumScore` DESC"));
    $tmpScore = 0; // 缓存分数
    $tmpRankingNum = 0; // 缓存排名数
    $successNum = 0; // 操作成功数
    foreach ($Data as $item) {
        if($tmpScore==$item[$fieldSumScore]){
           $setRanking = $tmpRankingNum;
        }else{
            $tmpRankingNum ++;
            $setRanking = $tmpRankingNum;
        }
        if(mysqli_query($con, "UPDATE `$from` SET `$fieldCiRanking`='$setRanking' WHERE id=".$item['id'])){
            $successNum++;
        }
        $tmpScore = $item[$fieldSumScore];
    }
    return $successNum;
}

// 引入文件？？
function requireFile($path,$par){
    if(!is_array($par)){
        return;
    }
    if(!file_exists($path)){
        return;
    }
    $GLOBALS['FunctionRequireFile'] = true;
    foreach($par as $key=>$val){
        $GLOBALS[$key] = $val;
    }
    require($path);
}