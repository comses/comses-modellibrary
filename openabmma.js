function validate_version_step01 (newVersion)
{
	// Nothing to validate here
	return true;
}

function validate_version_step02 (newVersion)
{
	if ($('#edit-submitAction').val () == 0)	// back button pressed
		return true;

	valid_ext = 'gz bz2 zip nlogo c cpp java jar';
	return validate_ext ('#edit-version-code-file', valid_ext, false, "The code file you are trying to upload has an extension that is not allowed for code files. The allowed extensions are: " + valid_ext + ".");
}

function validate_version_step03 (newVersion)
{
	if ($('#edit-submitAction').val () == 0)	// back button pressed
		return true;

	valid_ext = 'gz bz2 zip xls ods txt';
	stat1 = validate_ext ('#edit-version-sensitivity', valid_ext, true, "The sensitivity file you are trying to upload has an extension that is not allowed for sensitivity files. The allowed extensions are: " + valid_ext + ".\nThe sensitivity file is optional.");

	valid_ext = 'gz bz2 zip pdf doc txt';
	stat2 = validate_ext ('#edit-version-odd-file', valid_ext, false, "The ODD file you are trying to upload has an extension that is not allowed for ODD files. The allowed extensions are: " + valid_ext + ".");

	return stat1 && stat2;
}

function validate_version_step04 (newVersion)
{
	if ($('#edit-submitAction').val () == 0)	// back button pressed
		return true;

	valid_ext = 'gz bz2 zip pdf doc txt xls ods';
	stat2 = validate_ext ('#edit-version-dataset', valid_ext, true, "The Dataset file you are trying to upload has an extension that is not allowed for Dataset files. The allowed extensions are: " + valid_ext + ". The dataset file is optional.");

	valid_ext = 'gz bz2 zip pdf doc txt jpg jpeg';
	stat3 = validate_ext ('#edit-version-other', valid_ext, true, "The additional file you are trying to upload has an extension that is not allowed for additional files. The allowed extensions are: " + valid_ext + ". The additional file is optional.");

	return stat2 && stat3;
}

function validate_model (newVersion)
{
	// Nothing to validate here
	return true;
}

function getFileExtension (filename)
{
	var fileExt = '';
	var fileparts = filename.split ('.');

/*	for (i=fileparts.length-1; i>=0; i--)
	{
		fileExt = fileparts [i] + fileExt;
		if (fileparts [i] != "gz")
			break;

		fileExt = '.' + fileparts [i];
	}
*/
	return fileparts [fileparts.length-1].toLowerCase ();
}

function validate_ext (formVar, allowed_ext, optionalField, errorMsg)
{
	var fileInput = $(formVar).val ();

	if (fileInput == null)
		return true;

	if (optionalField == true && fileInput.length == 0)	// Ignore blank value
		return true;

	var valid_ext = allowed_ext.split (' ');
	var extension = getFileExtension (fileInput);
	var allowed_ext = '';

	for (i=0; i<valid_ext.length; i++)
	{
		if (valid_ext [i] == extension)
			break;

		allowed_ext += valid_ext [i] + ' ';
	}

	if (i == valid_ext.length)
	{
		alert (errorMsg);
		return false;
	}

	return true;
}
