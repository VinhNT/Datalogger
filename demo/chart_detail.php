<?php
require_once '../classes/Membership.php';
require_once '../classes/conf.php';
require_once '../classes/dao.php';
require_once '../classes/util.php';
$membership = new Membership();
$membership->confirm_Member();


$chartMgr = $membership->can_User_Access_System_Settings('chartMgr');

if ($chartMgr) {
	$dao = new Dao();
	
	$id = __getParam('id', $_GET, '');
	$id = str_replace(' ', '', $_GET['id']);
	$id = convert($id);
	
	$index = __getParam('index', $_GET, 0);

	
	$chartDetails = $dao->getChartDetails($id);
	if (empty($chartDetails)) {
		$chartDetails = array(
			array(
				'DataCol' => '',
				'YAxisLabel' => '',
				'Title' => '',
				'SubTitle' => '',
				'Order' => '',
				'Status' => '',
				'Type' => ''
			)
		);	
	}
} else {
	echo "Yon don't have permission to access this area!";
	die;
}

?>

<?php if (!empty($id)): ?>
<input type="hidden" id="totalChartDetail" name="totalChartDetail" value="<?= count($chartDetails); ?>" />
<?php endif; ?>

<?php $i = 0; ?>
<?php foreach ($chartDetails as $c): ?>
<?php if ($index) $i=$index; ?>
<div class="chart_config_items clearfix">
    <div class="line share">
    Data column <span class="required">*</span><br />
    <input type="text" name="DataCol[<?= $i; ?>]" class="DataCol" value="<?= $c['DataCol']; ?>" id="DataCol_<?= $i; ?>" />
    </div>
    
    <div class="line share">
    Y-axis Label <span class="required">*</span><br />
    <input type="text" name="YAxisLabel[<?= $i; ?>]" class="YAxisLabel" value="<?= $c['YAxisLabel']; ?>" id="YAxisLabel_<?= $i; ?>" />
    </div>
    <div class="clearfloat"> </div>
    
    <div class="line share">
    Chart title <span class="required">*</span><br />
    <input type="text" name="Title[<?= $i; ?>]" id="Title_<?= $i; ?>" class="Title" value="<?= $c['Title']; ?>" />
    </div>
    
    <div class="line share">
    Sub Title<br />
    <input type="text" name="SubTitle[<?= $i; ?>]" id="SubTitle_<?= $i; ?>" class="SubTitle" value="<?= $c['SubTitle']; ?>" />
    </div>
    <div class="clearfloat"> </div>
    
    <div class="line share1">
    Chart type<br />
    <select name="Type[<?= $i; ?>]" id="Type_<?= $i; ?>" class="Type">
        <option value="line" <?php if ($c['Type'] == 'line') echo 'selected="selected"'; ?>>Line</option>
        <option value="bar" <?php if ($c['Type'] == 'bar') echo 'selected="selected"'; ?>>Bar</option>
        <option value="pie" <?php if ($c['Type'] == 'pie') echo 'selected="selected"'; ?>>Pie</option>
    </select>
    </div>
    
    <div class="line share1">
    Order<br />
    <input type="text" name="Order[<?= $i; ?>]" id="Order_<?= $i; ?>" class="Order cc" value="<?= $c['Order'] ? $c['Order'] : 0; ?>" style="width:15px;"/>
    </div>
    
    <div class="line share1">
    Enable?<br />
    <input type="checkbox" name="Status[<?= $i; ?>]" id="Status_<?= $i; ?>" class="Status" value="1" <?php if ($c['Status'] == 1) echo 'checked="checked"'; ?>  />
    </div>
    
    <div class="line share1">
    Delete?<br />
    <input type="checkbox" name="Delete[<?= $i; ?>]" class="Delete" value="1" />
    </div>
    
    <div class="clearfloat"> </div>
</div>
<?php $i++; ?>
<?php endforeach; ?>
