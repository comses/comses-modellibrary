<?php
define('MODEL_DIRECTORY', 'model-archive/');

function openabmma_browseModels() {
  $query = "SELECT owner_uid, name, title FROM openabm_model ORDER BY title";
  $result = db_query($query);

  while ($model = db_fetch_object($result)) {
    $pId = $model->id;
    $modelName = $model->name;
    $pTitle = $model->title;
    $owner_uid = $model->owner_uid;

    $count++;
    $output .= l($pTitle . " [" . $modelName . "]", MODEL_DIRECTORY . $modelName) . "<br/><small>Owner: " . openabmma_getUserName($owner_uid) . "</small>&nbsp;<br/>";
  }

  if ($count) {
    $output = "<hr/>" . $count . " model(s) available.<br/>&nbsp;<br/>" . $output;
  }
  else {
    $output = "<hr/>No models available.";
  }
  return $output;
}

// **** Search Models ****

function openabmma_searchProjects() {
  $searchText = arg(2);
	$output = drupal_get_form(openabmma_getSearchText);

  if ($searchText != '') {
    $output .= "<br/>" . openabmma_doSearch($searchText);
  }
  
  return $output;
}

function openabmma_getSearchText() {
  $form["details"] = array(
    "#type" => 'fieldset',
    "#collapsible" => FALSE,
    "#collapsed" => FALSE,
    "#title" => NULL,
    "#description" => NULL,
  );

  $form ["details"]["text"] = array(
    "#type" => "textfield",
    "#title" => t("Search"),
    "#default_value" => NULL,
    "#description" => "",
    "#maxlength" => 255,
    "#required" => TRUE,
  );

  $form ["details"]["submit"] = array(
    "#type" => "submit",
    "#value" => t("Submit"),
  );

  return ($form);
}

function openabmma_getSearchText_submit($form, &$form_state) {
	$search = $form_state['values']['text'];
	$form_state['redirect'] = "models/search/". $search;
}

function openabmma_doSearch($searchText='') {
  if ($searchText == '') {
    return "";
  }

  $count = 0;

//  $searchText = strtolower ($searchText);

  $searchText = str_replace('\'', '\\\'', $searchText);  // escape string because not using %s later, in db_query
  $searchText = str_replace('"', '\\"', $searchText);  // escape string because not using %s later, in db_query
  $searchText = str_replace(',', ' ', $searchText);  // replace commas by spaces to assist searching

  $searchArray = explode(' ', $searchText);
  $keyCount = count($searchArray);

  if ($keyCount == 0) {
    drupal_set_message("<b><font color='red'>Please enter some text to search for.</font></b>");
    return;
  }

  for ($i=0; $i<$keyCount; $i++) {
    $searchArray[$i] = "'%" . $searchArray[$i] . "%'";
  }

  // slightly cryptic way of reducing lines of code to form a query using 'LIKE' for each keyword
  $searchTextKeyword = implode(" OR LOWER(keyword) LIKE ", $searchArray);
  $searchTextName = implode(" OR LOWER(name) LIKE ", $searchArray);
  $searchTextTitle = implode(" OR LOWER(title) LIKE ", $searchArray);

  // doing only plain keyword search. not checking title, description or other fields...
  $query = "SELECT DISTINCT (SELECT DISTINCT (id) as 'ID' FROM openabm_model WHERE (name) LIKE " . $searchTextName . " OR (title) LIKE " . $searchTextTitle . " UNION SELECT DISTINCT (model_id) as 'ID' FROM openabm_model_keywords WHERE (keyword) LIKE " . $searchTextKeyword . ") AS 'ID'";

  //  drupal_set_message ($query);
  // not using %s because the quotes should not be escaped
  $result1 = db_query($query);
  $count = 0;

  while ($proj = db_fetch_object($result1)) {
    if ($proj->ID == "") {
      continue;
    }

    $count++;

    $modelName = openabmma_getModelName($proj->ID);
    $owner = openabmma_getModelOwner($modelName);
    $output .= l(openabmma_getModelTitle($proj->ID) . " [${modelName}]", MODEL_DIRECTORY) . "<br/><small>Owner: ${owner}</small><br/>";
  }

  $output = $count . " result(s) matched your query.<br/>&nbsp;<br/>" . $output;
  return $output;
}

