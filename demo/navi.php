<?php include_once('_header.php'); ?>

<?php 
$currentDevice = '';
if (isset($_SESSION['DeviceID'])) $currentDevice = $_SESSION['DeviceID'];


$dao = new Dao();

$canReadGroup = '';
if ($_SESSION['user']->UserName != 'admin') {
	$canReadGroup = $dao->getUserPermission($_SESSION['user']->UserName, 1);
	$canReadGroup = '"'.join('","', $canReadGroup).'"';
}

$groups  = $dao->getAllDeviceGroups($canReadGroup);
$devices = $dao->getAllDevices($canReadGroup);

foreach ($devices as $d) {
	$groups[$d->DeviceGroupID]->child []= $d;
	if ($d->DeviceID == $currentDevice) {
		$groups[$d->DeviceGroupID]->class = 'active';
	}
}

// remove empty group
foreach ($groups as $k => $g) {
	if (empty($g->child)) unset($groups[$k]);
}


?>
<div id="left">
	<h1>Devices</h1>
    
    <ul id="nav">
    	<?php foreach ($groups as $g): ?>
        <li><a href="#" class="<?= $g->class; ?>"><?= $g->GroupID; ?></a>
            <?php if (!empty($g->child)): ?>
            <ul <?php if ($g->class == 'active'): ?>style="display:block"<?php endif; ?>>
                <?php foreach ($g->child as $d): ?>
                <li><a href="#" data-id="<?= $d->DeviceID; ?>" id="<?= $d->DeviceID; ?>" class="device"><?= $d->DeviceID; ?></a></li>
                <?php endforeach; ?>
            </ul>
            <?php endif; ?>
        </li>
        <?php endforeach; ?>
    </ul>
</div>

<div id="copyright">
    <p>Copyright © 2012. All rights Reserved, VNTech Specialist Group </p>
    <p>Contact us at:  vietnamese.specialists@gmail.com</p>
</div>