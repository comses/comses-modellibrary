<?php

function openabmma_versionMetadata ()
{
    global $user;
	$pName = arg(1);
    if ($pName == '')
        return '';

	$versionNumber = openabmma_parseVersionNumber (arg(2));
    $owner = openabmma_getModelOwner ($pName);

    $query = "SELECT visible FROM openabm_model_version WHERE model_id=%d AND version_num=%d";
    $result = (array) db_fetch_object (db_query ($query, openabmma_getModelId ($pName), $versionNumber));
    $visible = $result ['visible'];
    if ($visible == "1")
        $visible = TRUE;
    else
        $visible = FALSE;

    if (!$visible)
    {
	    if ($user->name != $owner && openabmma_inList ($user->name, openabmma_getModelMemberArray ($pName)) == -1)
	    {
		openabmma_accessError ('Only model members can view metadata for a model.');
		return '';
	    }
    }

    drupal_add_css (openabmma_get_css_path ());

    $query = "SELECT description, model_language_id, os, framework, reference_text, examples, submittedReview, visible, date_modified, run_conditions, license_id from openabm_model_version WHERE model_id=%d AND version_num=%d";
    $result = (array) db_fetch_object (db_query ($query, openabmma_getModelId ($pName), $versionNumber));
    $description = $result ['description'];
    $model_language_id = $result ['model_language_id'];
    $os = $result ['os'];
    $framework = $result ['framework'];
    $reference_text = $result ['reference_text'];
    $examples = $result ['examples'];
    $submittedReview = $result ['submittedReview'] == "1" ? "Yes" : "No";
    $visible = $result ['visible'] == "1" ? "Yes" : "No";
    $license_id = $result ['license_id'];

    $query = "SELECT name from openabm_model_language WHERE id=%d";
    $result = (array) db_fetch_object (db_query ($query, $model_language_id));
    $model_language_id = $result ['name'];

    $query = "SELECT name from openabm_license WHERE id=%d";
    $result = (array) db_fetch_object (db_query ($query, $license_id));
    $license_id = $result ['name'];

    $memberArray = openabmma_getModelMemberArray ($pName);
    if ($memberArray == null)
        $members = "None";
    else
        $members = implode (' ,', $memberArray);

    $output = "<br/><p><table border='0' cellpadding='0' cellspacing='0' width='100%'>";
    $output .= "<tr class='openabmData'><td width='30%'><b>Version description:</b></td><td><i>" . substr($description,0,100) . "...</i></td></tr>";
    $output .= "<tr class='openabmData'><td><b>Owner:</b></td><td><i>" . openabmma_getModelOwner ($pName) . "</i></td></tr>";
    $output .= "<tr class='openabmData'><td><b>Members:</b></td><td><i>" . $members . "</i></td></tr>";
    $output .= "<tr class='openabmData'><td><b>Visible to public:</b></td><td><i>" . $visible . "</i></td></tr>";
    $output .= "<tr class='openabmData'><td><b>Programming language:</b></td><td><i>" . $model_language_id . "</i></td></tr>";
    $output .= "<tr class='openabmData'><td><b>Operating System:</b></td><td><i>" . $os . "</i></td></tr>";
    $output .= "<tr class='openabmData'><td><b>Framework:</b></td><td><i>" . $framework . "</i></td></tr>";
    $output .= "<tr class='openabmData'><td><b>License:</b></td><td><i>" . $license_id . "</i></td></tr>";
    $output .= "<tr class='openabmData'><td><b>References:</b></td><td><i>" . $reference_text . "</i></td></tr>";
    $output .= "<tr class='openabmData'><td><b>Examples:</b></td><td><i>" . $examples . "</i></td></tr>";
    $output .= "<tr class='openabmData'><td><b>Submitted for review:</b></td><td><i>" . $submittedReview . "</i></td></tr>";

    $files_root = "files/models/" . $pName . "/v" . $versionNumber;
    if (openabmma_getFileCount ($files_root . "/code") == 0)
        $codeStr = "None";
    else
    {
        $filename = openabmma_getFirstFile ($files_root . "/code");
	$codeStr = l ($filename, "download/" . $pName . "/version" . $versionNumber . "/code");
//        $codeStr = "<a href='file://" . realpath ($files_root . "/code/" . $filename) . "'>" . $filename . "</a>";
    }

    $files_root = "files/models/" . $pName . "/v" . $versionNumber;
    if (openabmma_getFileCount ($files_root . "/doc") == 0)
        $docStr = "None";
    else
    {
        $filename = openabmma_getFirstFile ($files_root . "/doc");
	$docStr = l ($filename, "download/" . $pName . "/version" . $versionNumber . "/doc");
//        $docStr = "<a href='file://" . realpath ($files_root . "/doc/" . $filename) . "'>" . $filename . "</a>";
    }

    $files_root = "files/models/" . $pName . "/v" . $versionNumber;
    if (openabmma_getFileCount ($files_root . "/sensitivity") == 0)
        $sensitivityStr = "None";
    else
    {
        $filename = openabmma_getFirstFile ($files_root . "/sensitivity");
	$sensitivityStr = l ($filename, "download/" . $pName . "/version" . $versionNumber . "/sensitivity");
//        $sensitivityStr = "<a href='file://" . realpath ($files_root . "/sensitivity/" . $filename) . "'>" . $filename . "</a>";
    }

    $files_root = "files/models/" . $pName . "/v" . $versionNumber;
    if (openabmma_getFileCount ($files_root . "/dataset") == 0)
        $datasetStr = "None";
    else
    {
        $filename = openabmma_getFirstFile ($files_root . "/dataset");
	$datasetStr = l ($filename, "download/" . $pName . "/version" . $versionNumber . "/dataset");
//        $datasetStr = "<a href='file://" . realpath ($files_root . "/dataset/" . $filename) . "'>" . $filename . "</a>";
    }

    $files_root = "files/models/" . $pName . "/v" . $versionNumber;
    if (openabmma_getFileCount ($files_root . "/other") == 0)
        $additionalStr = "None";
    else
    {
        $filename = openabmma_getFirstFile ($files_root . "/other");
	$additionalStr = l ($filename, "download/" . $pName . "/version" . $versionNumber . "/other");
//        $additionalStr = "<a href='file://" . realpath ($files_root . "/other/" . $filename) . "'>" . $filename . "</a>";
    }

    $output .= "<tr><td>&nbsp;</td><td>&nbsp;</td></tr>";
    $output .= "<tr><td><u><i>Files:</i></u></td><td>&nbsp;</td></tr>";
    $output .= "<tr class='openabmData'><td><b>Code:</b></td><td><i>" . $codeStr . "</i></td></tr>";
    $output .= "<tr class='openabmData'><td><b>Documentation:</b></td><td><i>" . $docStr . "</i></td></tr>";
    $output .= "<tr class='openabmData'><td><b>Sensitivity:</b></td><td><i>" . $sensitivityStr . "</i></td></tr>";
    $output .= "<tr class='openabmData'><td><b>Dataset:</b></td><td><i>" . $datasetStr . "</i></td></tr>";
    $output .= "<tr class='openabmData'><td><b>Additional file:</b></td><td><i>" . $additionalStr . "</i></td></tr>";
    $output .= "</table>";

	if ($user->name == $owner)
	    $output .= "<p></p>" . l ("To change your metadata settings, click here", "mymodels/" . $pName . "/edit/version" . $versionNumber . "/step01");

	$output .= "<p></p>" . l ("To go to model workspace, click here", "mymodels/" . $pName);
    return $output;
}

