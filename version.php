<?php
define("MODEL_DIRECTORY", 'model-archive/');

function openabmma_addVersion() {
	global $user;
  $modelName = arg(1);

  drupal_add_css(openabmma_get_css_path());

  if ($user->name != openabmma_getModelOwner($modelName)) {
    return openabmma_accessError("Only model owners can add versions to any model. You are not registered as the owner of this model");
	}

  $output = "<p><br/>To upload a new model version, it can take a number of steps. The first three steps are mandatory.<p>&nbsp;</p>";
  $output .= "<table border='0' cellpadding='0' cellspacing='0' width='100%'>";
  $output .= "<tr class='openabmData'><td><b>Step 1</b></td><td><font color='red'>Mandatory</font></td><td>Version description and visibility to public</td></tr>";
  $output .= "<tr class='openabmData'><td><b>Step 2</b></td><td><font color='red'>Mandatory</font></td><td>Code files, language and platform details</td></tr>";
  $output .= "<tr class='openabmData'><td><b>Step 3</b></td><td><font color='red'>Mandatory</font></td><td>License information, references to publications, examples and sensitivity data</td></tr>";
  $output .= "<tr class='openabmData'><td><b>Step 4</b></td><td><font color='green'>Only for review</font></td><td>Collecting documents for version review</td></tr>";
  $output .= "</table>";
  $output .= "<p>&nbsp;</p>". l("Click here to proceed to first step", MODEL_DIRECTORY . $modelName ."/add/version/step01");
	
  return $output;
}

function openabmma_addVersion01() {
	global $user;
	$modelName = arg(1);
	$action = arg(2);
	$versionNumber = openabmma_parseVersionNumber(arg(3));
		
	if (is_numeric($versionNumber)) {  // version number specified in URL
	  $newVersion = 0;
	}
	else {
	  $newVersion = 1;
	}
	
	if ($newVersion == 0) {
    // FIXME: duplicated code
    if ($user->name != openabmma_getModelOwner($modelName)) {
      return openabmma_formAccessError("Only model owners can edit existing versions of a model. You are not registered as the owner of this model.");
    }
  }
  else {
    $result = db_fetch_array(db_query("SELECT max(version_num) FROM openabm_model_version WHERE model_id=%d", openabmma_getModelId($modelName)));
    $versionNumber = $result['max(version_num)'] + 1;
  }	

	$desc = '';
	$visible = true;
	if ($newVersion == "0")
	{
		$query = "SELECT description, visible FROM openabm_model_version WHERE model_id=%d AND version_num=%d";
		$result = (array) db_fetch_object (db_query ($query, openabmma_getModelId ($modelName), $versionNumber));

		$desc = $result['description'];
		$visible = $result['visible'];
		if ($visible == "1") {
			$visible = TRUE;
		}
		else {
			$visible = FALSE;
		}
	}

	drupal_add_js(openabmma_get_js_path());
	$form['#attributes'] = array ('onsubmit' => 'return validate_version_step01 ('. $newVersion .')');
  $form["details"] = array(
    "#type" => 'fieldset',
    "#collapsible" => FALSE,
    "#collapsed" => FALSE,
    "#title" => null,
    "#description" => null,
  );

	$stepLinkText = "<table width='100%'><tr><td><a href=\"javascript:validateAndGo ('" . $modelName . "', " . $versionNumber . ", '" . $action . "', 1, '" . url (MODEL_DIRECTORY . $modelName . "/" . $action . "/version" . $versionNumber . "/step01") . "');\">Step 1</a></td><td><a href=\"javascript:validateAndGo ('" . $modelName . "', " . $versionNumber . ", '" . $action . "', 1, '" . url (MODEL_DIRECTORY . $modelName . "/" . $action . "/version" . $versionNumber . "/step02") . "');\">Step 2</a></td><td><a href=\"javascript:validateAndGo ('" . $modelName . "', " . $versionNumber . ", '" . $action . "', 1, '" . url (MODEL_DIRECTORY . $modelName . "/" . $action . "/version" . $versionNumber . "/step03") . "');\">Step 3</a></td><td><a href=\"javascript:validateAndGo ('" . $modelName . "', " . $versionNumber . ", '" . $action . "', 1, '" . url (MODEL_DIRECTORY . $modelName . "/" . $action . "/version" . $versionNumber . "/step04") . "');\">Step 4</a></td></tr></table><p>&nbsp;</p>";

  if ($newVersion == 0) {
    $form ["details"]["reviewNote"] = array(
      "#type" => "item",
      "#value" => $stepLinkText ."<u><b>Note:</b></u> If this model has previously been submitted for review, it will automatically be removed from the reviewer's list and you would have to submit it again.",
    );
  }

  $form["details"]["newVersion"] = array(
    "#type" => "hidden",
    "#value" => $newVersion,
  );

  $form["details"]["versionNumber"] = array(
    "#type" => "hidden",
    "#value" => $versionNumber,
  );

  $form["details"]["version_description"] = array(
    "#type" => "textarea",
    "#title" => t("Description of this version"),
    "#default_value" => $form_state['values']["version_description"] == "" ? $desc : $form_state['values']["version_description"],
    "#description" => NULL,
    "#required" => FALSE,
  );

  // FIXME: get rid of duplicate code
  if (!$visible) {
    $form["details"]["version_visibility"] = array(
      '#type' => 'checkboxes',
      '#title' => t('Version visibility'),
      "#attributes" => array ('checked' => 'checked'),
      '#default_value' => 0,
      '#options' => array('visibility' => t('I want to make this version private')),
      '#description' => t('Enabling this option will make this version NOT visible to the public'),
    );
  }
  else {
    $form["details"]["version_visibility"] = array(
      '#type' => 'checkboxes',
      '#title' => t('Version visibility'),
      '#default_value' => 0,
      '#options' => array('visibility' => t('I want to make this version private')),
      '#description' => t('Enabling this option will make this version NOT visible to the public'),
    );
  }

  $form["details"]["submitAction"] = array(
    "#type" => "hidden",
    "#value" => 1,
  );

  $form["details"]["submit"] = array(
    "#type" => "submit",
    "#value" => t("Submit"),
  );

  $form["details"]["cancel"] = array(
    "#type" => "button",
    "#value" => t("Back"),
    "#executes_submit_callback" => TRUE,
    "#submit" => array('openabmma_form_cancel'),
  );

  return ($form);
}

