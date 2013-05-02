<?php

require_once '../classes/Membership.php';
require_once '../classes/conf.php';
require_once '../classes/dao.php';
$membership = new Membership();
$membership->confirm_Member();

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>

    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link rel="shortcut icon" href="images/favicon.ico"/>
    
    <link rel="stylesheet" href="css/reset.css" />
    <link rel="stylesheet" href="css/default.css" />
    
    <title>Tabular data | Data logger</title>
</head>

<body>
<?php include_once('navi.php'); ?>

<div id="chart_wrapper">
    <div id="container" class="chart_container">
        <span class="place_holder">
            <?php if (empty($_SESSION['DeviceID'])): ?>
            Please select device to view data
            <?php else: ?>
            <img src="images/loading.gif">
            <?php endif; ?>
        </span>
    </div>
    
</div>
	<script>action="table";</script>
    <?php if (!empty($_SESSION['DeviceID'])): ?>
    <script>DeviceID="<?= $_SESSION['DeviceID']; ?>";</script>
    <?php endif; ?>
    
    <?php include_once('_js_footer.php'); ?>
</body>
</html>