function openabmma_askIfReview () {
	global $user;
	$pName = arg(1);

	if ($user->name != openabmma_getModelOwner ($pName))
            return openabmma_formAccessError ("Only model owners can change metadata details of any model. You are not registered as the owner of this model.");
	drupal_add_css (openabmma_get_css_path ());

	$output .= "<br/><p><table border='0' cellpadding='1' cellspacing='0' width='100%'>";
$output .= "<tr class='openabmData'><td><b>Step 1</b></td><td><i>[Complete]</i></td><td>Version description and visibility to public</td></tr>";
$output .= "<tr class='openabmData'><td><b>Step 2</b></td><td><i>[Complete]</i></td><td>Code files, language and platform details</td></tr>";
$output .= "<tr class='openabmData'><td><b>Step 3</b></td><td><i>[Complete]</i></td><td>License information, References, Examples and Sensitivity data</td></tr>";
$output .= "<tr class='openabmData'><td><b>Step 4</b></td><td><font color='green'>Only for review</font></td><td>Collecting documents for model review</td></tr>";
$output .= "</table>";

$output .= "<p>&nbsp;<br/>At this point, you have completed the basic requirements of submitting a version of a model to OpenABM.org.<br/>You could complete the process of adding your version to the OpenABM repository by clicking on the 'Finish' button below.</p>";

$output .= "<p>Alternatively, you could indicate the version is ready for a review by the committee at OpenABM.org. If you wish to submit your model for review, click the 'Proceed to Submit Version for Review' button. In that case, you would be directed to a page that asks for some more information such as the Documentation.</p>";

$output .= "<p><u>Note: You can submit the version for review at any time by clicking on the \"Send this version for review\" link in the model workspace.</u></p>";

	$form["details"] = array(
		"#type" => 'fieldset',
		"#collapsible" => FALSE,
		"#collapsed" => FALSE,
		"#title" => null,
		"#visible" => FALSE,
		"#description" => null,
	);

	$form ["details"]["model_name"] = array (
		"#type" => "item",
		"#title" => null,
		"#value" => $output,
		"#description" => null,
	);

	$form ["details"]["review"] = array (
		"#type" => "submit",
		"#value" => t("Proceed to Submit Version for Review"),
		"#submit" => TRUE
	);

	$form ["details"]["noreview"] = array (
		"#type" => "submit",
		"#value" => t("Finish the Add Version process"),
		"#submit" => TRUE
	);

	$form ["details"]["cancel"] = array (
		"#type" => "submit",
		"#value" => t("Back"),
		"#submit" => TRUE
	);

	return ($form);
}