function openabmma_addVersion01_submit($form, &$form_state) {
	global $user;
	$modelName = arg(1);
  $action = arg(2);
	
	if ($form_state['values']['newVersion'] == "1") {
    $newVersion = TRUE;
  }
  else {
    $newVersion = FALSE;
  }
	
  $ownerName = openabmma_getModelOwner($modelName);

  // FIXME: duplicated code
  if ($user->name != $ownerName) {
    $form_state['redirect'] = openabmma_ownerError();
  }

  if ($modelName == "" || $modelName == null) {
    drupal_set_message("<b><font color='red'>Model name is required</font></b>");
    return;
  }

	// FIXME:  This check can be improved
  $pCount = strlen($modelName);
  for ($i=0; $i<$pCount; $i++) {
    if ($modelName[$i] == ' ' || $modelName[$i] == '`' || $modelName[$i] == '~' || $modelName[$i] == '/') {
      $errorString = "Invalid characters in model name. Model name cannot contain spaces, `, ~ and /.";
      drupal_set_message("<b><font color='red'>". $errorString ."</font></b>");
      return;
    }
  }

  $versionNumber = $form_state['values']["versionNumber"];
  $notVisible = $form_state['values']["version_visibility"]["visibility"];
  if ($notVisible != '0') {
    $notVisible = '1';
  }

	if ($notVisible == '0') {
    $visible = '1';
  }
  else {
    $visible = '0';
  }

  if ($newVersion) {
    // FIXME: rename to doc not 'doc'.
    mkdir("files/models/". $modelName ."/v". $versionNumber ."/doc", 0755, TRUE);
    mkdir("files/models/". $modelName ."/v". $versionNumber ."/code", 0755, TRUE);
    mkdir("files/models/". $modelName ."/v". $versionNumber ."/other", 0755, TRUE);
    mkdir("files/models/". $modelName ."/v". $versionNumber ."/dataset", 0755, TRUE);
    mkdir("files/models/". $modelName ."/v". $versionNumber ."/sensitivity", 0755, TRUE);

		$table = "openabm_model_version";
		$record = new stdClass();

	  $record->model_id = openabmma_getModelId($modelName);
		$record->description = $form_state['values']["version_description"];
		$record->visible = $visible;
		$record->version_num = $versionNumber;
		$record->date_modified = date("Y-m-d H:i:s");
		$record->submittedReview = 0;

		drupal_write_record($table, $record);
	}
	else {
    $query = "UPDATE openabm_model_version SET description='%s', date_modified='%s', visible=%d, submittedReview=%d WHERE model_id=%d AND version_num=%d";
    db_query($query, $form_state['values']["version_description"], date("Y-m-d H:i:s"), $visible, 0, openabmma_getModelId($modelName), $versionNumber);
	}
	//FIXME: Add code to invalidate the copy if it has already been sent for review
	$form_state['redirect'] = MODEL_DIRECTORY . $modelName . "/" . $action . "/version" . $versionNumber . "/step02";
}

