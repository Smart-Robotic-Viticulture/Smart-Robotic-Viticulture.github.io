<?php
require('defines.berrycount.php');
require('class.messageservice.php');

ob_start('ob_gzhandler');

$out = [];

if(count($_GET)) {
	//print "GET\n";
//	print_r($_GET);
	$out['GET'] = $_GET;
}
if(count($_FILES)) {
	//print "FILES\n";
//	print_r($_FILES);
	$out['FILES'] = $_FILES;
}
if(count($_POST)) {
	//print "POST\n";
//	print_r($_POST);
	$out['POST'] = $_POST;
}
//print "SERVER\n";
//print_r($_SERVER);
$out['SERVER'] = $_SERVER;

function copy_uploaded_file($filename, $destination)
{
	return (is_uploaded_file($filename) && copy($filename, $destination));
}

function handleUpload($target, $destination, $filebase = "")
{
global $_FILES;

	// uplaod doesn't exist?
	if(!array_key_exists($target, $_FILES))
		return null;

try {
    
    // Undefined | Multiple Files | $_FILES Corruption Attack
    // If this request falls under any of them, treat it invalid.
    if (
        !isset($_FILES[$target]['error']) ||
        is_array($_FILES[$target]['error'])
    ) {
        throw new RuntimeException('Invalid parameters.  Upload errors found.');
    }

    // Check $_FILES[$target]['error'] value.
    switch ($_FILES[$target]['error']) {
        case UPLOAD_ERR_OK: break;
        case UPLOAD_ERR_NO_FILE:        throw new RuntimeException('No file sent.');
        case UPLOAD_ERR_INI_SIZE:
        case UPLOAD_ERR_FORM_SIZE:      throw new RuntimeException('Exceeded filesize limit.');
        default:          		throw new RuntimeException('Unknown errors.');
    }

    // You should also check filesize here. 
    if ($_FILES[$target]['size'] > 8000000) {
        throw new RuntimeException('Exceeded filesize limit.');
    }

    $ext = strtolower(pathinfo($_FILES[$target]['name'], PATHINFO_EXTENSION));
    if(!in_array($ext, ['jpg','txt'])) {
        throw new RuntimeException('Invalid file format. ' + $ext);
    }

    // You should name it uniquely.
    // DO NOT USE $_FILES[$target]['name'] WITHOUT ANY VALIDATION !!
    // On this example, obtain safe unique name from its binary data.
    if($filebase == "") {
	$exif = exif_read_data($_FILES[$target]['tmp_name']);

	// default to server time 
	$dateyr = date("Ym");
	$datestr = date("dHis");

	if(array_key_exists('DateTimeOriginal', $exif)) {
		//print_r($exif);
		$datestr = preg_replace("/[^0-9]/", "", $exif['DateTimeOriginal']);

		// move into yyyymm
		$dateyr = substr($datestr, 0, 6);
		if($dateyr > 200000) {
			$datestr = substr($datestr, 6);
		}
	}

	$destination = "{$destination}{$dateyr}";
	$dir = STORAGE_ROOT . $destination;
	if(!is_dir($dir))
		mkdir($dir);
		//$destination =
	$newFile = sprintf(STORAGE_ROOT . '%s/%s_%s.%s', $destination, $datestr, substr(sha1_file($_FILES[$target]['tmp_name']),0,4), $ext);
//	        $newFile = sprintf(STORAGE_ROOT . '%s/%s.%s', $destination, sha1_file($_FILES[$target]['tmp_name']), $ext);
//	}
    }
    else
        $newFile = sprintf("%s.%s", $filebase, $ext);

    if (!copy_uploaded_file($_FILES[$target]['tmp_name'], $newFile)) {
        throw new RuntimeException("Failed to move uploaded file ($target).");
    }

    return $newFile;
    //echo 'File is uploaded successfully to .';

} catch (RuntimeException $e) {
    http_response_code(409); 
    echo $e->getMessage();
}
    return null;
}

if($_POST['description'] == ACTION_DESCRIPTION) {
	//print "PHOTOUPLOAD-ANDROID-OK\n";
	$photoFile = handleUpload('photo', STORAGE_DIR);

	//print "[Photo] {$photoFile}\n";
	$metaFile = handleUpload('meta', STORAGE_DIR, $photoFile);
	if(is_null($metaFile))
		$metaFile = $photoFile . '.txt';

	file_put_contents($metaFile, "SyncIP: {$_SERVER['REMOTE_ADDR']} (" . gethostbyaddr($_SERVER['REMOTE_ADDR']) . ")\n", FILE_APPEND);
	if(array_key_exists('photo', $_FILES))
		file_put_contents($metaFile, "File Name (Original): {$_FILES['photo']['name']}\n", FILE_APPEND);

	$exifPhotoTxt = `exiftool {$photoFile}`;
	file_put_contents($metaFile, "\n{$exifPhotoTxt}", FILE_APPEND);

	//print "[Meta] {$metaFile}\n";
	$exifPhotoJson = `exiftool {$photoFile} -json -g`;
	//print "[Exif] {$exifPhoto}\n";
	$exifj = json_decode($exifPhotoJson, true);

//	if($exifj[0]['EXIF']['ThumbnailLength'] > 0) {
//		$thumbFile = $photoFile . '.thumb.jpg';
//		`exiftool {$photoFile} -b -thumbnailimage > {$thumbFile}`;
//		$out['OUT_THUMB'] = $thumbFile;
//	}

	$out['OUT_ACTION'] = "PHOTOUPLOAD_ANDROID-OK";
	$out['OUT_PHOTO'] = $photoFile;
	$out['OUT_META'] = $metaFile;
	$out['OUT_EXIFT'] = $exifj[0];
	//$out['OUT_EXIFP'] = exif_read_data($photoFile);

	// enqueue processing request
	if(http_response_code() == 200) {
		$ms = new MessageService(STORAGE_ROOT . MESSAGES_DB);
		$ms->push('analyze', json_encode(['photo' => $photoFile, 'lodged' => date('Y-m-d')]));
	}
}


function notifySpreadsheet()
{
	$output = ob_get_contents();
	file_put_contents('/tmp/bcupload', $output);
	return;
	$output = ob_get_contents();
	$url = 'https://docs.google.com/forms/d/e/1FAIpQLScgAul_5KIlVlTJArzzMq2YxpS3htVhONWtZn0zSI5VwRUdrA/formResponse';
	$params = ['entry.683861534_year' => date('Y'),
		'entry.683861534_month' => date('n'), 
		'entry.683861534_day' => date('j'),
		'entry.940123061_hour' => date('H'),
		'entry.940123061_minute' => date('i'),
		'entry.783079361' => 'photoupload-android',
		'entry.128184215' => $output];
	$myvars = http_build_query($params); //'myvar1=' . $myvar1 . '&myvar2=' . $myvar2;

	$ch = curl_init( $url );
	curl_setopt( $ch, CURLOPT_POST, 1);
	curl_setopt( $ch, CURLOPT_POSTFIELDS, $myvars);
	curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, 1);
	curl_setopt( $ch, CURLOPT_HEADER, 0);
	curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1);

	$response = curl_exec( $ch );
//	print "Submission response: {$response}\n";
}
//sleep(1);
//print(json_encode($out, JSON_PRETTY_PRINT));
print json_encode($out);
//print "\nEND";
notifySpreadsheet();
touch('/dev/shm/vwsphotoupload-' . sha1(STORAGE_ROOT . STORAGE_DIR));