function openabmma_askIfReview_submit ($form_id, $edit)
{
	global $user;
	$pName = arg(1);
	$action = arg(2);
	if ($user->name != openabmma_getModelOwner ($pName))
		return openabmma_accessError ("Only model owners can change metadata details of any model. You are not registered as the owner of this model.");

	$versionNumber = openabmma_parseVersionNumber (arg(3));

	if ($_POST ["op"] == "Proceed to Submit Version for Review")
		drupal_goto ("mymodels/" . $pName . "/" . $action . "/version" . $versionNumber . "/step04");
	else if ($_POST ["op"] == "Finish the Add Version process")
		drupal_goto ("mymodels/" . $pName . "/" . $action . "/version" . $versionNumber . "/complete");
	else if ($_POST ["op"] == "Back")
		drupal_goto ("mymodels/" . $pName . "/" . $action . "/version" . $versionNumber . "/step03");
	else
		drupal_goto ("/");
}

function openabmma_addVersion02_submit ($form_id, $edit)
{
	global $user;
	$errString = '';
	$pName = arg(1);

	if ($user->name != openabmma_getModelOwner ($pName))
		return openabmma_accessError ("Only model owners can change metadata details of any version in the model. You are not registered as the owner of this model.");

	$versionNumber = openabmma_parseVersionNumber (arg(3));

	$action = arg(2);
	if ($_POST ["op"] == "Back")
		drupal_goto ("mymodels/" . $pName . "/" . $action . "/version" . $versionNumber . "/step01");

	$directory = "files/models/" . $pName . "/v" . $versionNumber . "/code";
	$fileCount = openabmma_getFileCount ($directory);
	if ($fileCount == 0)
	{
		openabmma_cleantmp ($pName . '/v' . $versionNumber . '/code');
		if (openabmma_uploadFile ($pName . '/v' . $versionNumber . '/code', 'version_code_file') == null)
		{
			drupal_set_message ("<font color='red'><b>Error uploading code file, please check the path of the file specified</b></font>");
			return;
		}
	}

	if ($errString != "")
	{
		drupal_set_message ("<b><font color='red'>" . $errString . "</font></b>");
		return;
	}

	$query = "UPDATE openabm_model_version SET date_modified='%s', model_language_id=%d, os=%d, framework='%s' WHERE model_id=%d AND version_num=%d";

	db_query ($query, date ("Y-m-d H:i:s"), $edit ["version_language"], $edit ["os"]+1, $edit ["framework"], openabmma_getModelId ($pName), $versionNumber);
	drupal_goto ("mymodels/" . $pName . "/" . $action . "/version" . $versionNumber . "/step03");
}

function openabmma_addVersion02 ($edit=null, $item=0) {
    global $user;
    $pName = arg(1);

    $action = arg(2);

    // FIXME: duplicated code
    if ($user->name != openabmma_getModelOwner ($pName))
        return openabmma_formAccessError ("Only model owners can change metadata details of any version in the model. You are not registered as the owner of this model.");

	$versionNumber = openabmma_parseVersionNumber (arg(3));

    // FIXME:this doesn't work anymore.
    $query = "SELECT model_language_id, os, framework FROM openabm_model_version WHERE model_id=%d AND version_num=%d";
    $result = (array) db_fetch_object (db_query ($query, openabmma_getModelId ($pName), $versionNumber));
    $progLang = $result ['model_language_id'];

    $os = $result ['os'];
    $framework = $result ['framework'];

    $newVersion = $action == "add" ? 1 : 0;		
    drupal_add_js( openabmma_get_js_path() );

    $form['#attributes'] = array('enctype' => "multipart/form-data", 'onsubmit' => 'return validate_version_step02(' . $newVersion . ')');

    $form["details"] = array(
            "#type" => 'fieldset',
            "#collapsible" => FALSE,
            "#collapsed" => FALSE,
            "#title" => null,
            "#description" => null,
            );

	$directory = "files/models/" . $pName . "/v" . $versionNumber . "/code";
	$fileCount = openabmma_getFileCount ($directory);
	if ($fileCount > 0)
		$form ["details"]["version_code_file"] = array (
			"#type" => "item",
			"#value" => "The file '<b>" . openabmma_getFirstFile ($directory) . "</b>' has been uploaded as the code file. To delete this file and upload a different one, click <a href='" . url ("mymodels/" . $pName . "/" . $action . "/version" . $versionNumber . "/files_basic/delete/code") . "'>delete</a>."
		);
	else
		$form ["details"]["version_code_file"] = array (
			"#type" => "file",
			"#title" => t("Code file (required):")
		);


    $languages = array ();
    $result = db_query ("SELECT id, name FROM openabm_model_language ORDER BY name");
    while ($node = db_fetch_object ($result))
        $languages [$node->id] = $node->name;

    $form ["details"]["version_language"] = array (
            "#type" => "select",
            "#title" => t("Programming Language:"),
            "#options" => $languages,
            "#default_value" => $edit ["prog_language"] == "" ? $progLang : $edit ["prog_language"],
            "#description" => null
            );
/*
    $form ["details"]["other_language"] = array (
            "#type" => "textfield",
            "#title" => t("Other (if not mentioned in above list):"),
            "#maxlength" => 210,
            "#default_value" => $edit ["other_language"],
            "#required" => false
            );
*/
// FIXME: add that crap that parses the output of "show columns from openabm_model_version like 'os'"
    $arrayElements = array ('Linux', 'Mac', 'Windows', 'Platform Independent', 'Other');

    $form ["details"]["os"] = array (
            "#type" => "select",
            "#title" => t("Operating System:"),
            "#default_value" => $edit ["os"] == "" ? openabmma_inList ($os, $arrayElements) : $edit ["os"],
            "#options" => $arrayElements,
            "#description" => null
            );

    $arrayElements = array ();
    $result = db_query ("SELECT name FROM openabm_framework ORDER BY name");
    while ($node = db_fetch_object ($result))
        $arrayElements [$node->name] = $node->name;
    $arrayElements ["Other"] = "Other";

    $form ["details"]["framework"] = array (
            "#type" => "select",
            "#title" => t("Framework used:"),
            "#default_value" => $edit ["framework"] == "" ? $framework : $edit ["framework"],
            "#options" => $arrayElements,
            "#description" => null
            );

    $form ["details"]["submitAction"] = array (
            "#type" => "hidden",
            "#value" => 1
            );

    $form ["details"]["submit"] = array (
            "#type" => "submit",
            "#value" => t("Submit"),
            "#submit" => TRUE
            );

    $form ["details"]["cancel"] = array (
            "#type" => "submit",
            "#attributes" => array ('onclick' => '$(\'#edit-submitAction\').val(0);'),
            "#value" => t("Back"),
            "#submit" => TRUE
            );

    return ($form);	
}