function openabmma_addVersion02(&$form_state) {
  global $user;

  $modelName = arg(1);
  $action = arg(2);

  // FIXME: duplicated code
  if ($user->name != openabmma_getModelOwner($modelName)) {
    return openabmma_formAccessError("Only model owners can change metadata details of any version in the model. You are not registered as the owner of this model.");
  }

  $versionNumber = openabmma_parseVersionNumber(arg(3));
  $query = "SELECT model_language_id, os, os_version, other_language, language_version, framework, framework_version FROM openabm_model_version WHERE model_id=%d AND version_num=%d";
  $result = db_fetch_array(db_query($query, openabmma_getModelId($modelName), $versionNumber));
  
	$progLang = $result['model_language_id'];
  $other_language = $result['other_language'];
  $progLangVer = $result['language_version'];
  $osName = $result['os'];
  $osVersion = $result['os_version'];
  $frameworkName = $result['framework'];
  $framework_version = $result['framework_version'];

  $newVersion = ($action == "add") ? 1 : 0;		
  drupal_add_js( openabmma_get_js_path() );

	$stepLinkText = "<table width='100%'><tr><td><a href=\"javascript:validateAndGo ('" . $modelName . "', " . $versionNumber . ", '" . $action . "', 2, '" . url (MODEL_DIRECTORY . $modelName . "/" . $action . "/version" . $versionNumber . "/step01") . "');\">Step 1</a></td><td><a href=\"javascript:validateAndGo ('" . $modelName . "', " . $versionNumber . ", '" . $action . "', 2, '" . url (MODEL_DIRECTORY . $modelName . "/" . $action . "/version" . $versionNumber . "/step02") . "');\">Step 2</a></td><td><a href=\"javascript:validateAndGo ('" . $modelName . "', " . $versionNumber . ", '" . $action . "', 2, '" . url (MODEL_DIRECTORY . $modelName . "/" . $action . "/version" . $versionNumber . "/step03") . "');\">Step 3</a></td><td><a href=\"javascript:validateAndGo ('" . $modelName . "', " . $versionNumber . ", '" . $action . "', 2, '" . url (MODEL_DIRECTORY . $modelName . "/" . $action . "/version" . $versionNumber . "/step04") . "');\">Step 4</a></td></tr></table><p>&nbsp;</p>";

	$files_root = "files/models/" . $modelName . "/v" . $versionNumber;
	if (openabmma_getFileCount ($files_root . "/code") == 0)
		$stepLinkText = "";	// Don't allow user to navigate unless he uploads a code file OR unless he clicks 'Back'
					// There is a possibility that the user may escape from having to upload the code file, but we make all
					// these checks at the end before the model is submitted and then redirect him to the appropriate step


  $form['#attributes'] = array('enctype' => "multipart/form-data", 'onsubmit' => 'return validate_version_step02('. $newVersion .')');
  $form["details"] = array(
          "#type" => 'fieldset',
          "#collapsible" => FALSE,
          "#collapsed" => FALSE,
          "#title" => null,
          "#description" => $stepLinkText,
	);

	$directory = "files/models/". $modelName ."/v". $versionNumber ."/code";
	$fileCount = openabmma_getFileCount($directory);
	if ($fileCount > 0) {
		$form["details"]["version_code_file"] = array(
			"#type" => "item",
			"#value" => "The file '<b>". openabmma_getFirstFile ($directory) ."</b>' has been uploaded as the code file. To delete this file and upload a different one, click <a href='". url(MODEL_DIRECTORY . $modelName ."/". $action ."/version". $versionNumber ."/files_basic/delete/code") ."'>delete</a>."
		);
	}
	else {
		$form["details"]["version_code_file"] = array(
			"#type" => "file",
			"#title" => t("Source code (required)")
		);
	}

	$languages = array ();
	$result = db_query ("SELECT id, name FROM openabm_model_language ORDER BY name");
	while ($node = db_fetch_object($result)) {
		$languages[$node->id] = $node->name;
	}

	$form ["details"]["version_language"] = array (
	        '#id' => 'programming_language',
	        "#type" => "select",
	        "#title" => t("Programming Language"),
	        "#options" => $languages,
	        "#default_value" => $form_state['values']["version_language"] == "" ? $progLang : $form_state['values']["version_language"],
	        "#description" => 'The programming language used to implement the model',
	//            '#attributes' => array('onChange' => 'updateFrameworks()')
	        );

	$form ["details"]["other_language"] = array (
	        "#type" => "textfield",
	        "#title" => t("Other language (if not mentioned in above list)"),
	        "#maxlength" => 255,
	        "#default_value" => $form_state['values']["other_language"] == "" ? $other_language : $form_state['values']["other_language"],
	        "#required" => FALSE,
	        );

	$form ["details"]["version_language_ver"] = array (
	        "#type" => "textfield",
	        "#size" => 10,
	        "#title" => t("Programming Language Version"),
	        "#default_value" => $form_state['values']["version_language_ver"] == "" ? $progLangVer : $form_state['values']["version_language_ver"],
	        "#description" => 'The version of the programming language used to implement this model, if necessary.',
	        );

	$arrayElements = array (
			'Linux' => 'Linux',
			'Mac' => 'Mac',
			'Windows' => 'Windows',
			'Platform Independent' => 'Platform Independent',
			'Other' => 'Other');

	$form ["details"]["os"] = array (
      "#type" => "select",
      "#title" => t("Operating System"),
      "#default_value" => $form_state['values']["os"] == "" ? (openabmma_inList($osName, $arrayElements) == -1 ? "Other" : $osName) : $form_state['values']["os"],
      "#options" => $arrayElements,
      "#description" => NULL,
      );

	$form ["details"]["other_os"] = array (
	        "#type" => "textfield",
	        "#title" => t("Other OS (if not mentioned in above list)"),
	        "#maxlength" => 255,
	        "#default_value" => $form_state['values']["other_os"] == "" ? (openabmma_inList($osName, $arrayElements) == -1 ? $osName : "") : $form_state['values']["other_os"],
	        "#required" => FALSE,
	        );

	$form ["details"]["os_ver"] = array (
	        "#type" => "textfield",
	        "#size" => 10,
	        "#title" => t("Operating System Version"),
	        "#default_value" => $form_state['values']["os_ver"] == "" ? $osVersion : $form_state['values']["os_ver"],
	        "#description" => NULL,
	        );

	$arrayElements = array ();
	$result = db_query ("SELECT name FROM openabm_framework ORDER BY name");
	while ($node = db_fetch_object ($result)) {
	    $arrayElements [$node->name] = $node->name;
	}
	$arrayElements ["Other"] = "Other";

	$form ["details"]["framework"] = array (
	        '#id' => 'framework',
	        "#type" => "select",
	        "#title" => t("Framework used"),
	        "#default_value" => $form_state['values']["framework"] == "" ? (openabmma_inList($frameworkName, $arrayElements) == -1 ? "Other" : $frameworkName) : $form_state['values']["framework"],
	        "#options" => $arrayElements,
	        "#description" => 'The ABM framework used to implement this model',
	        );

	$form ["details"]["other_framework"] = array (
	        "#type" => "textfield",
	        "#title" => t("Other framework (if not mentioned in above list)"),
	        "#maxlength" => 255,
	        "#default_value" => $form_state['values']["other_framework"] == "" ? (openabmma_inList ($frameworkName, $arrayElements) == -1 ? $frameworkName : "") : $form_state['values']["other_framework"],
	        "#required" => FALSE,
	        );

	$form ["details"]["framework_ver"] = array (
	        "#type" => "textfield",
	        "#size" => 10,
	        "#title" => t("Framework Version"),
	        "#default_value" => $form_state['values']["framework_ver"] == "" ? $framework_version : $form_state['values']["framework_ver"],
	        "#description" => NULL,
	        );

	$form ["details"]["submitAction"] = array (
	        "#type" => "hidden",
	        "#value" => 1,
	        );

	$form ["details"]["submit"] = array (
	        "#type" => "submit",
	        "#value" => t("Submit"),
	        );

	$form["details"]["cancel"] = array(
			"#type" => "button",
			"#value" => t("Back"),
			"#executes_submit_callback" => TRUE,
			"#submit" => array('openabmma_form_cancel'),
	);

	return ($form);	
}

