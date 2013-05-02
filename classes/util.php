<?php

/*
 * Remove all files inside given directory
 * @param string $dir
 */
function cleanUp($dir) {
    $files = glob($dir); // get all file names
    foreach($files as $file){
        if(is_file($file)) unlink($file);
    }
}

/*
* check directory have exists? if not exists then create new directorys
* @param string $dir
*/
function checkDirectory($dir)
{
    $path = explode('/', $dir);
    $total = count($path);
    $p = '';

    for ($i=0; $i<$total; $i++) {
        if (empty($path[$i])) {
            $p = '/';
            continue;
        }

        $p .= $path[$i].'/';

        if (!is_dir($p)) {
            mkdir($p, FOLDER_PERMISSION);
        } else {
            if (!is_writeable($p)) {
                @chmod($p, FOLDER_PERMISSION);
            }
        }
    }
}

/*
 * Save given data into a CSV file
 */
function exportCSV($deviceID, $data, $dir) {
    $notAllowed = array('#', '@', '$', '!', '~', '^', '&', '*', '/', '\\', '[', ']', '{', '}');
    $replace = '';
    $fileName = str_replace($notAllowed, $replace, $deviceID).'-'.date('YmdHis').'.csv';


    checkDirectory($dir);
    $f = fopen($dir.'/'.$fileName, 'a');

    if ($f) {
        foreach ($data as $row) {
            $str = join(',', $row);
            fwrite($f, $str."\n");
        }

        fclose($f);

        return $fileName;
    } else {
        return '';
    }
}

/*
 * check if given filename has forbiden characters or not
 * @param string $filename
 *
 * @return boolean
 */
function isValidFilename($filename) {
    $notAllow = array(
        '..',
        '/',
        '\\',
        '#', '@', '$', '!', '~', '^', '&', '*', '/', '\\', '[', ']', '{', '}'
    );
    foreach ($notAllow as $str) {
        if (strpos($filename, $str) !== false) return false;
    }

    return true;
}


function convert($str){
	$str = str_replace('+', ' ', $str);
	$str = mysql_real_escape_string(urldecode($str));
	
	return $str;
}

function __getParam($index, $src='', $default='') {
	if ($source == '') $source = $_REQUEST;
	if (@array_key_exists($index, $source)) return $source[$index];
	else return $default;
}