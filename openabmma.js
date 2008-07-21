/* FIXME: too much work, making it too brittle too */
function updateFrameworks() {
    var languageFrameworksMap = { 
        'Java': ['Ascape 5', 'DEVSJAVA', 'Mason', 'Repast'],
        'Netlogo': ['Netlogo']
    };
    var selectedOption = document.getElementById('programming_language');
    var language = selectedOption[selectedOption.selectedIndex].innerHTML;
    if (languageFrameworksMap[language]) {
        var frameworks = document.getElementById('framework');
        frameworks.options.length = 0;
        var index = 0;
        for (var framework in languageFrameworksMap[language]) {
            frameworks.options[index++] = new Option();
        }
}
// ashay's original JS validation code, replace with Drupal validation or
// improve later
function validate_version_step01 (newVersion)
{
    // Nothing to validate here
    return true;
}

function validate_version_step02 (newVersion)
{
    return true;
    /*
    if ($('#edit-submitAction').val () == 0)	// back button pressed
        return true;
    valid_ext = 'gz bz2 zip nlogo c cpp java jar tar rar py pl rb';
    return validate_ext ('#edit-version-code-file', valid_ext, false, "The code file you are trying to upload has an extension that is not allowed for code files. The allowed extensions are: " + valid_ext + ".");
    */
}

function validate_version_step03 (newVersion)
{
    // for now, allow anything
    return true;
    /*
    if ($('#edit-submitAction').val () == 0)	// back button pressed
        return true;

    valid_ext = 'gz bz2 zip xls ods txt pdf doc odt tar rar';
    stat1 = validate_ext ('#edit-version-sensitivity', valid_ext, true, "The sensitivity file you are trying to upload has an extension that is not allowed for sensitivity files. The allowed extensions are: " + valid_ext + ".\nThe sensitivity file is optional.");

    valid_ext = 'gz bz2 zip pdf doc txt odt tar rar';
    stat2 = validate_ext ('#edit-version-doc-file', valid_ext, false, "The documentation file you are trying to upload has an extension that is not allowed for such files. The allowed extensions are: " + valid_ext + ".");

    return stat1 && stat2;
    */
}

function validate_version_step04 (newVersion)
{
    return true;
    /*
    if ($('#edit-submitAction').val () == 0)	// back button pressed
        return true;

    valid_ext = 'gz bz2 zip pdf doc txt xls ods';
    stat2 = validate_ext ('#edit-version-dataset', valid_ext, true, "The Dataset file you are trying to upload has an extension that is not allowed for Dataset files. The allowed extensions are: " + valid_ext + ". The dataset file is optional.");

    valid_ext = 'gz bz2 zip pdf doc txt jpg jpeg';
    stat3 = validate_ext ('#edit-version-other', valid_ext, true, "The additional file you are trying to upload has an extension that is not allowed for additional files. The allowed extensions are: " + valid_ext + ". The additional file is optional.");

    return stat2 && stat3;
    */
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

function validateAndGo (modelName, modelVersion, action, stepNumber, url) {
    var valid = false;
    var newVersion = (action == "add") ? 1 : 0;

    switch (stepNumber)
    {
        case 1:	valid = validate_version_step01 (newVersion);	break;
        case 2:	valid = validate_version_step02 (newVersion);	break;
        case 3:	valid = validate_version_step03 (newVersion);	break;
        case 4:	valid = validate_version_step04 (newVersion);	break;
        default: alert ('Invalid step number to validate function.'); return false;
    }

    // goto URL
    if (valid)
        window.location.replace (url);
}
