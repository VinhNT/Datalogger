<?php
require_once '../classes/Membership.php';
require_once '../classes/conf.php';
require_once '../classes/dao.php';

$dao = new Dao();
$membership = new Membership();	
$groups  = $dao->getAllDeviceGroups();
$devices = $dao->getAllDevices();

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
        <tr id="tr_group_<?= $g->GroupID; ?>" class="sub_header">
            <td><?= $g->GroupID; ?></td>
            <td><?= $g->GroupName; ?></td>
            <td>
            	<a href="#" class="edit_group" data-id="<?= $g->GroupID; ?>">Edit</a>
                &nbsp;
                <a href="#" class="delete_group" data-id="<?= $g->GroupID; ?>">Remove</a>
                
                <input type="hidden" id="<?= $g->GroupID; ?>_GroupID" value="<?= $g->GroupID; ?>" />
                <input type="hidden" id="<?= $g->GroupID; ?>_GroupName" value="<?= $g->GroupName; ?>" />
                <input type="hidden" id="<?= $g->GroupID; ?>_GroupDescription" value="<?= $g->GroupDescription; ?>" />
                
                <input type="hidden" class="reserve_gi" id="<?= $g->GroupID; ?>" value="<?= $g->GroupID; ?>" />
            </td>
        </tr>
        <?php if (is_array($g->child)): ?>
        <?php foreach ($g->child as $d): ?>
        <tr id="tr_dv_<?= $d->DeviceID; ?>">
        	<td> &nbsp; - <?= $d->DeviceID; ?></td>
            <td><?= $d->DeviceName; ?></td>
            <td>
            	<a href="#" class="edit_device" data-id="<?= $d->DeviceID; ?>">Edit</a>
                &nbsp;
                <a href="#" class="delete_device" data-id="<?= $d->DeviceID; ?>">Remove</a>
                
                <input type="hidden" id="<?= $d->DeviceID; ?>_DeviceID" value="<?= $d->DeviceID; ?>" />
                <input type="hidden" id="<?= $d->DeviceID; ?>_DeviceGroupID" value="<?= $d->DeviceGroupID; ?>" />
                <input type="hidden" id="<?= $d->DeviceID; ?>_DeviceName" value="<?= $d->DeviceName; ?>" />
                <input type="hidden" id="<?= $d->DeviceID; ?>_DeviceDescription" value="<?= $d->DeviceDescription; ?>" />
                <input type="hidden" id="<?= $d->DeviceID; ?>_DevicePosition" value="<?= $d->DevicePosition; ?>" />
                
                <input type="hidden" id="<?= $d->DeviceID; ?>_ChartRange" value="<?= $d->ChartRange; ?>" />
                <input type="hidden" id="<?= $d->DeviceID; ?>_RecordInterval" value="<?= $d->RecordInterval; ?>" />
                
                <input type="hidden" class="reserve_di" id="<?= $d->DeviceID; ?>" value="<?= $d->DeviceID; ?>" />
            </td>
        </tr>
        <?php endforeach; ?>
        <?php endif; ?>
        <?php endforeach; ?>
    </table>
</div>