function openabmma_addVersion03_submit ($form_id, $edit)
{
	global $user;
	$pName = arg(1);

	if ($user->name != openabmma_getModelOwner ($pName))
		return openabmma_accessError ("Only model owners can change metadata details of any version in the model. You are not registered as the owner of this model.");

	$versionNumber = openabmma_parseVersionNumber (arg(3));

	$action = arg(2);
	if ($_POST ["op"] == "Back")
		drupal_goto ("mymodels/" . $pName . "/" . $action . "/version" . $versionNumber . "/step02");

	$directory = "files/models/" . $pName . "/v" . $versionNumber . "/doc";
	$fileCount = openabmma_getFileCount ($directory);
	if ($fileCount == 0)
	{
		// openabmma_cleantmp ($pName . '/v' . $versionNumber . '/doc');
		if (openabmma_uploadFile ($pName . '/v' . $versionNumber . '/doc', 'version_doc_file') == null)
		{
			drupal_set_message ("<font color='red'><b>Error uploading Documentation, please check the path of the file specified</b></font>");
			return;
		}
	}

	$directory = "files/models/" . $pName . "/v" . $versionNumber . "/sensitivity";
	$fileCount = openabmma_getFileCount ($directory);
	if ($fileCount == 0)
	{
//		openabmma_cleantmp ($pName . '/v' . $versionNumber . '/sensitivity');
		openabmma_uploadFile ($pName . '/v' . $versionNumber . '/sensitivity', 'version_sensitivity');
	}

	$query = "UPDATE openabm_model_version SET date_modified='%s', license_id=%d, reference_text='%s', examples='%s' WHERE model_id=%d AND version_num=%d";
	db_query ($query, date ("Y-m-d H:i:s"), $edit ["version_licenseId"], $edit ["version_ref"], $edit ["version_examples"], openabmma_getModelId ($pName), $versionNumber);

	drupal_goto ("mymodels/" . $pName . "/" . $action . "/version" . $versionNumber . "/reviewnote");
}

