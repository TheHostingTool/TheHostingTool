<form enctype="multipart/form-data" action="" method="POST">
Upload Your Theme (themename.zip): <input name="zip" type="file" /><input type="submit" value="Upload" />
</form>

<?
/*
* THT Theme Uploader
* By: TheRaptor -> http://ismyforum.info 
*/ 

if(THT != 1){die();}
if(!$_SESSION['logged']){die();}

//check if file is uploaded
if(isset($_FILES['zip'])){
	require_once('pclzip.lib.php'); //include class

	$upload_dir = '../themes'; //your upload directory NOTE: CHMODD 0777
	$filename = $_FILES['zip']['name']; //the filename

	//move file
	if(move_uploaded_file($_FILES['zip']['tmp_name'], $upload_dir.'/'.$filename))
	    echo "Uploaded ". $filename . " - ". $_FILES['zip']['size'] . " bytes<br />";
	else
		die("<font color='red'>Error : Unable to upload file</font><br />");

	$zip_dir = basename($filename, ".zip"); //get filename without extension fpr directory creation
	
	//unzip

	$archive = new PclZip($upload_dir.'/'.$filename);

	if ($archive->extract(PCLZIP_OPT_PATH, $upload_dir) == 0)
		die("<font color='red'>Error : Unable to unzip archive</font>");
	
	//show what was just extracted
	$list = $archive->listContent();
	echo "<br /><b>Files in Archive</b><br />";
	for ($i=0; $i<sizeof($list); $i++) {
	
		if(!$list[$i]['folder'])
			$bytes = " - ".$list[$i]['size']." bytes";
		else
			$bytes = "";
		
		echo "".$list[$i]['filename']."$bytes<br />";
	}

	unlink($upload_dir.'/'.$filename); //delete uploaded file
}
?>