function openabmma_addVersion02_submit ($form, &$form_state) {
	global $user;
	$errString = '';
	$modelName = arg(1);
	$action = arg(2);
	
	if ($user->name != openabmma_getModelOwner ($modelName))
		return openabmma_accessError ("Only model owners can change metadata details of any version in the model. You are not registered as the owner of this model.");

	$versionNumber = openabmma_parseVersionNumber (arg(3));
	
	$directory = "files/models/". $modelName ."/v". $versionNumber ."/code";
	$fileCount = openabmma_getFileCount($directory);
	if ($fileCount == 0) {
		openabmma_cleantmp($modelName . '/v' . $versionNumber . '/code');
		if (openabmma_uploadFile($modelName .'/v'. $versionNumber .'/code', 'version_code_file') == null) {
			drupal_set_message("<font color='red'><b>Error uploading code file, please check the path of the file specified</b></font>");
			return;
		}
	}

	if ($errString != "")
	{
		drupal_set_message("<b><font color='red'>" . $errString . "</font></b>");
		return;
	}

	$os = $edit ["os"];
	if ($os == "Other")	{ // selected other, so get the "other" fields contents first
		$os = $form_state['values']["other_os"];
		if ($os == "") {
			drupal_set_message("<b><font color='red'>Please choose an operating system from the dropdown list or enter the name in the text box below it.</font></b>");
			return;
		}
	}
	
	$osVersion = $form_state['values']["os_ver"];

	$framework = $form_state['values']["framework"];
	if ($framework == "Other") {	// selected other, so get the "other" fields contents first
		$framework = $form_state['values']["other_framework"];
		if ($framework == "") {
			drupal_set_message("<b><font color='red'>Please choose a Framework from the dropdown list or enter the name in the text box below it.</font></b>");
			return;
		}
	}

	$framework_version = $form_state['values']["framework_ver"];
        if ($framework = '') {
            // allow framework version without framework?  or spit out an
            // error?  Actually, should have JS enable textfields
        }

	$pLanguage = $form_state['values']["version_language"];

	// Check if this is the index of the "Other"
	$query = "SELECT id FROM openabm_model_language WHERE name = 'Other'";
	$result = (array) db_fetch_object(db_query($query));

	$other_language = '';
	if ($pLanguage == $result['id']) {
		$other_language = $form_state['values']["other_language"];
    if ($other_language == "") {
			drupal_set_message ("<b><font color='red'>Please choose a programming language from the dropdown list or enter the name in the text box below it.</font></b>");
			return;
		}
	}	
	
	$pLanguageVersion = $form_state['values']["version_language_ver"];

	$query = "UPDATE openabm_model_version SET date_modified='%s', model_language_id=%d, other_language='%s', language_version='%s', os='%s', os_version='%s', framework='%s', framework_version='%s' WHERE model_id=%d AND version_num=%d";
	db_query($query, date("Y-m-d H:i:s"), $pLanguage, $other_language, $pLanguageVersion, $os, $osVersion, $framework, $framework_version, openabmma_getModelId($modelName), $versionNumber);

	$form_state['redirect'] = MODEL_DIRECTORY . $modelName ."/". $action ."/version". $versionNumber ."/step03";
}

function openabmma_addVersion03(&$form_state) {
	global $user;
	$modelName = arg(1);
	$action = arg(2);
	$versionNumber = openabmma_parseVersionNumber (arg(3));
	
	// FIXME: duplicated code
	if ($user->name != openabmma_getModelOwner($modelName)) {
		return openabmma_formAccessError ("Only model owners can change metadata details of any version in the model. You are not registered as the owner of this model.");
	}
	
	$query = "SELECT license_id, reference_text, examples FROM openabm_model_version WHERE model_id=%d AND version_num=%d";
	$result = (array) db_fetch_object (db_query($query, openabmma_getModelId($modelName), $versionNumber));
	$licenseId = $result['license_id'];
	$refText = $result['reference_text'];
	$examples = $result['examples'];
	$newVersion = $action == "add" ? 1 : 0;		

	drupal_add_js( openabmma_get_js_path() );

	$stepLinkText = "<table width='100%'><tr><td><a href=\"javascript:validateAndGo ('" . $modelName . "', " . $versionNumber . ", '" . $action . "', 3, '" . url (MODEL_DIRECTORY . $modelName . "/" . $action . "/version" . $versionNumber . "/step01") . "');\">Step 1</a></td><td><a href=\"javascript:validateAndGo ('" . $modelName . "', " . $versionNumber . ", '" . $action . "', 3, '" . url (MODEL_DIRECTORY . $modelName . "/" . $action . "/version" . $versionNumber . "/step02") . "');\">Step 2</a></td><td><a href=\"javascript:validateAndGo ('" . $modelName . "', " . $versionNumber . ", '" . $action . "', 3, '" . url (MODEL_DIRECTORY . $modelName . "/" . $action . "/version" . $versionNumber . "/step03") . "');\">Step 3</a></td><td><a href=\"javascript:validateAndGo ('" . $modelName . "', " . $versionNumber . ", '" . $action . "', 3, '" . url (MODEL_DIRECTORY . $modelName . "/" . $action . "/version" . $versionNumber . "/step04") . "');\">Step 4</a></td></tr></table><p>&nbsp;</p>";

// Files

//	$form['#attributes'] = array('enctype' => "multipart/form-data", 'onsubmit' => 'return validate_version_step03 (' . $newVersion . ')');
	$form["details"] = array(
		"#type" => 'fieldset',
		"#collapsible" => FALSE,
		"#collapsed" => FALSE,
		"#title" => NULL,
		"#description" => $stepLinkText,
	);

	$licenseTypes = array();
	$result = db_query("SELECT id, name, url FROM openabm_license");
	while ($node = db_fetch_object($result)) {
		$licenseTypes[$node->id] = $node->name ." [". $node->url ."]";
	}

	$form["details"]["version_licenseId"] = array(
		"#type" => "select",
		"#title" => t("License"),
		"#default_value" => $form_state['values']["version_licenseId"] == "" ? $licenseId : $form_state['values']['version_licenseId'],
		"#options" => $licenseTypes,
		"#description" => NULL,
	);

	$form ["details"]["version_ref"] = array (
		"#type" => "textarea",
		"#title" => "References to publications where the original model is described",
		"#default_value" => $form_state['values']["version_ref"] == "" ? $refText : $form_state['values']["version_ref"],
		"#description" => t("Links to other hosted material of reference"),
		"#required" => FALSE,
	);

	$form ["details"]["version_examples"] = array (
		"#type" => "textarea",
		"#title" => "Examples",
		"#default_value" => $form_state['values']["version_examples"] == "" ? $examples : $form_state['values']["version_examples"],
		"#maxlength" => 255,
		"#description" => t("Notes on how to use the version"),
		"#required" => FALSE,
	);

// File Uploads

	$form ["details"]["submitAction"] = array (
		  "#type" => "hidden",
		  "#value" => 1,
	);

	$form ["details"]["submit"] = array (
			"#type" => "submit",
			"#value" => t("Submit"),
	);

	$form["details"]["cancel"] = array(
			"#type" => "button",
			"#value" => t("Back"),
			"#executes_submit_callback" => TRUE,
			"#submit" => array('openabmma_form_cancel'),
	);

	return ($form);	
}