function openabmma_addVersion03 ($edit=null, $item=0)
{
	global $user;
	$pName = arg(1);

	$action = arg(2);
        // FIXME: duplicated code
	if ($user->name != openabmma_getModelOwner ($pName))
            return openabmma_formAccessError ("Only model owners can change metadata details of any version in the model. You are not registered as the owner of this model.");

	$versionNumber = openabmma_parseVersionNumber (arg(3));

	$query = "SELECT license_id, reference_text, examples FROM openabm_model_version WHERE model_id=%d AND version_num=%d";
	$result = (array) db_fetch_object (db_query ($query, openabmma_getModelId ($pName), $versionNumber));
	$licenseId = $result ['license_id'];
	$refText = $result ['reference_text'];
	$examples = $result ['examples'];

	$newVersion = $action == "add" ? 1 : 0;		
	drupal_add_js( openabmma_get_js_path() );

	$form['#attributes'] = array('enctype' => "multipart/form-data", 'onsubmit' => 'return validate_version_step03 (' . $newVersion . ')');

	$form["details"] = array(
		"#type" => 'fieldset',
		"#collapsible" => FALSE,
		"#collapsed" => FALSE,
		"#title" => null,
		"#description" => null,
	);

	$licenseTypes = array ();
	$result = db_query ("SELECT id, name, url FROM openabm_license");
	while ($node = db_fetch_object ($result))
            $licenseTypes [$node->id] = $node->name . " [" . $node->url . "]";

	$form ["details"]["version_licenseId"] = array (
		"#type" => "select",
		"#title" => t("License:"),
		"#default_value" => $edit ["version_licenseId"] == "" ? $licenseId : $edit ['version_licenseId'],
		"#options" => $licenseTypes,
		"#description" => null
	);

	$form ["details"]["version_ref"] = array (
		"#type" => "textarea",
		"#title" => "References:",
		"#default_value" => $edit ["version_ref"] == "" ? $refText : $edit ["version_ref"],
		"#maxlength" => 210,
		"#description" => t("Links to other hosted material of reference"),
		"#required" => false
	);

	$form ["details"]["version_examples"] = array (
		"#type" => "textarea",
		"#title" => "Examples:",
		"#default_value" => $edit ["version_examples"] == "" ? $examples : $edit ["version_examples"],
		"#maxlength" => 210,
		"#description" => t("Notes on how to use the version"),
		"#required" => false
	);

//	if ($action != "edit")
	{
		$directory = "files/models/" . $pName . "/v" . $versionNumber . "/doc";
		$fileCount = openabmma_getFileCount ($directory);
		if ($fileCount > 0)
		{
			$form ["details"]["version_doc_file"] = array (
				"#type" => "item",
				"#value" => "The file '<b>" . openabmma_getFirstFile ($directory) . "</b>' has been uploaded as the Documentation. To delete this file and upload a different one, click <a href='" . url ("mymodels/" . $pName . "/" . $action . "/version" . $versionNumber . "/files_opt/delete/doc") . "'>delete</a>."
			);
		}
		else
		{
			$form ["details"]["version_doc_file"] = array (
				"#type" => "file",
				"#title" => t("Documentation (required):")
			);
		}
	}

	$directory = "files/models/" . $pName . "/v" . $versionNumber . "/sensitivity";
	$fileCount = openabmma_getFileCount ($directory);
	if ($fileCount > 0)
		$form ["details"]["version_sensitivity"] = array (
			"#type" => "item",
			"#value" => "The file '<b>" . openabmma_getFirstFile ($directory) . "</b>' has been uploaded as the sensitivity file. To delete this file and upload a different one, click <a href='" . url ("mymodels/" . $pName . "/" . $action . "/version" . $versionNumber . "/files_basic/delete/sensitivity") . "'>delete</a>."
		);
	else		
		$form ["details"]["version_sensitivity"] = array (
			"#type" => "file",
			"#description" => t("File containing sensitivity analysis"),
			"#title" => t("File containing sensitivity data:")
		);

    $form ["details"]["submitAction"] = array (
            "#type" => "hidden",
            "#value" => 1
            );

    $form ["details"]["submit"] = array (
            "#type" => "submit",
            "#value" => t("Submit"),
            "#submit" => TRUE
            );

    $form ["details"]["cancel"] = array (
            "#type" => "submit",
            "#attributes" => array ('onclick' => '$(\'#edit-submitAction\').val(0);'),
            "#value" => t("Back"),
            "#submit" => TRUE
            );

	return ($form);	
}

function openabmma_optFiles_delete ()
{
	global $user;
	$pName = arg(1);
	$action = arg(2);
	$versionNumber = openabmma_parseVersionNumber (arg(3));
	$target = arg(6);

	if ($user->name != openabmma_getModelOwner ($pName))
		return openabmma_formAccessError ("Only model owners can change metadata details of any version in the model. You are not registered as the owner of this model.");

	if ($target != "doc" && $target != "dataset" && $target != "other")
		drupal_set_message ("<font color='red'><b>Invalid file category</b></font>");


	openabmma_cleantmp ($pName . "/v" . $versionNumber . "/" . $target);

	$gotoURL = "mymodels/" . $pName . "/" . $action . "/version" . $versionNumber . "/";
	switch ($target)
	{
		case "doc":	$gotoURL .= "step03";	break;
		case "dataset":
		case "other":	$gotoURL .= "step04";	break;
	}

	drupal_goto ($gotoURL);
}

function openabmma_basicFiles_delete ()
{
	global $user;
	$pName = arg(1);
	$action = arg(2);
	$versionNumber = openabmma_parseVersionNumber (arg(3));
	$target = arg(6);

	if ($user->name != openabmma_getModelOwner ($pName))
		return openabmma_formAccessError ("Only model owners can change metadata details of any version in the model. You are not registered as the owner of this model.");

	if ($target != "code" && $target != "sensitivity")
		drupal_set_message ("<font color='red'><b>Invalid file category</b></font>");

	openabmma_cleantmp ($pName . "/v" . $versionNumber . "/" . $target);

	if ($target == "code")
		drupal_goto ("mymodels/" . $pName . "/" . $action . "/version" . $versionNumber . "/step02");
	else if ($target == "sensitivity")
		drupal_goto ("mymodels/" . $pName . "/" . $action . "/version" . $versionNumber . "/step03");
}