// **** Show My Models ****

function openabmma_showProjects() {
  global $user;

  $count = 0;
  $output .= '<br/><b>Your submitted models:</b><hr/><br/>';
  $query = "SELECT m.name, m.title, m.owner_uid, m.replicatedModel, m.replicators FROM openabm_model m WHERE m.owner_uid=%d";
  $result = db_query($query, $user->uid);

  while ($node = db_fetch_object($result)) {
    $count++;
    $output .= "<b>" . l($node->title . " (" . $node->name . ")", MODEL_DIRECTORY . $node->name) . "</b>&nbsp;";
    $output .= "&nbsp;<br/><small>" . $node->description . "<hr/>";
    $output .= "</small><br/>";
  }

  $output .= "You have submitted <b>". $count ."</b> model(s).<br/>&nbsp;<br/>";
  $count = 0;

  $output .= '<br/><b>Other models:</b><hr/><br/>';
  $query = "SELECT m.owner_uid, m.name, m.title, B.role 
    FROM openabm_model m INNER JOIN openabm_model_member B ON m.id=B.project_id
    WHERE B.user_id=%d";
  $result = db_query($query, $user->uid);

  while ($node = db_fetch_object($result)) {
    $count++;
    $owner = openabmma_getUserName($node->owner_uid);
    $output .= "<b>". l($node->title ." (". $node->name .")", MODEL_DIRECTORY . $node->name) ."</b>&nbsp;";

    if ($node->visible == 1) {
      $output .= "[Public]";
    }
    else {
      $output .= "[Private]";
    }

    $output .= "&nbsp;<br/><small>" . $node->description . "<br/>";
    $output .= "Owned by: <b>" . $owner . "</b></small><hr/><br/>";
  }

  $output .= "You are a member of <b>" . $count . "</b> model(s).<br/>&nbsp;<br/>";
  $output .= "<br/>". l("To submit a new model, please click here", "models/add");
  return $output;
}

function openabmma_form_cancel() {
	drupal_goto('models/');
}

// **** Add Model ****

function openabmma_addModel() {
	$output = drupal_get_form(openabmma_addModelForm);
	return $output;
}

