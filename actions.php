<?php
require('functions.php');

$op = @addslashes(trim($_GET['op']));

switch ($op) {
    // 'query 请求栗子'=>'http://127.0.0.1/scoreQuery/actions.php?op=query&form=八年级秋季半期&keyWords=小明&limit=1&$offset=0&withoutUi=0'
    case 'query':
        try{
            $getForm = handleGetForm();
        }catch(Exception $Exception){
            // 抓取 throw new Exception('message') 产生的错误
            $errorMsg = $Exception->getMessage();
            returnError($errorMsg);
        }
        $formName = getDbMap('getFormNameByLable',$getForm);
        $keyWords = @addslashes(htmlspecialchars(trim($_GET['keyWords'])));
        $keyWords = @explode(' ',$keyWords);
        $offset = @intval(trim($_GET['offset']));
        $offset = !empty($offset)?$offset:0;
        $limit = @intval(trim($_GET['limit']));
        $limit = !empty($limit)?$limit:50;
        $withoutUi = (bool)@$_GET['withoutUi']; // 0或1
        $order = @addslashes(trim($_GET['order']));
        $order = !empty($order)?$order:'sumScore';
        $findClass = (bool)@$_GET['findClass'];
        define('AllowExecutionRequireFile', true);
        // 循环拼接
        $searchSqlStr = '';
        if(!$findClass){
            for($i=0;$i<count($keyWords);$i++){
                $searchSqlStr .= "`".getDbMap('getFieldName',$formName,'name')."` LIKE '%".$keyWords[$i]."%'||`".getDbMap('getFieldName',$formName,'testId')."` = '".$keyWords[$i]."'||";
            }
        }else{
            $searchSqlStr .= "`".getDbMap('getFieldName',$formName,'school')."` = '".$keyWords[0]."'&&`".getDbMap('getFieldName',$formName,'class')."` = '".$keyWords[1]."'||";
        }
        $searchSqlStr = trim($searchSqlStr,'||');
        $sql = "SELECT * FROM `$formName` WHERE ".$searchSqlStr." ORDER BY `".getDbMap('getFieldName',$formName,$order)."` DESC LIMIT $offset,$limit";
        $commonListData = mysqlToArray(mysqli_query($con, $sql));
        // 关键词着色
        /*$commonListData = [];
        foreach ($listData as $key=>$value) {
            $nameField = getDbMap('getFieldName',$formName,'name');
            $value[$nameField] = preg_replace("/$keyWords/i", "<font color=\"red\"><b>$keyWords</b></font>", $value[$nameField]);
            $testIdField = getDbMap('getFieldName',$formName,'testId');
            $value[$testIdField] = preg_replace("/$keyWords/i", "<font color=\"red\"><b>$keyWords</b></font>", $value[$testIdField]);
            $commonListData[] = $value;
        }*/
        if(!$withoutUi){
            // 启动关键词着色
            $commonListSearchMode = true;
            require('./commonList.php');
        }else{
            echo json_encode(['success'=>'true','total'=>count($commonListData),'query-data'=>$commonListData]);
        }
        break;
    default:
        returnError('What Are You Doing?');
        break;
}

mysqli_close($con);