function openabmma_addVersion03_submit ($form, &$form_state) {
	global $user;
	$modelName = arg(1);
	$action = arg(2);
	$versionNumber = openabmma_parseVersionNumber(arg(3));
	
	if ($user->name != openabmma_getModelOwner ($modelName))
		return openabmma_accessError ("Only model owners can change metadata details of any version in the model. You are not registered as the owner of this model.");

// File Uploads

	$query = "UPDATE openabm_model_version SET date_modified='%s', license_id=%d, reference_text='%s', examples='%s' WHERE model_id=%d AND version_num=%d";
	db_query ($query, date("Y-m-d H:i:s"), $form_state['values']["version_licenseId"], $form_state['values']["version_ref"], $form_state['values']["version_examples"], openabmma_getModelId($modelName), $versionNumber);

	$form_state['redirect'] = MODEL_DIRECTORY . $modelName . "/" . $action . "/version" . $versionNumber . "/reviewnote";
}

function openabmma_askIfReview() {
  global $user;
  $modelName = arg(1);

  if ($user->name != openabmma_getModelOwner($modelName)) {
    return openabmma_formAccessError("Only model owners can change metadata details of any model. You are not registered as the owner of this model.");
  }
  
  drupal_add_css(openabmma_get_css_path());

  $output .= "<br/><p><table border='0' cellpadding='1' cellspacing='0' width='100%'>";
  $output .= "<tr class='openabmData'><td><b>Step 1</b></td><td><i>[Complete]</i></td><td>Version description and visibility to public</td></tr>";
  $output .= "<tr class='openabmData'><td><b>Step 2</b></td><td><i>[Complete]</i></td><td>Code files, language and platform details</td></tr>";
  $output .= "<tr class='openabmData'><td><b>Step 3</b></td><td><i>[Complete]</i></td><td>License information, references to publications, examples and sensitivity data</td></tr>";
  $output .= "<tr class='openabmData'><td><b>Step 4</b></td><td><font color='green'>Only for review</font></td><td>Collecting documents for model review</td></tr>";
  $output .= "</table>";

  $output .= "<p>&nbsp;<br/>At this point, you have completed the basic requirements of submitting a version of a model to OpenABM.org.<br/>You could complete the process of adding your version to the OpenABM repository by clicking on the 'Finish' button below.</p>";

  $output .= "<p>Alternatively, you could indicate the version is ready for a review by the committee at OpenABM.org. If you wish to submit your model for review, click the 'Proceed to Submit Version for Review' button. In that case, you would be directed to a page that asks for some more information such as the Documentation.</p>";

  $output .= "<p><u>Note: You can submit the version for review at any time by clicking on the \"Send this version for review\" link in the model workspace.</u></p>";

  $form["details"] = array(
    "#type" => 'fieldset',
    "#collapsible" => FALSE,
    "#collapsed" => FALSE,
    "#title" => NULL,
    "#visible" => FALSE,
    "#description" => NULL,
  );

  $form["details"]["model_name"] = array(
    "#type" => "item",
    "#title" => NULL,
    "#value" => $output,
    "#description" => NULL,
  );

  $form["details"]["review"] = array(
    "#type" => "submit",
    "#value" => t("Proceed to Submit Version for Review"),
  );

  $form["details"]["noreview"] = array(
    "#type" => "submit",
    "#value" => t("Finish the Add Version process"),
  );

  $form["details"]["cancel"] = array(
    "#type" => "submit",
    "#value" => t("Back"),
  );

  return ($form);
}