function openabmma_addVersion04_submit ($form_id, $edit)
{
	global $user;
	$pName = arg(1);
	$action = arg(2);

	$versionNumber = openabmma_parseVersionNumber (arg(3));

	if ($user->name != openabmma_getModelOwner ($pName))
		return openabmma_accessError ("Only model owners can change metadata details of any version in the model. You are not registered as the owner of this model.");

	if ($_POST ["op"] == "Back")
		drupal_goto ("mymodels/" . $pName . "/" . $action . "/version" . $versionNumber . "/reviewnote");

	if ($edit ["version_inst"]['commented'] == '0' || $edit ["version_inst"]['cleanup'] == '0' || $edit ["version_inst"]['running'] == '0')
		drupal_set_message ("<b><font color='red'>Well-commented, cleanedup and running code is required before you submit your model version for review!</font></b><br/>");
	else
	{
		$directory = "files/models/" . $pName . "/v" . $versionNumber . "/dataset";
		$fileCount = openabmma_getFileCount ($directory);
		if ($fileCount == 0)
		{
		//	openabmma_cleantmp ($pName . '/v' . $versionNumber . '/dataset');
			openabmma_uploadFile ($pName . '/v' . $versionNumber . '/dataset', 'version_dataset');
		}

		$directory = "files/models/" . $pName . "/v" . $versionNumber . "/other";
		$fileCount = openabmma_getFileCount ($directory);
		if ($fileCount == 0)
		{
		//	openabmma_cleantmp ($pName . '/v' . $versionNumber . '/other');
			openabmma_uploadFile ($pName . '/v' . $versionNumber . '/other', 'version_other');
		}

		$query = "UPDATE openabm_model_version SET date_modified='%s', submittedReview=1, run_conditions='%s' WHERE model_id=%d AND version_num=%d";
		db_query ($query, date ("Y-m-d H:i:s"), $edit ["version_conditions"], openabmma_getModelId ($pName), $versionNumber);
		drupal_goto ("mymodels/" . $pName . "/" . $action . "/version" . $versionNumber . "/complete");
	}
}

function openabmma_addVersion04 ($edit=null, $item=0)
{
	global $user;
	$pName = arg(1);
	$action = arg(2);

        // FIXME: duplicated code
	if ($user->name != openabmma_getModelOwner ($pName))
		return openabmma_formAccessError ("Only model owners can change metadata details of any version in the model. You are not registered as the owner of this model.");

	$versionNumber = openabmma_parseVersionNumber (arg(3));

	$query = "SELECT run_conditions FROM openabm_model_version WHERE model_id=%d AND version_num=%d";
	$result = (array) db_fetch_object (db_query ($query, openabmma_getModelId ($pName), $versionNumber));
	$run_conditions = $result ['run_conditions'];

	$newVersion = $action == "add" ? 1 : 0;
	drupal_add_js( openabmma_get_js_path() );
	$form['#attributes'] = array('enctype' => "multipart/form-data", 'onsubmit' => 'return validate_version_step04 (' . $newVersion . ')');

	$form["details"] = array(
		"#type" => 'fieldset',
		"#collapsible" => FALSE,
		"#collapsed" => FALSE,
		"#title" => null,
		"#description" => "Components required for review",
	);

//	if ($action != "edit")
	{
		$directory = "files/models/" . $pName . "/v" . $versionNumber . "/dataset";
		$fileCount = openabmma_getFileCount ($directory);
		if ($fileCount > 0)
		{
			$form ["details"]["version_dataset"] = array (
				"#type" => "item",
				"#value" => "The file '<b>" . openabmma_getFirstFile ($directory) . "</b>' has been uploaded as the Dataset file. To delete this file and upload a different one, click <a href='" . url ("mymodels/" . $pName . "/" . $action . "/version" . $versionNumber . "/files_opt/delete/dataset") . "'>delete</a>."
			);
		}
		else
		{
			$form ["details"]["version_dataset"] = array (
				"#type" => "file",
				"#title" => t("Test data set (for running the version) - optional:")
			);
		}
	}

	$form ["details"]["version_conditions"] = array (
		"#type" => "textarea",
		"#title" => "Conditions or comments for running the code:",
		"#default_value" => $edit ["model_conditions"] == "" ? $run_conditions : $edit ["model_conditions"],
		"#maxlength" => 210,
		"#description" => t("Optional notes on running of version"),
		"#required" => false
	);

//	if ($action != "edit")
	{
		$directory = "files/models/" . $pName . "/v" . $versionNumber . "/other";
		$fileCount = openabmma_getFileCount ($directory);
		if ($fileCount > 0)
		{
			$form ["details"]["version_other"] = array (
				"#type" => "item",
				"#value" => "The file '<b>" . openabmma_getFirstFile ($directory) . "</b>' has been uploaded as the additional file. To delete this file and upload a different one, click <a href='" . url ("mymodels/" . $pName . "/" . $action . "/version" . $versionNumber . "/files_opt/delete/other") . "'>delete</a>."
			);
		}
		else
		{
			$form ["details"]["version_other"] = array (
				"#type" => "file",
				"#title" => t("Additional document to be included with version submission (optional):")
			);
		}
	}

//	if ($action != "edit")
		$form["details"]["version_inst"] = array(
		'#type' => 'checkboxes',
		'#title' => t('Submission requirements:'),
		'#default_value' => "false",
		'#options' => array(
		'commented' => t('The code has been well commented'),
		'cleanup' => t('The code has been cleaned up'),
		'running' => t('The version can be run'),
			),
		'#description' => t(''),
		);

    $form ["details"]["submitAction"] = array (
            "#type" => "hidden",
            "#value" => 1
            );

    $form ["details"]["submit"] = array (
            "#type" => "submit",
            "#value" => t("Submit"),
            "#submit" => TRUE
            );

    $form ["details"]["cancel"] = array (
            "#type" => "submit",
            "#attributes" => array ('onclick' => '$(\'#edit-submitAction\').val(0);'),
            "#value" => t("Back"),
            "#submit" => TRUE
            );

	return ($form);	
}