<div class="edit_form_wrapper">
    <h2>Add / Edit device group</h2>
    <form id="group">
        <div class="line">
            Group ID <span class="required">*</span><br />
            <input type="text" id="groupid" name="groupid" readonly="readonly" />
            <input type="hidden" id="current_groupid" />
        </div>
        <div class="line">
            Group Name <span class="required">*</span><br />
            <input type="text" id="groupname" name="groupname" />
        </div>
        <div class="line">
            Group Description <br />
            <textarea id="groupdescription" name="groupdescription"></textarea>
        </div>
        
        <input type="button" class="insert" value="Add new group" />
        
        <input type="button" class="update hidden" value="Update group" />
        <a href="#" class="cancel hidden">Cancel</a>
    </form>

	<br /><hr /><br />
    
    <h2>Add / Edit device</h2>
    <form id="device">
        <div class="line share1">
            Device ID <span class="required">*</span><br />
            <input type="text" id="deviceid" name="deviceid" readonly="readonly" />
            <input type="hidden" id="current_deviceid" />
        </div>
        <div class="line share1">
            Device Group ID <span class="required">*</span><br />
			<select id="devicegroupid" name="devicegroupid">
            	<?php foreach ($groups as $g): ?>
                <option value="<?= $g->GroupID; ?>"><?= $g->GroupID; ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="clearfloat"> </div>
        
        <div class="line">
            Device Name <span class="required">*</span><br />
            <input type="text" id="devicename" name="devicename" />
        </div>
        
        <div class="line">
            Device Description <br />
            <textarea id="devicedescription" name="devicedescription"></textarea>
        </div>
        <div class="line">
            Device Position <br />
            <input type="text" id="deviceposition" name="deviceposition" />
        </div>
        
        <div class="line share1">
            Default chart range<br />
            <select id="chartrange" name="chartrange">
                <option value="1">1 week</option>
                <option value="2">2 weeks</option>
                <option value="4">4 weeks</option>
            </select>
        </div>
        <div class="line share1">
            Record interval (minute) - time between two sample <span class="required">*</span><br />
            <input type="text" id="recordinterval" name="recordinterval" style="width:30px;" />
        </div>
        <div class="clearfloat"> </div>
        
        
        
        
        <input type="button" class="insert" value="Add new device" />
        
        <input type="button" class="update hidden" value="Update device" />
        <a href="#" class="cancel hidden">Cancel</a>
    </form>
</div>