function openabmma_addModelForm() {
	global $user;

	$action = arg(1);
	$modelName = arg(2);

	if ($action == "edit") {
	  $newModel = "0";
	}
	else {
	  $newModel = "1";
	}

	if ($newModel == "0") {
	  // FIXME: duplicated code
	  if ($user->name != openabmma_getModelOwner($modelName))
	  return openabmma_formAccessError("Only model owners can change metadata details of any model. You are not registered as the owner of this model.");
	}

	$replicated = FALSE;  // Value for new models
	if ($newModel == "0") {
	  $query = "SELECT title, replicatedModel, replicators, reference FROM openabm_model WHERE name='%s'";
	  $result = db_fetch_array(db_query($query, $modelName));

	  $replicated = $result['replicatedModel'];
	  $projTitle = $result['title'];
	  if ($replicated == "1") {
	    $replicated = TRUE;
	  }

	  $replicators = $result['replicators'];
	  $refurl = $result['reference'];
	  $desc = $result['description'];

	  $keywordList = '';
	  $query = "SELECT keyword FROM openabm_model_keywords WHERE model_id=%d";
	  $result = db_query($query, openabmma_getModelId($modelName));

	  while ($element = db_fetch_object($result)) {
	    $keywordList .= $element->keyword .", ";
	  }
	  $keywordList = substr($keywordList, 0, strlen($keywordList)-2);
	}

// FIXME: dangerous, may not work for all concurrent requests!
	$result = db_fetch_array(db_query("SELECT max(id) FROM openabm_model"));
	$id = $result['max(id)'] + 1;

	$form["details"] = array(
		"#type" => 'fieldset',
		"#collapsible" => FALSE,
		"#collapsed" => FALSE,
		"#title" => NULL,
		"#description" => NULL,
	);

	$form["details"]["model_ownerId"] = array(
			"#type" => "item",
			"#title" => t("Owner"),
			"#value" => $user->name,
			"#description" => NULL,
	);

	$form["details"]["newModel"] = array(
	  "#type" => "hidden",
	  "#value" => $newModel,
	);

	if ($newModel == 1) {
	  $form["details"]["model_name"] = array(
	    "#type" => "textfield",
	    "#title" => t("Model name"),
			"#default_value" => "",
	    "#description" => t("This case insensitive name will be used
	        as part of the URL to access your model, so a shorter name is
	        better.  Only alphanumeric characters and the underscore
	        character are allowed."),
	    "#maxlength" => 255,
	  );
	}
	else {
	  $form["details"]["model_name"] = array(
	    "#type" => "item",
	    "#title" => t("Model name"),
	    "#description" => t("This case insensitive name will be used
	        as part of the URL to access your model, so a shorter name is
	        better.  Only alphanumeric characters and the underscore
	        character are allowed."),
	    "#value" => arg(2),
	    "#maxlength" => 255,
	  );
	}

	$form["details"]["model_title"] = array(
	  "#type" => "textfield",
	  "#title" => t("Model title"),
	  "#default_value" => ($edit["model_title"] == "" ? $projTitle : $edit["model_title"]),
	  "#description" => t("A more descriptive title for your model - spaces and non alphanumeric characters are allowed, within reason."),
	  "#maxlength" => 255,
	);

// FIXME: what is the point of this conditional?
	if ($replicated) {
	  $form["details"]["model_replicated"] = array(
	    '#type' => 'checkboxes',
	    "#attributes" => array('checked' => 'checked'),
	    '#title' => t("Replicated model"),
	    '#options' => array('replica' => t('Check this box if this is a replicated model instead of an original model')),
	    '#description' => t('If the model you are submitting is a replication of an existing model, put a check mark here. If this is a new, original, model, leave this box blank.'),
	  );
	}
	else {
	  $form["details"]["model_replicated"] = array(
	    '#type' => 'checkboxes',
	    '#title' => t("Replicated model"),
	    '#options' => array('replica' => t('Check this box if this a replicated model instead of an original model')),
	    '#description' => t('If the model you are submitting is a replication of an existing model, put a check mark here. If this is a new, original, model, leave this box blank.'),
	  );
	}
	
	$form["details"]["model_repl"] = array(
	  "#type" => "textarea",
	  "#title" => t("List of authors of the original model"),
	  "#default_value" => ($edit["model_repl"] == "" ? $replicators : $edit["model_repl"]),
	  "#description" => t("If this model is a replicated model, enter the names of original authors here."),
	);

	$form["details"]["model_refurl"] = array(
	  "#type" => "textarea",
	  "#title" => t("Reference URL or citation"),
	  "#default_value" => ($edit["model_refurl"] == "" ? $refurl : $edit["model_refurl"]),
	  "#description" => t("If this model is a replicated model, enter a URL or citation for the original model."),
	);

	$form["details"]["keywords"] = array(
	  "#type" => "textfield",
	  "#title" => "Keywords",
	  "#description" => t("Keywords related to your project (separate by commas)"),
	  "#default_value" => ($edit["keywords"] == "" ? $keywordList : $edit["keywords"]),
	  "#maxlength" => 255,
	  "#required" => false,
	);

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

function openabmma_addModelForm_validate($form, &$form_state) {
  global $user;
	
  if ($form_state['values']['newModel']) {
    $newModel = TRUE;
    $modelName = $form_state['values']['model_name'];
  }
  else {
    $newModel = FALSE;
    $modelName = arg(2);
    // ensure that owner is the same as the user for metadata editing.
    $ownerName = openabmma_getModelOwner($modelName);

    // FIXME: duplicated code
    if ($user->name != $ownerName) {
      form_set_error($form_state['values']['form_id'], t("Only model owners can change metadata details of any model. You are not registered as the owner of this model."));
    }
  }

  // validate model name
  if ($modelName == '') {
    form_set_error($form_state['values']['form_id'], t("<b><font color='#DD0000'>Model name is a required field</font></b>"));
  }
  else if (! preg_match('/^[\w.-]+$/', $modelName)) {
    form_set_error ($form_state['values']['form_id'], t("The model name you entered, <b><i><font color='#DD0000'>${modelName}</font></i></b> contains invalid characters. Please re-enter the model name only using alphanumeric characters and the underscore character."));
  }

  if ($form_state['values']['model_title'] == '') {
    form_set_error($form_state['values']['form_id'], t("Please enter a model title."));
  }

  $replicated = $form_state['values']["model_replicated"]["replica"];
  if ($replicated) {
    if ($form_state['values']["model_repl"] == "" || $form_state['values']["model_refurl"] == "") {
      form_set_error($form_state['values']['form_id'], t("Replicated models must enter proper references to the original authors and citations.  Please enter a list of authors and a reference URL or citation."));
    }
  }
  if ($newModel) {
    $modelId = openabmma_getModelId($modelName);
    if ($modelId != -1) {
      form_set_error($form_state['values']['form_id'], t("Another project with the same name (<b><font color='#DD0000'>" . $modelName . "</font></b>) exists. Please choose a different name."));
    }
  }
}

function openabmma_addModelForm_submit($form, &$form_state) {
	global $user;
  $action = arg(1);
	
  if ($form_state['values']['newModel'] == "1") {
    $newModel = TRUE;
    $modelName = $form_state['values']["model_name"];
  }
  else {
    $newModel = FALSE;
    $modelName = arg(2);
  }
  $modelName = strtolower($modelName);

  $replicated = ($form_state['values']["model_replicated"]["replica"] ? 1 : 0);
  $modelId = openabmma_getModelId($modelName);

	$table = "openabm_model";
	$record = new stdClass();

  $record->owner_uid = $user->uid;
	$record->name = $modelName;
	$record->title = $form_state['values']['model_title'];
	$record->replicators = $form_state['values']['model_repl'];
	$record->replicatedModel = $replicated;
	$record->reference = $form_state['values']['model_refurl'];

  if ($newModel == 1) {
		drupal_write_record($table, $record);
  }
  else {
		drupal_write_record($table, $record, 'name');
  }

	openabm_addKeywords($modelId, $form_state['values']['keywords']);

	$form_state['redirect'] = MODEL_DIRECTORY .$modelName;
}


function openabmma_openProject($modelName='') {
  global $user;
  
  if ($modelName == '') {
    drupal_goto("models");
  }

  $owner = openabmma_getModelOwner($modelName);

  $output = "<h3>Model Metadata</h3>";

  /*
  if ($user->name != $owner && openabmma_inList($user->name, openabmma_getModelMemberArray($modelName)) == -1) {
  openabmma_accessError('Only model members can view metadata for a model.');
  return '';
  }
  */
  drupal_add_css(openabmma_get_css_path());

  $query = "SELECT owner_uid, name, title, replicatedModel, replicators, reference from openabm_model WHERE name='%s'";
  $result = (array) db_fetch_object(db_query($query, $modelName));
  $owner_uid = $result['owner_uid'];
  $name = $result['name'];
  $title = $result['title'];
  $replicated = ($result['replicatedModel'] == "1" ? "Yes" : "No");
  $replicators = $result['replicators'];
  $reference_url = $result['reference'];

  // FIXME: hacky, use split/join instead and an array.
  $keywordList = '';
  $query = "SELECT keyword FROM openabm_model_keywords WHERE model_id=%d";
  $result = db_query($query, openabmma_getModelId($modelName));
  
  while ($element = db_fetch_object($result)) {
    $keywordList .= $element->keyword . ", ";
  }
  $keywordList = substr($keywordList, 0, strlen($keywordList)-2);

  $header = array('Name/URL', 'Owner', 'Title', 'Replicated?');
  $data = array(array($name, openabmma_getModelOwner($name), $title, $replicated));
  if ($replicated == "Yes") {
    array_push($header, 'Other Authors', 'Reference / URL', 'Keywords');
    array_push($data[0], $replicators, $reference_url, $keywordList);
  }

  $output .= "<p><table border='0' cellpadding='0' cellspacing='0' width='100%'>";
  $output .= "<tr class='openabmData'><td width='30%'><b>Model name:</b></td><td><i>" . $name . "</i></td></tr>";
  $output .= "<tr class='openabmData'><td><b>Model owner:</b></td><td><i>" . openabmma_getModelOwner($name) . "</i></td></tr>";
  $output .= "<tr class='openabmData'><td><b>Model title:</b></td><td><i>" . $title . "</i></td></tr>";
  $output .= "<tr class='openabmData'><td><b>Replicated model:</b></td><td><i>" . $replicated . "</i></td></tr>";
  
  if ($replicated == "Yes") {
    $output .= "<tr class='openabmData'><td><b>Authors of the original model:</b></td><td><i>" . $replicators . "</i></td></tr>";
    $output .= "<tr class='openabmData'><td><b>Reference URL:</b></td><td><i>" . $reference_url . "</i></td></tr>";
  }

  $output .= "<tr class='openabmData'><td><b>Model keywords:</b></td><td><i>" . $keywordList . "</i></td></tr>";
  $output .= "</table>";

  if ($owner == $user->name) {
    $output .= '(' . l('Change model metadata', 'models/edit/' . $modelName);
    $output .= ' | ' . l('Manage members of this model', MODEL_DIRECTORY . $modelName . '/members') . ')';
  }

  $output .= openabmma_getFormattedVersionList($modelName);

  return $output;
}

function openabmma_manageMembers($name='') {
  global $user;
  
  if ($name == '') {
    drupal_goto("models");
  }

  $owner = openabmma_getModelOwner($name);
  $output = "<br/><u>Owner</u>: ". $owner;

  $members = openabmma_showModelMembers($name);

  if ($members != "") {
    $output .= "<br/>&nbsp;<br/><u>Members</u>: ". $members;
  }
  else {
    $output .= "<br/>&nbsp;<br/>Currently there are no members in this model.";
  }

  if ($user->name == $owner) {
    $output .= "<br/>&nbsp;<br/>". drupal_get_form(openabmma_addMember);
  }

  $output .= "<p></p>To go to the model workspace, click ". l("here", MODEL_DIRECTORY . $name);

  return $output;
}

function openabmma_showModelMembers($name='') {
  global $user;
  
  if ($name == '') {
    return "";
  }

  // get identifier (number) of the project
  $result = db_fetch_array(db_query("SELECT id FROM openabm_model WHERE name='%s'", $name));
  $model_id = $result["id"];

  $query = "SELECT user_id, role FROM openabm_model_member WHERE project_id=%d";
  $result = db_query($query, $model_id);
  while ($users = db_fetch_object($result)) {
    $output .= "<br/>". openabmma_getUserName($users->user_id) ."&nbsp;<small>[". openabmma_getRoleName($users->role) ."]";
    if ($user->name == openabmma_getModelOwner($name)) {
      $output .= " - <a href=\"javascript:if(confirm('Are you sure you want to remove this user from your model?')) window.location.replace('". url(MODEL_DIRECTORY . $name ."/members/delete/". $users->user_id) ."');\">Remove this user from my model</a></small>";
    }
  }

  return $output;
}

function openabmma_addMember() {
  global $user;

  $projName = arg(1);
  if ($projName == "") {
    return null;
  }

  if ($user->name != openabmma_getModelOwner($projName)) {
    $form["details"] = array(
      "#type" => 'fieldset',
      "#collapsible" => FALSE,
      "#collapsed" => FALSE,
      "#title" => NULL,
      "#description" => "",
    );

    $form ["details"]["msg"] = array(
      '#type' => "item",
      '#value' => "This feature is only available to model owners.",
    );

    return $form;
  }

  $form["details"] = array(
    "#type" => 'fieldset',
    "#collapsible" => FALSE,
    "#collapsed" => FALSE,
    "#title" => NULL,
    "#description" => t("You can add a new member for this model here."),
  );

  $form["details"]["projName"] = array(
    "#type" => "hidden",
    "#value" => $projName,
  );

  $form["details"]["name"] = array(
    "#type" => "textfield",
    "#title" => t("User name"),
    '#autocomplete_path' => 'user/autocomplete',
    "#default_value" => NULL,
    "#description" => "",
    "#maxlength" => 210,
  );

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

function openabmma_addMember_submit($form, &$form_state) {
  global $user;
  $modelName = arg(1);
  $username = $form_state['values']['name'];
  $projName = arg(1);

  if ($user->name != openabmma_getModelOwner($projName)) {
    openabmma_accessError('Only model members can add members for a model.');
    return;
  }

  if ($username == "") {
    drupal_set_message("<b><font color='red'>Please enter the name of user whom you want to add into your project</font></b>");
    return;
  }

  if ($username == $user->name) {
    drupal_set_message("You already have owner privileges. You cannot add yourself as a Developer for this model.");
    return;
  }

  $userid = openabmma_getUserId($username);
  if ($userid == -1) {
    drupal_set_message("Invalid user name specified.");
    return;
  }

  $projName = $form_state['values']["projName"];
  $model_id = openabmma_getModelId($projName);    // get identifier (number) of the project

  $query = "SELECT id FROM openabm_role WHERE name='Developer'";
  $result = db_fetch_array(db_query($query));
  $roleId = $result['id'];

  $query = "SELECT id FROM openabm_model_member WHERE user_id=%d AND project_id=%d AND role=%d";
  $result = db_fetch_array(db_query($query, $userid, $model_id, $roleId));
  if ($result ['id'] != "") {
    drupal_set_message("<b><font color='red'>This user has already been added as a member for this project.</font></b>");
    return;
  }

  $table = "openabm_model_member";
	$record = new stdClass();

  $record->project_id = $model_id;
	$record->user_id = $userid;
	$record->role = NULL;

	drupal_write_record($table, $record);
}

function openabmma_deleteMember($name, $uid) {
  global $user;
  
  // check if user is owner of this model
  $owner = openabmma_getModelOwner($name);
  if ($owner != $user->name) {
    return "Only the model owner can perform this function.";
  }

  $query = "DELETE FROM openabm_model_member WHERE project_id=%d AND user_id=%d";

  $projectId = openabmma_getModelId($name);
  if ($projectId == -1) {
    return "Invalid project name";
  }

  db_query($query, $projectId, $uid);
  drupal_goto(MODEL_DIRECTORY . $name . "/members");
  return "";
}

function openabmma_getModelId($name) {
  if ($name == '') {
    return -1;
  }

  $result = db_fetch_array(db_query("SELECT id FROM openabm_model WHERE name='%s'", $name));
  $modelId = $result['id'];
  return (($modelId) ? $modelId : -1);
}

function openabmma_getModelName($id) {
  if ($id == '' || $id < 0) {
    return '';
  }

  $query = "SELECT name FROM openabm_model WHERE id=%d";
  $result = db_fetch_array(db_query($query, $id));
  return $result['name'];
}

function openabmma_getModelTitle($id) {
  if ($id == '' || $id < 0) {
    return '';
  }

  $query = "SELECT title FROM openabm_model WHERE id=%d";
  $result = db_fetch_array(db_query($query, $id));
  return $result['title'];
}

function openabmma_getModelOwner($name='') {
  if ($name == '') {
    return "";
  }

  $result = db_fetch_array(db_query("SELECT name FROM users WHERE uid = (SELECT owner_uid FROM openabm_model WHERE name='%s')", $name));
  $owner = $result['name'];
  return $owner;
}

function openabmma_getModelMemberArray($name='') {
  if ($name == '') {
    return "";
  }

  $query = "SELECT user_id FROM openabm_model_member WHERE project_id=%d";
  $result = db_query($query, openabmma_getModelId($name));
  $i = 0;
  
  while ($users = db_fetch_object($result)) {
    $userArr [$i] = openabmma_getUserName($users->user_id);
    $i++;
  }

  if($i == 0) {
    return null;
  }
  
  return $userArr;
}

function openabmma_showMetaData($modelName='') {
  global $user;
  
  if ($modelName == '') {
    return '';
  }

  $owner = openabmma_getModelOwner($modelName);
  if ($owner == "") {
    drupal_goto ("models");
  }
  
  /*
  if ($user->name != $owner && openabmma_inList($user->name, openabmma_getModelMemberArray($modelName)) == -1) {
    openabmma_accessError('Only model members can view metadata for a model.');
    return '';
  }
  */

  drupal_add_css(openabmma_get_css_path());

  $query = "SELECT owner_uid, name, title, replicatedModel, replicators, reference from openabm_model WHERE name='%s'";
  $result = (array) db_fetch_object(db_query($query, $modelName));
  $owner_uid = $result['owner_uid'];
  $name = $result['name'];
  $title = $result['title'];
  $replicated = ($result['replicatedModel'] == "1" ? "Yes" : "No");
  $replicators = $result['replicators'];
  $reference_url = $result['reference'];

  $keywordList = '';
  $query = "SELECT keyword FROM openabm_model_keywords WHERE model_id=%d";
  $result = db_query($query, openabmma_getModelId($modelName));
  
  while ($element = db_fetch_object ($result)) {
    $keywordList .= $element->keyword . ", ";
  }
  $keywordList = substr($keywordList, 0, strlen($keywordList)-2);

  $output = "<br/><p><table border='0' cellpadding='0' cellspacing='0' width='100%'>";
  $output .= "<tr class='openabmData'><td width='30%'><b>Model name:</b></td><td><i>" . $name . "</i></td></tr>";
  $output .= "<tr class='openabmData'><td><b>Model owner:</b></td><td><i>" . openabmma_getModelOwner($name) . "</i></td></tr>";
  $output .= "<tr class='openabmData'><td><b>Model title:</b></td><td><i>" . $title . "</i></td></tr>";
  $output .= "<tr class='openabmData'><td><b>Replicated model:</b></td><td><i>" . $replicated . "</i></td></tr>";
  
  if ($replicated == "Yes") {
    $output .= "<tr class='openabmData'><td><b>List of authors of the original model (for replicated models only):</b></td><td><i>" . $replicators . "</i></td></tr>";
    $output .= "<tr class='openabmData'><td><b>Reference URL:</b></td><td><i>" . $reference_url . "</i></td></tr>";
  }

  $output .= "<tr class='openabmData'><td><b>Model keywords:</b></td><td><i>" . $keywordList . "</i></td></tr>";
  $output .= "</table>";

  if ($user->uid && (strcasecmp($user->name, $owner) == 0)) {
    $output .= "<p></p>" . l("To change your metadata settings, click here", array('attributes' => "models/edit/" . $modelName));
  }

  $output .= "<p></p>" . l("To go to model workspace, click here", array('attributes' => MODEL_DIRECTORY . $modelName));
  return $output;
}

function openabm_addKeywords($modelId, $inputKeywordList) {
  // FIXME: notify them that we are replacing any non-alphanumeric/space
  // characters with nothing?
  $keywordList = preg_replace('/[^\w\s,]+/', '', $inputKeywordList);
  $keywords = explode(',', $keywordList);

  $query = "DELETE FROM openabm_model_keywords WHERE model_id=%d";
  db_query($query, $modelId);

  $query = "INSERT INTO openabm_model_keywords (model_id, keyword) VALUES (%d, '%s')";
  foreach ($keywords as $keyword) {
    $keyword = trim($keyword);
    
    if ($keyword) {
      db_query($query, $modelId, $keyword);
    }
  }
}


function openabmma_getFormattedVersionList ($modelName) {
  global $user;
  
  if ($modelName == '') {
    return "";
  }

  $owner = openabmma_getModelOwner($modelName);
  drupal_add_css(openabmma_get_css_path());
  $versionCount = 0;
  $output  = "<p>&nbsp;</p><table border='0' cellpadding='1' cellspacing='0' width='100%'>";
  $output .= "<tr class='openabmData'><td class='openabmCol' width='10%'><b>Version Num</b></td><td class='openabmCol' width='50%'><b>Description</b></td><td class='openabmCol' width='5%'><b>Download</b></td><td class='openabmCol' width='5%'><b>Public</b></td><td class='openabmCol' width='5%'><b>Submitted</b></td><td class='openabmCol' width='20%'><b>Date last modified</b></td><td>&nbsp;</td><td>&nbsp;</td></tr>";

  $query = "SELECT visible, version_num, description, date_modified, submittedReview FROM openabm_model_version WHERE model_id = %d ORDER BY version_num ASC";
  $result = db_query ($query, openabmma_getModelId ($modelName));
  
  while ($item = db_fetch_object($result)) {
    $versionCount++;
    $submitted = ($item->submittedReview == "1" ? "Yes" : "No");
    $visible = ($item->visible == "1" ? "Yes" : "No");
    $desc = substr($item->description, 0, 49);

    if ($desc == "") {
      $desc = "(none)";
    }

    $dlStr = "";
    $files_root = "files/models/" . $modelName . "/v" . $item->version_num;
    if (openabmma_getFileCount($files_root . "/code") != 0) {
      $filename = openabmma_getFirstFile($files_root . "/code");
      $dlStr = l("Code", "download/" . $modelName . "/version" . $item->version_num . "/code") . "<br/>";
    }

    $files_root = "files/models/" . $modelName . "/v" . $item->version_num;
    if (openabmma_getFileCount($files_root . "/doc") != 0) {
      $filename = openabmma_getFirstFile($files_root . "/doc");
      $dlStr .= l("Documentation", "download/" . $modelName . "/version" . $item->version_num . "/doc") . "<br/>";
    }

    $files_root = "files/models/" . $modelName . "/v" . $item->version_num;
    if (openabmma_getFileCount($files_root . "/sensitivity") != 0) {
      $filename = openabmma_getFirstFile ($files_root . "/sensitivity");
      $dlStr .= l("Sensitivity", "download/" . $modelName . "/version" . $item->version_num . "/sensitivity") . "<br/>";
    }

    $files_root = "files/models/" . $modelName . "/v" . $item->version_num;
    if (openabmma_getFileCount($files_root . "/dataset") != 0) {
      $filename = openabmma_getFirstFile($files_root . "/dataset");
      $dlStr .= l("Dataset", "download/" . $modelName . "/version" . $item->version_num . "/dataset") . "<br/>";
    }

    $files_root = "files/models/" . $modelName . "/v" . $item->version_num;
    if (openabmma_getFileCount($files_root . "/other") != 0) {
      $filename = openabmma_getFirstFile ($files_root . "/other");
      $dlStr .= l("Other", "download/" . $modelName . "/version" . $item->version_num . "/other") . "<br/>";
    }
    
    if (strlen($item->description) > 50) {
      $desc .= "...";
    }
    $output .= "<tr class='openabmData'><td class='openabmCol'>" . $item->version_num . "</td><td class='openabmCol'>" . l($desc, MODEL_DIRECTORY. $modelName . "/version" . $item->version_num . "/metadata") . "</td><td class='openabmCol'>" . $dlStr . "</td><td class='openabmCol'>" . $visible . "</td><td class='openabmCol'>" . $submitted . "</td><td class='openabmCol'>". $item->date_modified . "</td><td>";

    if ($user->name == $owner) {
      $output .= l("edit", MODEL_DIRECTORY . $modelName . "/edit/version" . $item->version_num . "/step01");
    }
    else {
      $output .= "&nbsp;";
    }

    $output .= "</td><td>";

    if ($user->name == $owner) {
      $output .= "<a href=\"javascript:if(confirm('Are you sure you want to delete this version?\\nOnce deleted, no recovery is possible.')) window.location.replace('" . url(MODEL_DIRECTORY . $modelName . "/delete/version" . $item->version_num) . "');\">delete</a>";
    }
    else {
      $output .= "&nbsp;";
    }
    $output .= "</td></tr>";
  }

  $output .= "</table>";

  $finalOutput = "<br/>&nbsp;<br/><u>${versionCount} version(s) available:</u><br/>" . l("Upload a new version", MODEL_DIRECTORY . $modelName . "/add/version") . "<br/>";
  
  if ($versionCount != 0) {
    $finalOutput .= $output;
  }

  return $finalOutput;
}