function openabmma_addVersionComplete ()
{
	global $user;
	$pName = arg(1);
        // FIXME: duplicated code
	if ($user->name != openabmma_getModelOwner ($pName))
            return openabmma_formAccessError ("Only model owners can change metadata details of any version in the model. You are not registered as the owner of this model.");

	$versionNumber = openabmma_parseVersionNumber (arg(3));

	$query = "SELECT submittedReview FROM openabm_model_version WHERE model_id=%d and version_num=%d";
	$result = (array) db_fetch_object (db_query ($query, $pName, $versionNumber));
	if ($result ['submittedReview'] == "1")
		$output = "<p><br/>Congratulations! Your version has been uploaded and submitted for review!</p>";
	else
		$output = "<p><br/>Congratulations! Your version has been uploaded!</p>";

	$webAddr = url ("mymodels/" . $pName, NULL, NULL, TRUE);
	$output .= "<p>Your model can be accessed via the URL:<br/>" . l($webAddr, $webAddr) . "</p>";
//	$output = 
	return $output;
}

function openabmma_addVersion01_submit ($form_id, $edit) {
    $pName = arg(1);
	$action = arg(2);

    global $user;
    if ($edit ["newVersion"] == "1") {
        $newVersion = TRUE;
    }
    else {
        $newVersion = FALSE;
    }

        $ownerName = openabmma_getModelOwner( $pName );
        // FIXME: duplicated code
        if ($user->name != $ownerName)
            return openabmma_ownerError ();

    if ($pName == "" || $pName == null)
    {
        drupal_set_message ("<b><font color='red'>Model name is required</font></b>");
        return;
    }

    if ($_POST ["op"] == "Cancel")
        drupal_goto ("mymodels/" . $pName);

    $pCount = strlen ($pName);
    for ($i=0; $i<$pCount; $i++)
    {
        if ($pName [$i] == ' ' || $pName [$i] == '`' || $pName [$i] == '~' || $pName [$i] == '/')
        {
            $errorString = "Invalid characters in model name. Model name cannot contain spaces, `, ~ and /.";
            drupal_set_message ("<b><font color='red'>" . $errorString . "</font></b>");
            return;
        }
    }

	$versionNumber = $edit ["versionNumber"];
    $visible = $edit ["version_visibility"]["visibility"];
    if ($visible != '0')
        $visible = '1';

	if ($newVersion)
	{
            // FIXME: rename to doc not 'doc'.
            mkdir ("files/models/" . $pName . "/v" . $versionNumber . "/doc", 0755, TRUE);
            mkdir ("files/models/" . $pName . "/v" . $versionNumber . "/code", 0755, TRUE);
            mkdir ("files/models/" . $pName . "/v" . $versionNumber . "/other", 0755, TRUE);
            mkdir ("files/models/" . $pName . "/v" . $versionNumber . "/dataset", 0755, TRUE);
            mkdir ("files/models/" . $pName . "/v" . $versionNumber . "/sensitivity", 0755, TRUE);

            $query = "INSERT INTO openabm_model_version (model_id, description, visible, version_num, date_modified, submittedReview) VALUES (%d, '%s', %d, %d, '%s', %d)";
            db_query($query, openabmma_getModelId ($pName), $edit ["version_description"], $visible, $versionNumber, date("Y-m-d H:i:s"), 0);

	//FIXME: Add code to invalidate the copy if it has already been sent for review

            drupal_goto("mymodels/" . $pName . "/" . $action . "/version" . $versionNumber . "/step02");
        }
    else
    {
        $query = "UPDATE openabm_model_version SET description='%s', date_modified='%s', visible=%d, submittedReview=%d WHERE model_id=%d AND version_num=%d";
        db_query ($query, $edit ["version_description"], date ("Y-m-d H:i:s"), $visible, 0, openabmma_getModelId ($pName), $versionNumber);

	//FIXME: Add code to invalidate the copy if it has already been sent for review

        drupal_goto("mymodels/" . $pName . "/" . $action . "/version" . $versionNumber . "/step02");
    }
}

