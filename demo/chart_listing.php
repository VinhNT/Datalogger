<?php
require_once '../classes/conf.php';
require_once '../classes/dao.php';
	
$dao = new Dao();

$canModifyGroup = '';
if ($_SESSION['user']->UserName != 'admin') {
	$canModifyGroup = $dao->getUserPermission($_SESSION['user']->UserName, '', 1);
	$canModifyGroup = '"'.join('","', $canModifyGroup).'"';
}


$groups  = $dao->getAllDeviceGroups($canModifyGroup);
$devices = $dao->getAllDevices($canModifyGroup);

foreach ($devices as $d) {
	$groups[$d->DeviceGroupID]->child []= $d;
}

?>

<div class="data_listing">
    <h2>All devices/groups</h2>
    <table width="100%">
    	<tr class="header">
        	<td>ID</td>
            <td>Name</td>
            <td>Action</td>
        </tr>
        
        <?php foreach ($groups as $g): ?>
        <?php if (is_array($g->child)): ?>
        <tr id="tr_group_<?= $g->GroupID; ?>" class="sub_header">
            <td><?= $g->GroupID; ?></td>
            <td><?= $g->GroupName; ?></td>
            <td></td>
        </tr>
        
        <?php foreach ($g->child as $d): ?>
        <tr id="tr_dv_<?= $d->DeviceID; ?>">
        	<td> &nbsp; - <?= $d->DeviceID; ?></td>
            <td><?= $d->DeviceName; ?></td>
            <td>
            	<a href="#" class="edit_device" data-id="<?= $d->DeviceID; ?>">Config</a>

                <input type="hidden" id="<?= $d->DeviceID; ?>_DeviceID" value="<?= $d->DeviceID; ?>" />
            </td>
        </tr>
        <?php endforeach; ?>
        <?php endif; ?>
        <?php endforeach; ?>
    </table>
    
    <br />
    <p>Want to add more devices? Click <a href="device.php">here</a></p>
</div>

<div class="edit_form_wrapper">
	<span class="intro">Select device on left to config</span>
    
    <form id="chart_config" class="hidden">
    	<h2>Config chart for device #<span id="cur_device_id"></span></h2>
    	<div id="chart_config_wrapper">
        
        </div>
        
        
        
        <br /><br />
        <p style="margin-bottom:5px;"><input type="button" value="Add more CHART" id="addMoreChart" /></p>
        <input type="button" value="Save" id="Save" />
        &nbsp;&nbsp;
        <a href="#" class="cancel">Cancel</a>
        <input type="hidden" name="curDeviceID" id="curDeviceID" />
    </form>
</div>


<script type="text/javascript">
	$(document).ready(function(){
		initConfigDeviceChart();
	});
	
	function initConfigDeviceChart(){
		$('.edit_device').click(function(e){
			var _o = $(this);

			$.get(
				'chart_detail.php?id='+_o.attr('data-id'),
				function(data){
					$('#cur_device_id').html(_o.attr('data-id'));
					$('#curDeviceID').val(_o.attr('data-id'));
					$('#chart_config_wrapper').html(data);
					
					$('.edit_form_wrapper .intro').hide();
					$('.edit_form_wrapper #chart_config').show();
				}
			);
			
			e.preventDefault();
		});
		
		$('.cancel').click(function(e){
			$('.edit_form_wrapper .intro').show();
			$('.edit_form_wrapper #chart_config').hide();
			
			e.preventDefault();
		});
		
		$('#addMoreChart').click(function(e){
			var index = $('#totalChartDetail').val();
			$.get(
				'chart_detail.php?index='+index,
				function(data){
					$('#chart_config_wrapper').append(data);
					index++;
					$('#totalChartDetail').val(index);
				}
			);
			
			e.preventDefault();
		});
		
		$('#Save').click(function(e){
			if (__checkChartConfigForm()) {
				$.get(
					'chart_dao.php',
					{
						data: $('#chart_config').serialize(),
						op: 'updateChartDetail'
					}, function(data) {
						alert(data);
						
						$('.edit_form_wrapper .intro').show();
						$('.edit_form_wrapper #chart_config').hide();
					}
				);
			} else {
				alert('Please enter required information!');
			}
			
			e.preventDefault();
		});
	}
	
	function __checkChartConfigForm() {
		$('#chart_config *').removeClass('error');
		var total=$('#totalChartDetail').val();
		var result = true;
		for (var i=0; i<total; i++) {
			var dataCol = $('#DataCol_'+i);
			if (dataCol.val()=='') {
				dataCol.addClass('error');
				result = false;
			} else {
				dataCol.removeClass('error');
			}
			
			var title = $('#Title_'+i);
			if (title.val()=='') {
				title.addClass('error');
				result = false;
			} else {
				title.removeClass('error');
			}
			
			var yAxisLabel = $('#YAxisLabel_'+i);
			if (yAxisLabel.val()=='') {
				yAxisLabel.addClass('error');
				result = false;
			} else {
				yAxisLabel.removeClass('error');
			}
		}

		return result;
	}
</script>