function openabmma_askIfReview_submit($form, &$form_state) {
  global $user;
  
  $modelName = arg(1);
  $action = arg(2);
  $versionNumber = openabmma_parseVersionNumber(arg(3));

  if ($user->name != openabmma_getModelOwner($modelName)) {
    return openabmma_accessError("Only model owners can change metadata details of any model. You are not registered as the owner of this model.");
  }

  if ($_POST["op"] == "Proceed to Submit Version for Review") {
    $form_state['redirect'] = MODEL_DIRECTORY . $modelName . "/" . $action . "/version" . $versionNumber . "/step04";
  }
  else if ($_POST["op"] == "Finish the Add Version process") {
    $form_state['redirect'] = MODEL_DIRECTORY . $modelName . "/" . $action . "/version" . $versionNumber . "/complete";
  }
  else if ($_POST["op"] == "Back") {
    $form_state['redirect'] = MODEL_DIRECTORY . $modelName . "/" . $action . "/version" . $versionNumber . "/step03";
  }
  else {
    $form_state['redirect'] = "/";
  }
}

function openabmma_addVersionComplete() {
	global $user;
	$modelName = arg(1);
	$action = arg(2);
	$versionNumber = openabmma_parseVersionNumber (arg(3));
	
// FIXME: duplicated code
	if ($user->name != openabmma_getModelOwner($modelName)) {
		return openabmma_formAccessError ("Only model owners can change metadata details of any version in the model. You are not registered as the owner of this model.");
	}
	
	$stepURL_root = MODEL_DIRECTORY . $modelName ."/". $action ."/version". $versionNumber ."/step";

// Files: jump to correct step if missing

	$query = "SELECT submittedReview FROM openabm_model_version WHERE model_id=%d and version_num=%d";
	$result = (array) db_fetch_object(db_query($query, $modelName, $versionNumber));
	if ($result['submittedReview'] == "1")
		$output = "<p><br/>Congratulations! Your version has been uploaded and submitted for review!</p>";
	else
		$output = "<p><br/>Congratulations! Your version has been uploaded!</p>";

//	$webAddr = url(MODEL_DIRECTORY . $modelName, NULL, NULL, TRUE);
//	$output .= "<p>Your model can be accessed via the URL:<br/>". l($webAddr, $webAddr) ."</p>";
	
	return $output;
}

function openabmma_addVersion04 (&$form_state) {		// Step 04 (Optional): Submit Model for Review
	global $user;
	$modelName = arg(1);
	$action = arg(2);
	$versionNumber = openabmma_parseVersionNumber(arg(3));
	
// FIXME: duplicated code
	if ($user->name != openabmma_getModelOwner ($modelName))
		return openabmma_formAccessError("Only model owners can change metadata details of any version in the model. You are not registered as the owner of this model.");

	$query = "SELECT run_conditions FROM openabm_model_version WHERE model_id=%d AND version_num=%d";
	$result = (array) db_fetch_object(db_query($query, openabmma_getModelId($modelName), $versionNumber));
	$run_conditions = $result['run_conditions'];

	$newVersion = $action == "add" ? 1 : 0;
	drupal_add_js(openabmma_get_js_path());

	$stepLinkText = "<table width='100%'><tr><td><a href=\"javascript:validateAndGo ('". $modelName ."', ". $versionNumber .", '". $action ."', 4, '". url(MODEL_DIRECTORY . $modelName ."/". $action ."/version" . $versionNumber . "/step01") . "');\">Step 1</a></td><td><a href=\"javascript:validateAndGo ('" . $modelName . "', " . $versionNumber . ", '" . $action . "', 4, '" . url (MODEL_DIRECTORY . $modelName . "/" . $action . "/version" . $versionNumber . "/step02") . "');\">Step 2</a></td><td><a href=\"javascript:validateAndGo ('" . $modelName . "', " . $versionNumber . ", '" . $action . "', 4, '" . url (MODEL_DIRECTORY . $modelName . "/" . $action . "/version" . $versionNumber . "/step03") . "');\">Step 3</a></td><td><a href=\"javascript:validateAndGo ('" . $modelName . "', " . $versionNumber . ", '" . $action . "', 4, '" . url (MODEL_DIRECTORY . $modelName . "/" . $action . "/version" . $versionNumber . "/step04") . "');\">Step 4</a></td></tr></table><p>&nbsp;</p>";

//	$form['#attributes'] = array('enctype' => "multipart/form-data", 'onsubmit' => 'return validate_version_step04 (' . $newVersion . ')');
	$form["details"] = array(
		"#type" => 'fieldset',
		"#collapsible" => FALSE,
		"#collapsed" => FALSE,
		"#title" => null,
		"#description" => $stepLinkText ."<h3>Additional information required for review</h3>",
	);

// Files

	$form ["details"]["version_conditions"] = array (
		"#type" => "textarea",
		"#title" => "Special conditions or other comments for running the code",
		"#default_value" => $form_state['values']["model_conditions"] == "" ? $run_conditions : $form_state['values']["model_conditions"],
		"#maxlength" => 255,
		"#description" => t("Optional notes on how to run this version of code"),
		"#required" => FALSE,
	);

	$form["details"]["version_inst"] = array(
		'#type' => 'checkboxes',
		'#title' => t('Submission requirements'),
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
	        "#value" => 1,
	        );

	$form ["details"]["submit"] = array (
	        "#type" => "submit",
	        "#value" => t("Submit"),
	        );

	$form ["details"]["cancel"] = array (
	        "#type" => "submit",
//	        "#attributes" => array ('onclick' => '$(\'#edit-submitAction\').val(0);'),
	        "#value" => t("Back"),
	        );

	return ($form);
}

