<?php
require_once 'classes/conf.php';
require_once 'classes/Membership.php';

$membership = new Membership();
$membership->confirm_Member();


include_once('classes/util.php');

$path = $_GET['p'];
if (isValidFilename($p)) {
    $file = EXPORT_DIR.'/'.$path;
    if (is_file($file)) {
        header("Cache-Control: public");
        header("Content-Description: File Transfer");
        header('Content-Type: application/x-msdownload; charset=utf-8');
        header("Content-Disposition: attachment; filename=$path");
        // Read the file from disk
        readfile($file);
    } else {
        echo 'Sorry, your requested file could not be found.';
    }
} else {
    echo 'Please do not edit filename by yourself.';
}