function openabmma_addVersion01 ($edit=null, $item=0)
{
	global $user;

	$pName = arg(1);
	$action = arg(2);

	$versionNumber = openabmma_parseVersionNumber (arg(3));

	if (is_numeric ($versionNumber))	// version number specified in URL
//	if ($action == "edit" || $action == "add")
		$newVersion = "0";
	else
		$newVersion = "1";

	if ($newVersion == "0")
	{
            // FIXME: duplicated code
            if ($user->name != openabmma_getModelOwner ($pName))
                return openabmma_formAccessError ("Only model owners can change metadata details of any version in the model. You are not registered as the owner of this model.");
	}

	if ($newVersion == "1")
	{
		$result = (array) db_fetch_object (db_query ("SELECT max(version_num) FROM openabm_model_version WHERE model_id=%d", openabmma_getModelId ($pName)));
		$versionNumber = $result['max(version_num)'] + 1;
	}

	$desc = '';
	$visible = FALSE;
	if ($newVersion == "0")
	{
		$query = "SELECT description, visible FROM openabm_model_version WHERE model_id=%d AND version_num=%d";
		$result = (array) db_fetch_object (db_query ($query, openabmma_getModelId ($pName), $versionNumber));

		$desc = $result ['description'];
		$visible = $result ['visible'];
		if ($visible == "1")
			$visible = TRUE;
		else
			$visible = FALSE;
	}

	drupal_add_js( openabmma_get_js_path() );
	$form['#attributes'] = array ('onsubmit' => 'return validate_version_step01 (' . $newVersion . ')');
	$form["details"] = array(
		"#type" => 'fieldset',
		"#collapsible" => FALSE,
		"#collapsed" => FALSE,
		"#title" => null,
		"#description" => null,
	);

	if ($newVersion == "0")
		$form ["details"]["reviewNote"] = array (
			"#type" => "item",
			"#value" => "<u><b>Note:</b></u> If this model has previously been submitted for review, it will automatically be invalidated from the reviewer's list and you would have to submit it again."
		);

	$form ["details"]["newVersion"] = array (
		"#type" => "hidden",
		"#value" => $newVersion
	);

	$form ["details"]["versionNumber"] = array (
		"#type" => "hidden",
		"#value" => $versionNumber
	);
/*
	$form ["details"]["model_name"] = array (
		"#type" => "item",
		"#title" => t("Model name:"),
		"#description" => null,
		"#value" => $pName,
//		"#required" => true,		// Commented because clicking Cancel validates this field too!
		"#maxlength" => 210
//		'#autocomplete_path' => 'user/autocomplete',
	);
*/
	$form ["details"]["version_description"] = array (
		"#type" => "textarea",
		"#title" => t("Description of this version:"),
		"#default_value" => $edit ["version_description"] == "" ? $desc : $edit ["version_description"],
		"#description" => null,
		"#required" => false
	);

	if ($visible)
	{
		$form["details"]["version_visibility"] = array(
		'#type' => 'checkboxes',
		'#title' => t('Version visibility:'),
		"#attributes" => array ('checked' => 'checked'),
	//	'#default_value' => array (TRUE),
		'#options' => array(
		'visibility' => t('I want to make this version visible to all'),
			),
		'#description' => t('Enabling this option will make this version visible to public'),
		);
	}
	else
	{
		$form["details"]["version_visibility"] = array(
		'#type' => 'checkboxes',
		'#title' => t('Version visibility:'),
	//	'#default_value' => array (TRUE),
		'#options' => array(
		'visibility' => t('I want to make this version visible to all'),
			),
		'#description' => t('Enabling this option will make this version visible to public'),
		);
	}

    $form ["details"]["submitAction"] = array (
            "#type" => "hidden",
            "#value" => 1
            );

    $form ["details"]["submit"] = array (
            "#type" => "submit",
            "#value" => t("Submit"),
            "#submit" => TRUE
            );

    $form ["details"]["cancel"] = array (
            "#type" => "submit",
            "#attributes" => array ('onclick' => '$(\'#edit-submitAction\').val(0);'),
            "#value" => t("Cancel"),
            "#submit" => TRUE
            );

	return ($form);
}

function openabmma_addVersion ()
{
	global $user;
	$pName = arg(1);

	drupal_add_css (openabmma_get_css_path ());

	if ($user->name != openabmma_getModelOwner ($pName))
               return openabmma_accessError ("Only model owners can add versions to any model. You are not registered as the owner of this model");

    $output = "<p><br/>To upload a new model version, it can take a number of steps. The first three steps are mandatory.<p>&nbsp;</p>";
    $output .= "<table border='0' cellpadding='0' cellspacing='0' width='100%'>";
    $output .= "<tr class='openabmData'><td><b>Step 1</b></td><td><font color='red'>Mandatory</font></td><td>Version description and visibility to public</td></tr>";
    $output .= "<tr class='openabmData'><td><b>Step 2</b></td><td><font color='red'>Mandatory</font></td><td>Code files, language and platform details</td></tr>";
    $output .= "<tr class='openabmData'><td><b>Step 3</b></td><td><font color='red'>Mandatory</font></td><td>License information, References, Examples and Sensitivity data</td></tr>";
    $output .= "<tr class='openabmData'><td><b>Step 4</b></td><td><font color='green'>Only for review</font></td><td>Collecting documents for version review</td></tr>";
    $output .= "</table>";
    $output .= "<p>&nbsp;</p>" . l ("Click here to proceed to first step", "mymodels/" . $pName . "/add/version/step01");	
    return $output;
}

function openabmma_deleteVersion ()
{
	global $user;
	$pName = arg (1);
	$versionNumber = openabmma_parseVersionNumber (arg(3));

	if ($user->name != openabmma_getModelOwner ($pName))
		return "<br/>Only model owners can delete versions of any model. You are not registered as the owner of this model.";

	if (!is_numeric ($versionNumber))
		return "<br/><b><font color='red'>Invalid version number</font></b>";

	$query = "DELETE FROM openabm_model_version WHERE model_id=%d AND version_num=%d";
	db_query ($query, openabmma_getModelId ($pName), $versionNumber);

	openabmma_cleantmp ($pName . '/v' . $versionNumber, true);

	drupal_goto ("mymodels/" . $pName);
	return "";
}


