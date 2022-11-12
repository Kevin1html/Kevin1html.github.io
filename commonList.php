<?php if(!defined('AllowExecutionRequireFile')){header("HTTP/1.1 404 Not Found");exit;} ?>
<?php if(@$commonListSearchMode): ?>
<div class="result-head">
    <?php
    // 拆分显示关键词
    $buildHtmlCode = '';
    foreach(@$keyWords as $item){
        $buildHtmlCode .= '<span class="keyword-item">'.$item.'</span>，';
    }
    $buildHtmlCode = trim($buildHtmlCode,'，');
    ?>
    <h2 class="title"><i class="fa fa-search"></i> 搜索结果 <span class="title-sub"><?= @$getForm ?>，关键词：<?= $buildHtmlCode ?></span></h2>
</div>
<?php endif; ?>
<div class="grades-table table-responsive">
    <?php
    $myselfUrl = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING'];
    ?>
    <table class="table table-striped">
    <thead>
        <tr>
            <th width="3%">#</th>
            <th width="11%">姓名</th>
            <th width="25%">班级</th>
            <th width="13%"<?php if(@$commonListSearchMode): ?> onclick="reqPageGet('<?= $myselfUrl.'&order=sumScore' ?>')" class="sort-link<?= $order=='sumScore'?' active':''; ?>"<?php endif; ?>>总分 (市排名)</th>
            <th width="6%"<?php if(@$commonListSearchMode): ?> onclick="reqPageGet('<?= $myselfUrl.'&order=chinese' ?>')" class="sort-link<?= $order=='chinese'?' active':''; ?>"<?php endif; ?>>语文</th>
            <th width="6%"<?php if(@$commonListSearchMode): ?> onclick="reqPageGet('<?= $myselfUrl.'&order=math' ?>')" class="sort-link<?= $order=='math'?' active':''; ?>"<?php endif; ?>>数学</th>
            <th width="6%"<?php if(@$commonListSearchMode): ?> onclick="reqPageGet('<?= $myselfUrl.'&order=english' ?>')" class="sort-link<?= $order=='english'?' active':''; ?>"<?php endif; ?>>英语</th>
            <th width="6%"<?php if(@$commonListSearchMode): ?> onclick="reqPageGet('<?= $myselfUrl.'&order=physics' ?>')" class="sort-link<?= $order=='physics'?' active':''; ?>"<?php endif; ?>>物理</th>
            <th width="6%"<?php if(@$commonListSearchMode): ?> onclick="reqPageGet('<?= $myselfUrl.'&order=politics' ?>')" class="sort-link<?= $order=='politics'?' active':''; ?>"<?php endif; ?>>思品</th>
            <th width="6%"<?php if(@$commonListSearchMode): ?> onclick="reqPageGet('<?= $myselfUrl.'&order=history' ?>')" class="sort-link<?= $order=='history'?' active':''; ?>"<?php endif; ?>>历史</th>
            <th width="6%"<?php if(@$commonListSearchMode): ?> onclick="reqPageGet('<?= $myselfUrl.'&order=biology' ?>')" class="sort-link<?= $order=='biology'?' active':''; ?>"<?php endif; ?>>生物</th>
            <th width="6%"<?php if(@$commonListSearchMode): ?> onclick="reqPageGet('<?= $myselfUrl.'&order=geography' ?>')" class="sort-link<?= $order=='geography'?' active':''; ?>"<?php endif; ?>>地理</th>
        </tr>
    </thead>
    <tbody>
    <?php
    // 循环叠加
    $sum['sumScore'] = 0;
    $sum['chinese'] = 0;
    $sum['english'] = 0;
    $sum['math'] = 0;
    $sum['physics'] = 0;
    $sum['politics'] = 0;
    $sum['history'] = 0;
    $sum['biology'] = 0;
    $sum['geography'] = 0;
    $count = 0;
    ?>
    <?php foreach($commonListData as $num=>$item): ?>
    <tr>
        <td><?= $num+1 ?></td>
        <td><?= whetherSearch($item[getDbMap('getFieldName',$formName,'name')]) ?></td>
        <td><?= $item[getDbMap('getFieldName',$formName,'school')] ?> <?= $item[getDbMap('getFieldName',$formName,'class')] ?></td>
        <td><?= $item[getDbMap('getFieldName',$formName,'sumScore')] ?> (<?= $item[getDbMap('getFieldName',$formName,'ciRanking')] ?>)</td>
        <td><?= ceil($item[getDbMap('getFieldName',$formName,'chinese')]) ?></td>
        <td><?= ceil($item[getDbMap('getFieldName',$formName,'math')]) ?></td>
        <td><?= ceil($item[getDbMap('getFieldName',$formName,'english')]) ?></td>
        <?php $physicsScore = $item[getDbMap('getFieldName',$formName,'physics')]; ?>
        <td><?= $physicsScore?ceil($physicsScore):'<span style="/*text-align:center;display: block;*/">-</span>' ?></td>
        <td><?= ceil($item[getDbMap('getFieldName',$formName,'politics')]) ?></td>
        <td><?= ceil($item[getDbMap('getFieldName',$formName,'history')]) ?></td>
        <td><?= ceil($item[getDbMap('getFieldName',$formName,'biology')]) ?></td>
        <td><?= ceil($item[getDbMap('getFieldName',$formName,'geography')]) ?></td>
    </tr>
    <?php
    foreach($sum as $key=>$val){
        $sum[$key] += ceil($item[getDbMap('getFieldName',$formName,$key)]);
    }
    $count ++;
    ?>
    <?php endforeach; ?>
    <?php if($count>0): ?>
    <tr>
        <th></th>
        <td colspan="2" class="am-text-center">平均分：</td>
        <?php foreach($sum as $key=>$val): ?>
            <td title="<?= $val/$count ?>"><?= round($val/$count,2) ?></td>
        <?php endforeach; ?>
    </tr>
    <?php endif; ?>
    </tbody>
    </table>
</div>
<?php if(@$commonListSearchMode): ?>

<?php endif; ?>
<?php
function whetherSearch($str){
    if(empty($str)){
        return $str;
    }
    global $commonListSearchMode;
    global $keyWords;
    if(@$commonListSearchMode&&!empty($keyWords)){
        return colouring($keyWords,$str);
    }else{
        return $str;
    }
}
// 关键词着色
function colouring($keyWords,$str){
    $str = trim($str);
    foreach($keyWords as $item){
        $str = preg_replace("/$item/i", "<font color=\"#ff4b4b\"><b>$item</b></font>", $str);
    }
    return $str;
}
?>