function openabmma_addVersion04_submit ($form, &$form_state) {
	global $user;
	$modelName = arg(1);
	$action = arg(2);
	$versionNumber = openabmma_parseVersionNumber(arg(3));

	if ($user->name != openabmma_getModelOwner($modelName))
		return openabmma_accessError("Only model owners can change metadata details of any version in the model. You are not registered as the owner of this model.");

	if ($_POST ["op"] == "Back")
		$form_state['redirect'] = MODEL_DIRECTORY . $modelName . "/" . $action . "/version" . $versionNumber . "/reviewnote";
	
	if ($form_state['values']["version_inst"]['commented'] == '0' || $form_state['values']["version_inst"]['cleanup'] == '0' || $form_state['values']["version_inst"]['running'] == '0') {
		drupal_set_message("<b><font color='red'>Well-commented, cleaned up, and running code is required before you submit your model version for review!</font></b><br/>");
		return;
	}
/*	else
	{
		$directory = "files/models/" . $modelName . "/v" . $versionNumber . "/dataset";
		$fileCount = openabmma_getFileCount($directory);
		if ($fileCount == 0)
		{
		//	openabmma_cleantmp ($modelName . '/v' . $versionNumber . '/dataset');
			openabmma_uploadFile ($modelName . '/v' . $versionNumber . '/dataset', 'version_dataset');
		}

		$directory = "files/models/" . $modelName . "/v" . $versionNumber . "/other";
		$fileCount = openabmma_getFileCount ($directory);
		if ($fileCount == 0)
		{
		//	openabmma_cleantmp ($modelName . '/v' . $versionNumber . '/other');
			openabmma_uploadFile ($modelName . '/v' . $versionNumber . '/other', 'version_other');
		}
*/
		$query = "UPDATE openabm_model_version SET date_modified='%s', submittedReview=1, run_conditions='%s' WHERE model_id=%d AND version_num=%d";
		db_query($query, date("Y-m-d H:i:s"), $form_state['values']["version_conditions"], openabmma_getModelId($modelName), $versionNumber);
		$form_state['redirect'] = MODEL_DIRECTORY . $modelName . "/" . $action . "/version" . $versionNumber . "/complete";
//	}
}



function openabmma_deleteVersion() {
  global $user;
  
  $modelName = arg (1);
  $versionNumber = openabmma_parseVersionNumber(arg(3));

  if ($user->name != openabmma_getModelOwner($modelName)) {
    return "<br/>Only model owners can delete versions of any model. You are not registered as the owner of this model.";
  }

  if (!is_numeric($versionNumber)) {
    return "<br/><b><font color='red'>Invalid version number</font></b>";
  }

  $query = "DELETE FROM openabm_model_version WHERE model_id=%d AND version_num=%d";
  db_query($query, openabmma_getModelId($modelName), $versionNumber);

  openabmma_cleantmp($modelName . '/v' . $versionNumber, true);

  drupal_goto(MODEL_DIRECTORY . $modelName);
  return "";
}


