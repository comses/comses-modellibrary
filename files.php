<?php

function openabmma_downloadFile ()
{
	$pName = arg(1);
	$versionNumber = openabmma_parseVersionNumber (arg(2));
	$target = arg(3);

	$files_root = "files/models/" . $pName . "/v" . $versionNumber . "/" . $target;
	$filename = openabmma_getFirstFile ($files_root);
	$filepath = realpath ($files_root . "/" . $filename);

	if (is_file($filepath) && strpos ($filepath, "../") === false && strpos ($filepath, "./") === false)
	{
		str_replace (" ", "\\ ", $filename);
    header ("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Length: ' . filesize($filepath));
    header('Content-Disposition: attachment; filename=' . $filename);

	readfile ($filepath);
//		header('Content-type: application/octet-stream');
//		ob_start();
//		include $filepath;
//		$contents = readfile($filepath);
//		ob_end_clean();
//		drupal_set_message ($contents);
//		print $contents;
	}
	else
	{
		return t('File not found.');
	}
}

function openabmma_uploadFile ($subdir, $formVar, $permission=0760)
{
    if ($formVar == '')
        return;

    $dir = "files/models/";
    if ($subdir != "")
        $dir .= $subdir;

    $is_writable = file_check_directory($dir, 1);
    if($is_writable) {
        $source = file_check_upload($formVar);
        if ($source == null)
            return null;

	// converting spaces to underscores
	$source->filename = str_replace (" ", "_", $source->filename);

        // Security measure to prevent exploit of file.php.png
        $source->filename = upload_munge_filename($source->filename);

        if ($file = file_save_upload($source,$dir ))
        {
            //			if (image_get_info($file->filepath))
            //				drupal_set_message(t('New image saved.'));
            //			else
            //			{
            //				file_delete($file->filepath);
            //				drupal_set_message('Uploaded file does not appear to be a valid image file. Please try again.');
            //			}
            chmod (realpath ($dir . "/" . $source->filename), $permission);
            return $source->filename;
        }
        else
            return null;
    }
    else
        return null;
}

function openabmma_cleantmp($directory, $recursive=false)
{
	//FIXME: Better security checks to see which folder/files are being deleted
	if ($directory [0] == '/' || strpos ($directory, "../") != FALSE)
		return;

	$directory = "files/models/" . $directory;
	if( !$dirhandle = @opendir($directory))
		return;

	while(false !== ($filename = readdir($dirhandle)) )
	{
		if( $filename != "." && $filename != ".." )
		{
			$filename = $directory. "/". $filename;

			if ($recursive && is_dir ($filename))
			{
				// drupal_set_message ($filename);
				// remove the first 'files' and 'models' from the path because it will automatically be re-attached in the next call
				$pathArray = explode ('/', $filename);
				array_shift ($pathArray);
				array_shift ($pathArray);
				$dirName = implode ('/', $pathArray);
				openabmma_cleantmp ($dirName);
				rmdir ('files/models/' . $dirName);
			}
			else
				unlink($filename);
		}
	}

	if ($recursive)
		rmdir ($directory);
}

function openabmma_getFileCount ($directory)
{
	$d = @dir($directory);
	if ($d == null || $d == false)
		return;

	$count = 0;
	while (false !== ($entry = $d->read()))
		$count++;

	$d->close();

	return $count-2;	// Exclude "." and ".."
}

function openabmma_getFirstFile ($directory)
{
	$d = @dir($directory);
	if ($d == null || $d == false)
		return;

	while (false !== ($entry = $d->read()))
		if ($entry != "." && $entry != "..")
			return $entry;

	$d->close();

	return "";	// Exclude "." and ".."
}