<script>
	$(document).ready(function(){
		initEditDevice();
		initEditGroup();
	});
	
	function initEditDevice(){
		$('.delete_device').click(function(){
			if (confirm('Are you sure want to remove this device?')) {
				var _o = $(this);
				var id = _o.attr('data-id');
				$.get(
					'device_dao.php',
					{
						op: 'delete_device',
						deviceid: id
					},
					function(data){
						$('#tr_dv_'+id).remove();
					}
				);
			}
			
			return false;
		});
		
		$('.edit_device').click(function(){
			document.getElementById('device').reset();
			
			var _o = $(this);
			var id = _o.attr('data-id');
			
			$('#deviceid').val($('#'+id+'_DeviceID').val());
			$('#devicegroupid').val($('#'+id+'_DeviceGroupID').val());
			$('#devicename').val($('#'+id+'_DeviceName').val());
			$('#devicedescription').html($('#'+id+'_DeviceDescription').val());
			$('#deviceposition').val($('#'+id+'_DevicePosition').val());
			
			$('#chartrange').val($('#'+id+'_ChartRange').val());
			$('#recordinterval').val($('#'+id+'_RecordInterval').val());

			$('#current_deviceid').val(id);
	
			$('#device .insert').hide();
			$('#device .update').show();
			$('#device .cancel').show();
			
			return false;
		});
		$('#device .cancel').click(function(){
			document.getElementById('device').reset();
			$('#devicedescription').html('');
			$('#current_deviceid').val('');
	
			$('#device .insert').show();
			$('#device .update').hide();
			$('#device .cancel').hide();
			
			return false;
		});
		
		$('#device .insert').click(function(){
			_checkDeviceForm(true);
		});
		$('#device .update').click(function(){
			_checkDeviceForm(false);
		});
	}
	function _checkDeviceForm(isInsert){
		var _deviceid = $('#deviceid');
		if (_deviceid.val() == '') {
			alert('Please enter device ID');
			return false;
		}
		// check if new user name is duplicate or not
		var deviceidArr = $('.reserve_di');
		if (!isInsert) { // no need to check current username
			var _deviceid_to_check = $('#current_deviceid').val();
			for (var i=0; i<deviceidArr.length; i++) {
				if ($(deviceidArr[i]).val() == _deviceid_to_check) {
					deviceidArr.splice(i, 1);
					break;
				}
			}
		}
		for (var i=0; i<deviceidArr.length; i++) {
			if (_deviceid.val() == $(deviceidArr[i]).val()) {
				alert('Given device ID is already taken. Please try again');
				return false;
			}
		}
		
		var _devicename = $('#devicename');
		if (_devicename.val() == '') {
			alert('Please enter device name');
			return false;
		}
		
		var _interval = $('#recordinterval');
		if (_interval.val() == '') {
			alert('Please enter record interval');
			return false;
		}
		if (isNaN(_interval.val())) {
			alert('Please enter record interval as an INTERGER (1, 2, 3,...)');
			return false;
		}
		
		var op = 'update_device';
		if (isInsert) op = 'insert_device';
		$.get(
			'device_dao.php',
			{
				data: $('#device').serialize(),
				op: op,
				currentDI: $('#current_deviceid').val()
			},
			function(data){
				console.log(data);
				
				if (data == 1) {
					if (isInsert) {
						alert('New device created');
					} else {
						alert('Device updated');
					}
					
					$('.sys_setting_wrapper').load('device_listing.php');
				} else {
					alert('Error occurs - pls try again');
				}
				document.getElementById('device').reset();
			}
		);
		
		return false;
	}


	function initEditGroup(){
		$('.delete_group').click(function(){
			if (confirm('Are you sure want to remove this group?')) {
				var _o = $(this);
				var id = _o.attr('data-id');
				$.get(
					'device_dao.php',
					{
						op: 'delete_group',
						groupid: id
					},
					function(data){
						$('#tr_group_'+id).remove();
					}
				);
			}
			
			return false;
		});
		
		$('.edit_group').click(function(){
			document.getElementById('group').reset();
			
			var _o = $(this);
			var id = _o.attr('data-id');
			
			$('#groupid').val($('#'+id+'_GroupID').val());
			$('#groupname').val($('#'+id+'_GroupName').val());
			$('#groupdescription').html($('#'+id+'_GroupDescription').val());

			$('#current_groupid').val(id);
	
			$('#group .insert').hide();
			$('#group .update').show();
			$('#group .cancel').show();
			
			return false;
		});
		
		$('#group .cancel').click(function(){
			document.getElementById('group').reset();
			$('#groupdescription').html('');
			$('#current_groupid').val('');
	
			$('#group .insert').show();
			$('#group .update').hide();
			$('#group .cancel').hide();
			
			return false;
		});
		
		$('#group .insert').click(function(){
			_checkGroupForm(true);
		});
		$('#group .update').click(function(){
			_checkGroupForm(false);
		});
	}
	function _checkGroupForm(isInsert){
		var _groupid = $('#groupid');
		if (_groupid.val() == '') {
			alert('Please enter group ID');
			return false;
		}
		// check if new user name is duplicate or not
		var groupidArr = $('.reserve_gi');
		if (!isInsert) { // no need to check current username
			var _groupid_to_check = $('#current_groupid').val();
			for (var i=0; i<groupidArr.length; i++) {
				if ($(groupidArr[i]).val() == _groupid_to_check) {
					groupidArr.splice(i, 1);
					break;
				}
			}
		}
		for (var i=0; i<groupidArr.length; i++) {
			if (_groupid.val() == $(groupidArr[i]).val()) {
				alert('Given group ID is already taken. Please try again');
				return false;
			}
		}
		
		var _groupname = $('#groupname');
		if (_groupname.val() == '') {
			alert('Please enter group name');
			return false;
		}
		
		var op = 'update_group';
		if (isInsert) op = 'insert_group';
		$.get(
			'device_dao.php',
			{
				data: $('#group').serialize(),
				op: op,
				currentGI: $('#current_groupid').val()
			},
			function(data){
				console.log(data);
				
				if (data == 1) {
					if (isInsert) {
						alert('New group created');
					} else {
						alert('Group updated');
					}
					
					$('.sys_setting_wrapper').load('device_listing.php');
				} else {
					alert('Error occurs - pls try again');
				}
				document.getElementById('group').reset();
			}
		);
		
		return false;
	}
</script>