function openabmma_versionMetadata() {
  global $user;
  $modelName = arg(1);
  if ($modelName == '') {
    return '';
  }

  $versionNumber = openabmma_parseVersionNumber (arg(2));
  $owner = openabmma_getModelOwner ($modelName);

  $query = "SELECT visible FROM openabm_model_version WHERE model_id=%d AND version_num=%d";
  $result = (array) db_fetch_object (db_query ($query, openabmma_getModelId ($modelName), $versionNumber));
  $visible = ($result ['visible'] == 1);
  
  /*
  if ($visible == "1") {
    $visible = TRUE;
  }
  else {
    $visible = FALSE;
  }

  if (!$visible) {
    if ($user->name != $owner && openabmma_inList ($user->name, openabmma_getModelMemberArray ($modelName)) == -1) {
      openabmma_accessError ('Only model members can view metadata for a model.');
      return '';
    }
  }
  */

  drupal_add_css(openabmma_get_css_path());

  $query = "SELECT description, model_language_id, other_language, language_version, os, framework, reference_text, examples, submittedReview, visible, date_modified, run_conditions, license_id from openabm_model_version WHERE model_id=%d AND version_num=%d";
  $result = (array) db_fetch_object(db_query($query, openabmma_getModelId($modelName), $versionNumber));
  
  $description = ($result['description'] == '' ? '(none)' : $result['description']);
  $model_language_id = $result['model_language_id'];
  $os = $result['os'];
  $framework = $result['framework'];
  $reference_text = $result['reference_text'];
  $examples = $result['examples'];
  $submittedReview = ($result['submittedReview'] == "1" ? "Yes" : "No");
  $visible = ($result['visible'] == "1" ? "Yes" : "No");
  $license_id = ($result['license_id']);
  $other_language = ($result['other_language']);
  $pLanguageVersion = ($result['language_version']);

  $query = "SELECT name from openabm_model_language WHERE id=%d";
  $result = (array) db_fetch_object(db_query($query, $model_language_id));
  $model_language_id = $result['name'];
  if ($model_language_id == "Other") {
    $model_language_id = $other_language;
  }

  if ($pLanguageVersion != "") {
    $model_language_id .= ", Version: " . $pLanguageVersion;
  }

  $query = "SELECT name from openabm_license WHERE id=%d";
  $result = (array) db_fetch_object (db_query ($query, $license_id));
  $license_id = $result ['name'];

  $memberArray = openabmma_getModelMemberArray ($modelName);
  if ($memberArray == null) {
    $members = "None";
  }
  else {
    $members = implode (', ', $memberArray);
  }

  $output = "<br/><p><table border='0' cellpadding='0' cellspacing='0' width='100%'>";
  $output .= "<tr class='openabmData'><td width='30%'><b>Version description:</b></td><td><i>" . $description . "</i></td></tr>";
  $output .= "<tr class='openabmData'><td><b>Owner:</b></td><td><i>" . openabmma_getModelOwner ($modelName) . "</i></td></tr>";
  $output .= "<tr class='openabmData'><td><b>Members:</b></td><td><i>" . $members . "</i></td></tr>";
  $output .= "<tr class='openabmData'><td><b>Visible to public:</b></td><td><i>" . $visible . "</i></td></tr>";
  $output .= "<tr class='openabmData'><td><b>Programming language:</b></td><td><i>" . $model_language_id . "</i></td></tr>";
  $output .= "<tr class='openabmData'><td><b>Operating System:</b></td><td><i>" . $os . "</i></td></tr>";
  $output .= "<tr class='openabmData'><td><b>Framework:</b></td><td><i>" . $framework . "</i></td></tr>";
  $output .= "<tr class='openabmData'><td><b>License:</b></td><td><i>" . $license_id . "</i></td></tr>";
  $output .= "<tr class='openabmData'><td><b>References to publications where the original model is described:</b></td><td><i>" . $reference_text . "</i></td></tr>";
  $output .= "<tr class='openabmData'><td><b>Examples:</b></td><td><i>" . $examples . "</i></td></tr>";
  $output .= "<tr class='openabmData'><td><b>Submitted for review:</b></td><td><i>" . $submittedReview . "</i></td></tr>";

  $files_root = "files/models/" . $modelName . "/v" . $versionNumber;
  
  if (openabmma_getFileCount($files_root . "/code") == 0) {
    $codeStr = "None";
  }
  else {
    $filename = openabmma_getFirstFile($files_root . "/code");
    $codeStr = l($filename, "download/" . $modelName . "/version" . $versionNumber . "/code");
//    $codeStr = "<a href='file://" . realpath ($files_root . "/code/" . $filename) . "'>" . $filename . "</a>";
  }

  $files_root = "files/models/" . $modelName . "/v" . $versionNumber;
  if (openabmma_getFileCount($files_root . "/doc") == 0) {
    $docStr = "None";
  }
  else {
    $filename = openabmma_getFirstFile($files_root . "/doc");
    $docStr = l($filename, "download/" . $modelName . "/version" . $versionNumber . "/doc");
//        $docStr = "<a href='file://" . realpath ($files_root . "/doc/" . $filename) . "'>" . $filename . "</a>";
  }

  $files_root = "files/models/" . $modelName . "/v" . $versionNumber;
  if (openabmma_getFileCount($files_root . "/sensitivity") == 0) {
    $sensitivityStr = "None";
  }
  else {
    $filename = openabmma_getFirstFile($files_root . "/sensitivity");
    $sensitivityStr = l($filename, "download/" . $modelName . "/version" . $versionNumber . "/sensitivity");
//        $sensitivityStr = "<a href='file://" . realpath ($files_root . "/sensitivity/" . $filename) . "'>" . $filename . "</a>";
  }

  $files_root = "files/models/" . $modelName . "/v" . $versionNumber;
  if (openabmma_getFileCount($files_root . "/dataset") == 0) {
    $datasetStr = "None";
  }
  else {
    $filename = openabmma_getFirstFile($files_root . "/dataset");
    $datasetStr = l($filename, "download/" . $modelName . "/version" . $versionNumber . "/dataset");
//        $datasetStr = "<a href='file://" . realpath ($files_root . "/dataset/" . $filename) . "'>" . $filename . "</a>";
  }

  $files_root = "files/models/" . $modelName . "/v" . $versionNumber;
  if (openabmma_getFileCount($files_root . "/other") == 0) {
    $additionalStr = "None";
  }
  else {
    $filename = openabmma_getFirstFile($files_root . "/other");
    $additionalStr = l($filename, "download/" . $modelName . "/version" . $versionNumber . "/other");
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

  if (strcasecmp($user->name, $owner) == 0) {
    $output .= "<p></p>" . l("To change your metadata settings, click here", array('attributes' => MODEL_DIRECTORY . $modelName . "/edit/version" . $versionNumber . "/step01"));
  }

  $output .= "<p></p>" . l("To go to model workspace, click here", array('attributes' => MODEL_DIRECTORY . $modelName));
  return $output;
}

function openabmma_getNameFromString($os) {
  return substr($os, 0, strpos($os, ", Version")-1);
}

function openabmma_getVersionFromString($os) {
  return (substr ($os, strpos ($os, ", Version: ")+strlen (",Version: ")+1));
}

function openabmma_optFiles_delete() {
  global $user;
  
  $modelName = arg(1);
  $action = arg(2);
  $versionNumber = openabmma_parseVersionNumber(arg(3));
  $target = arg(6);

  if ($user->name != openabmma_getModelOwner($modelName)) {
    return openabmma_formAccessError("Only model owners can change metadata details of any version in the model. You are not registered as the owner of this model.");
  }

  if ($target != "doc" && $target != "dataset" && $target != "other") {
    drupal_set_message("<font color='red'><b>Invalid file category</b></font>");
  }

  openabmma_cleantmp($modelName . "/v" . $versionNumber . "/" . $target);

  $gotoURL = MODEL_DIRECTORY . $modelName . "/" . $action . "/version" . $versionNumber . "/";
  switch ($target) {
    case "doc": 
      $gotoURL .= "step03"; 
      break;
      
    case "dataset":
    case "other": 
      $gotoURL .= "step04"; 
      break;
  }

  drupal_goto($gotoURL);
}

function openabmma_basicFiles_delete() {
  global $user;
  
  $modelName = arg(1);
  $action = arg(2);
  $versionNumber = openabmma_parseVersionNumber(arg(3));
  $target = arg(6);

  if ($user->name != openabmma_getModelOwner($modelName)) {
    return openabmma_formAccessError("Only model owners can change metadata details of any version in the model. You are not registered as the owner of this model.");
  }

  if ($target != "code" && $target != "sensitivity") {
    drupal_set_message("<font color='red'><b>Invalid file category</b></font>");
  }

  openabmma_cleantmp($modelName . "/v" . $versionNumber . "/" . $target);

  if ($target == "code") {
    drupal_goto(MODEL_DIRECTORY . $modelName . "/" . $action . "/version" . $versionNumber . "/step02");
  }
  else if ($target == "sensitivity") {
    drupal_goto(MODEL_DIRECTORY . $modelName . "/" . $action . "/version" . $versionNumber . "/step03");
  }
}
