<?php
require_once '../classes/conf.php';
require_once '../classes/dao.php';
require_once '../classes/Membership.php';
	
$dao = new Dao();
$membership = new Membership();
$un = htmlspecialchars($_GET['u'], ENT_QUOTES);
if (!empty($un)) {
	$users = $dao->getUser($un);
} else {
	$users = $dao->getAllUsers();
}


$groups  = $dao->getAllDeviceGroups();
$permissions = $dao->getPermissions();
$systemPermssions = $membership->getSystemPermission();

?>

<h2>System permissions</h2>
<form id="sys_permission">
<table <?php if (empty($un)):?>width="90%"<?php else: ?>width="30%"<?php endif;?> align="center" border="1">
    <tr class="header">
        <td align="center">System permssions / USER</td>
        <?php foreach ($users as $u): ?>
        <td class="header"><?= $u->UserName; ?></td>
        <?php endforeach; ?>
    </tr>
    <?php $i=0; ?>
    <?php foreach ($systemPermssions as $k => $v): ?>
    <tr class="<?php if ($i % 2) echo 'odd'; ?> " >
    	<td align="right"><?= $v; ?> &nbsp;</td>
        
        <?php foreach ($users as $u): ?>
        <td>
        	<?php 
				$r = $k.'_'.$u->UserName;
				$userPerm = explode(',', $u->SystemPermission);
				if (in_array($k, $userPerm)) {
					$check = 'checked="checked"'; 
				} else {
					$check = '';
				}
			?>
			<input type="checkbox" name="<?= $r; ?>" id="<?= $r; ?>" class="perm" value="1" <?= $check; ?> /> 
        </td>
        <?php endforeach; ?>
    </tr>
    <?php $i++; ?>
	<?php endforeach; ?>
</table>
</form>
<input type="button" value=" Save " id="saveSys" />


<br /><br /><br />
<h2>Device group permissions</h2>

<form id="permission">
<table <?php if (empty($un)):?>width="90%"<?php else: ?>width="30%"<?php endif;?> align="center" border="1">
    <tr class="header">
        <td align="center">DEVICE GROUP / USER</td>
        <?php foreach ($users as $u): ?>
        <td class="header"><?= $u->UserName; ?></td>
        <?php endforeach; ?>
    </tr>
    <?php $i=0; ?>
    <?php foreach ($groups as $g): ?>
    <tr class="<?php if ($i % 2) echo 'odd'; ?> " >
    	<td align="right">
			Can READ <strong><?= $g->GroupID.' ('.$g->GroupName.')'; ?></strong> &nbsp;
            <br />
            Can MODIFY <strong><?= $g->GroupID.' ('.$g->GroupName.')'; ?></strong> &nbsp;
        </td>
        
        <?php foreach ($users as $u): ?>
        <td>
        	<?php 
				$r = $g->GroupID.'_'.$u->UserName.'_r';
				$m = $g->GroupID.'_'.$u->UserName.'_m';
			?>
			<input type="checkbox" name="<?= $r; ?>" id="<?= $r; ?>" class="perm" value="1" <?php if ($permissions[$r]) echo 'checked="checked"'; ?> /> 
            <br />
            <input type="checkbox" name="<?= $m; ?>" id="<?= $m; ?>" class="perm" value="1" <?php if ($permissions[$m]) echo 'checked="checked"'; ?> /> 
        </td>
        <?php endforeach; ?>
    </tr>
    <?php $i++; ?>
	<?php endforeach; ?>
</table>
</form>
<input type="button" value=" Save " id="save" />

<script>
	$(document).ready(function(){
		$('#save').click(function(){
			$.get(
				'permission_dao.php',
				{
					data: $('#permission').serialize(),
					op: 'groupPerm'
				},
				function (data) {
					alert('Permission saved!');
				}
			);
			
			return false;
		});
		$('#saveSys').click(function(){
			$.get(
				'permission_dao.php',
				{
					data: $('#sys_permission').serialize(),
					op: 'sysPerm'
				},
				function (data) {
					alert('System permission saved!');
				}
			);
			
			return false;
		});
	});
</script>