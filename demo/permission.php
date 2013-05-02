<?php
	require_once '../classes/Membership.php';
	require_once '../classes/conf.php';
	require_once '../classes/dao.php';
	$membership = new Membership();
	$membership->confirm_Member();
	
	$permissionMgr = $membership->can_User_Access_System_Settings('permissionMgr');
	
	

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>

    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link rel="shortcut icon" href="images/favicon.ico"/>
    
    <link rel="stylesheet" href="css/reset.css" />
    <link rel="stylesheet" href="css/default.css" />
	<script>action="";</script>
	<?php include_once('_js_footer.php'); ?>
    <title>Permission configuration | Data logger</title>
</head>

<body class="sys">
<?php include_once('_header.php'); ?>

<div class="sys_setting_wrapper clearfix">
	<?php if ($permissionMgr): ?>
    	
        <?php include_once('permission_listing.php'); ?>
        
    <?php else: ?>
        Yon don't have permission to access this area!
    <?php endif; ?>
</div>
</body>
</html>