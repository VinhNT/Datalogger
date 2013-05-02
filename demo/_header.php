<div id="header" class="clearfix">
	<div id="top_welcome">
        <a href="../login.php?status=loggedout" title="Log out">Log out</a>
        <a href="profile.php" title="Edit your informaion">Edit profile</a>
        <span>Welcome, <?php echo $_SESSION['user']->FullName ? $_SESSION['user']->FullName : $_SESSION['user']->UserName; ?></span>
    </div>
    
    <?php #var_dump($_SESSION['user']); ?>

    <ul id="menu">
        <li><a href="show.php" id="ico_chart" title="View device data as CHART"></a></li>
        <li><a href="table.php" id="ico_table" title="View device data as TABLE"></a></li>
        <li><a href="#" id="ico_print" title="Print TABLE data"></a></li>
        
		<?php if ($membership->can_User_Access_System_Settings()): ?>
        <li class="seperate"> </li>
        <?php endif; ?>
        
		<?php if ($membership->can_User_Access_System_Settings('userMgr')): ?>
        <li><a href="user.php" id="ico_user" title="User configuration"></a></li>
        <?php endif; ?>
        
        <?php if ($membership->can_User_Access_System_Settings('deviceMgr')): ?>
        <li><a href="device.php" id="ico_device" title="Device configuration"></a></li>
        <?php endif; ?>
        
        <?php if ($membership->can_User_Access_System_Settings('permissionMgr')): ?>
        <li><a href="permission.php" id="ico_permission" title="Permission configuration"></a></li>
        <?php endif; ?>
        
        <?php if ($membership->can_User_Access_System_Settings('chartMgr')): ?>
        <li><a href="chart.php" id="ico_chart_config" title="Chart configuration"></a></li>
        <?php endif; ?>
    </ul>
</div>