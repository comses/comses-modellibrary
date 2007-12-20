<?php

function openabmma_showMetaData ($pName='') {
    global $user;
    if ($pName == '')
        return '';

    $owner = openabmma_getModelOwner ($pName);
    if ($owner == "")
        drupal_goto ("models");
/*
    $query = "SELECT visible FROM openabm_model_version WHERE model_id=%d AND version_num=%d";
    $result = (array) db_fetch_object (db_query ($query, openabmma_getModelId ($pName), $versionNumber));
    $visible = $result ['visible'];
    if ($visible == "1")
        $visible = TRUE;
    else
        $visible = FALSE;

	drupal_set_message ($visible);
*/
    if ($user->name != $owner && openabmma_inList ($user->name, openabmma_getModelMemberArray ($pName)) == -1)
    {
        openabmma_accessError ('Only model members can view metadata for a model.');
        return '';
    }

    drupal_add_css (openabmma_get_css_path ());

    $query = "SELECT owner_uid, name, title, replicatedModel, replicators, reference from openabm_model WHERE name='%s'";
    $result = (array) db_fetch_object (db_query ($query, $pName));
    $owner_uid = $result ['owner_uid'];
    $name = $result ['name'];
    $title = $result ['title'];
    $replicated = $result ['replicatedModel'] == "1" ? "Yes" : "No";
    $replicators = $result ['replicators'];
    $reference_url = $result ['reference'];

    $keywordList = '';
    $query = "SELECT keyword FROM openabm_model_keywords WHERE model_id=%d";
    $result = db_query ($query, openabmma_getModelId ($pName));
    while ($element = db_fetch_object ($result))
        $keywordList .= $element->keyword . ", ";
    $keywordList = substr ($keywordList, 0, strlen($keywordList)-2);

    $output = "<br/><p><table border='0' cellpadding='0' cellspacing='0' width='100%'>";
    $output .= "<tr class='openabmData'><td width='30%'><b>Model name:</b></td><td><i>" . $name . "</i></td></tr>";
    $output .= "<tr class='openabmData'><td><b>Model owner:</b></td><td><i>" . openabmma_getModelOwner ($name) . "</i></td></tr>";
    $output .= "<tr class='openabmData'><td><b>Model title:</b></td><td><i>" . $title . "</i></td></tr>";
    $output .= "<tr class='openabmData'><td><b>Replicated model:</b></td><td><i>" . $replicated . "</i></td></tr>";
    if ($replicated == "Yes")
    {
        $output .= "<tr class='openabmData'><td><b>Replicators:</b></td><td><i>" . $replicators . "</i></td></tr>";
        $output .= "<tr class='openabmData'><td><b>Reference URL:</b></td><td><i>" . $reference_url . "</i></td></tr>";
    }

    $output .= "<tr class='openabmData'><td><b>Model keywords:</b></td><td><i>" . $keywordList . "</i></td></tr>";

    $output .= "</table>";

	if ($user->name == $owner)
	    $output .= "<p></p>" . l ("To change your metadata settings, click here", "models/edit/" . $pName);

	$output .= "<p></p>" . l ("To go to model workspace, click here", "mymodels/" . $pName);
    return $output;
}

function openabmma_addModel_submit ($form_id, $edit)
{
    $action = arg(1);

    global $user;
    if ($edit ["newModel"] == "1") {
        $newModel = TRUE;
        $pName = $edit ["model_name"];
    }
    else {
        $newModel = FALSE;
        $pName = arg(2);
    }

    if ($_POST ["op"] == "Cancel")
        if ($action == "add")
            drupal_goto ("models");
        else if ($action == "edit")
        {
            $pName = arg(2);
            drupal_goto ("mymodels/" . $pName);
        }

    if (!$newModel) {
        $ownerName = openabmma_getModelOwner( $pName );
        // FIXME: duplicated code
        if ($user->name != $ownerName)
            return openabmma_formAccessError ("Only model owners can change metadata details of any model. You are not registered as the owner of this model.");
    }

    if ($pName == "" || $pName == null)
    {
        drupal_set_message ("<b><font color='red'>Model name is a required field</font></b>");
        return;
    }

    $pCount = strlen ($pName);
    for ($i=0; $i<$pCount; $i++)
    {
        if ($pName [$i] == ' ')
        {
            $errorString = "Model name cannot contain spaces. Please re-enter the correct model name.";
            drupal_set_message ("<b><font color='red'>" . $errorString . "</font></b>");
            return;
        }
    }

    $replicated = $edit ["model_replicated"]["replica"];
    if ($replicated != '0')
        $replicated = '1';

    if ($newModel == TRUE) {
        if (openabmma_getModelId ($pName) != -1 && openabmma_getModelId ($pName) != '') {
            drupal_set_message ("<b><font color='red'>Another project with the same name exists. Please choose a different name</font></b>");
        }
        else {
            if ($replicated == '1') {
                if ($edit ["model_repl"] == "" || $edit ["model_refurl"] == "") {
                    drupal_set_message ("<b><font color='red'>Since this project is a replicated one, you need to enter information about the replicators and the reference URL.</font></b>");
                    return;
                }
            }

            $query = "INSERT INTO openabm_model (owner_uid, name, title, replicators, replicatedModel, reference) VALUES (%d, '%s', '%s', '%s', %d, '%s')";
            db_query($query, $user->uid, $pName, $edit ["model_title"], $edit ["model_repl"], $replicated, $edit ["model_refurl"]);

	$keywordList = $edit ["keywords"];
	$replaceList = "!@#$%^&*()+-|\\[]{}:;'\"<>,/?~` ";
	$replaceLen = strlen ($replaceList);
	for ($i=0; $i<$replaceLen; $i = $i+1)
            $keywordList = str_replace ($replaceList [$i], ",", $keywordList);

	$keywords = explode (",", $keywordList);

	$pId = openabmma_getModelId ($pName);
	$query = "DELETE FROM openabm_model_keywords WHERE model_id=%d";
	db_query ($query, $pId);

	$query = "INSERT INTO openabm_model_keywords (model_id, keyword) VALUES (%d, '%s')";
	for ($i=0; $i<count($keywords); $i++)
	{
		if ($keywords [$i] == '')
			continue;
		db_query ($query, $pId, trim ($keywords [$i]));
	}

	    drupal_goto("mymodels/" . $pName);
        }
    }
    else
    {
        if ($replicated == '1')
        {
            if ($edit ["model_repl"] == "" || $edit ["model_refurl"] == "")
            {
		drupal_set_message ("|" . $edit ["model_repl"] . "|" . $edit ["model_refurl"] . "|");
                drupal_set_message ("<b><font color='red'>Since this project is a replicated one, you need to enter information about the replicators and the reference URL.</font></b>");
                return;
            }
        }

        $query = "UPDATE openabm_model SET title='%s', replicatedModel=%d, replicators='%s', reference='%s' WHERE name='%s'";
        db_query ($query, $edit ["model_title"], $replicated, $edit ["model_repl"], $edit ["model_refurl"], $pName);

	$keywordList = $edit ["keywords"];
	$replaceList = "!@#$%^&*()+-|\\[]{}:;'\"<>,/?~` ";
	$replaceLen = strlen ($replaceList);
	for ($i=0; $i<$replaceLen; $i = $i+1)
            $keywordList = str_replace ($replaceList [$i], ",", $keywordList);

	$keywords = explode (",", $keywordList);

	$pId = openabmma_getModelId ($pName);
	$query = "DELETE FROM openabm_model_keywords WHERE model_id=%d";
	db_query ($query, $pId);

	$query = "INSERT INTO openabm_model_keywords (model_id, keyword) VALUES (%d, '%s')";
	for ($i=0; $i<count($keywords); $i++)
	{
		if ($keywords [$i] == '')
			continue;
		db_query ($query, $pId, trim ($keywords [$i]));
	}


        drupal_goto("mymodels/" . $pName);
    }
}

function openabmma_addModel ()
{
	global $user;

	$action = arg(1);
	$pName = arg(2);

	if ($action == "edit")
		$newModel = "0";
	else
		$newModel = "1";

	if ($newModel == "0")
	{
            // FIXME: duplicated code
            if ($user->name != openabmma_getModelOwner ($pName))
                return openabmma_formAccessError ("Only model owners can change metadata details of any model. You are not registered as the owner of this model.");
	}

	$replicated = FALSE;	// Value for new models
	if ($newModel == "0")
	{
		$query = "SELECT title, replicatedModel, replicators, reference FROM openabm_model WHERE name='%s'";
		$result = (array) db_fetch_object (db_query ($query, $pName));
		$replicated = $result ['replicatedModel'];
		$projTitle = $result ['title'];
		if ($replicated == "1")
			$replicated = TRUE;
		else
			$replicated = FALSE;

		$replicators = $result ['replicators'];
		$refurl = $result ['reference'];

		$desc = $result ['description'];

	    $keywordList = '';
	    $query = "SELECT keyword FROM openabm_model_keywords WHERE model_id=%d";
	    $result = db_query ($query, openabmma_getModelId ($pName));
	    while ($element = db_fetch_object ($result))
		$keywordList .= $element->keyword . ", ";
	    $keywordList = substr ($keywordList, 0, strlen($keywordList)-2);
	}

	$result = (array) db_fetch_object (db_query ("SELECT max(id) FROM openabm_model"));
	$id = $result['max(id)'] + 1;

	$form["details"] = array(
		"#type" => 'fieldset',
		"#collapsible" => FALSE,
		"#collapsed" => FALSE,
		"#title" => null,
		"#description" => null,
	);

	$form ["details"]["model_ownerId"] = array (
		"#type" => "item",
		"#title" => t("Owner ID:"),
		"#value" => "#" . $user->uid . " - " . $user->name,
		"#description" => null,
	);

	$form ["details"]["newModel"] = array (
		"#type" => "hidden",
		"#value" => $newModel
	);

	if ($newModel == "1")
	{
		$form ["details"]["model_name"] = array (
			"#type" => "textfield",
			"#title" => t("Model name (should not contain spaces):"),
			"#default_value" => $edit ["model_name"],
			"#description" => null,
	//		"#required" => true,		// Commented because clicking Cancel validates this field too!
			"#maxlength" => 210
		);
	}
	else
	{
		$form ["details"]["model_name"] = array (
			"#type" => "item",
			"#title" => t("Model name:"),
			"#description" => null,
			"#value" => arg(2),
	//		"#required" => true,		// Commented because clicking Cancel validates this field too!
			"#maxlength" => 210
		);
	}

	$form ["details"]["model_title"] = array (
		"#type" => "textfield",
		"#title" => t("Model title (human-friendly name):"),
		"#default_value" => $edit ["model_title"] == "" ? $projTitle : $edit ["model_title"],
		"#description" => null,
//		"#required" => true,		// Commented because clicking Cancel validates this field too!
		"#maxlength" => 210
	);

	if ($replicated)
	{
		$form["details"]["model_replicated"] = array(
		'#type' => 'checkboxes',
		"#attributes" => array ('checked' => 'checked'),
		'#title' => t("Model replication:"),
		'#options' => array(
		'replica' => t('Check this box is this a replicated model instead of an original model'),
			),
		'#description' => t('If the model you are submitting is your own implementation a replica of an existing model of somebody else, put a check mark here. If this is an original model, leave this box blank.'),
		);
	}
	else
	{
		$form["details"]["model_replicated"] = array(
		'#type' => 'checkboxes',
		'#title' => t("Model replication:"),
		'#options' => array(
		'replica' => t('Check this box is this a replicated model instead of an original model'),
			),
		'#description' => t('If the model you are submitting is your own implementation a replica of an existing model of somebody else, put a check mark here. If this is an original model, leave this box blank.'),
		);
	}

	$form ["details"]["model_repl"] = array (
		"#type" => "textfield",
		"#title" => t("Replicators (only for replicated models):"),
		"#default_value" => $edit ["model_repl"] == "" ? $replicators : $edit ["model_repl"],
		"#description" => t("If this model is a replicated model, enter the name of replicators here."),
		"#maxlength" => 210
	);

	$form ["details"]["model_refurl"] = array (
		"#type" => "textfield",
		"#title" => t("References (only for replicated models):"),
		"#default_value" => $edit ["model_refurl"] == "" ? $refurl : $edit ["model_refurl"],
		"#description" => t("If this model is a replicated model, enter references, if any."),
		"#maxlength" => 255
	);

	$form ["details"]["keywords"] = array (
		"#type" => "textfield",
		"#title" => "Keywords:",
		"#description" => t("Special words related to your project (separated by commas)"),
		"#default_value" => $edit ["keywords"] == "" ? $keywordList : $edit ["keywords"],
		"#maxlength" => 210,
		"#required" => false
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

function openabmma_searchProjects ()
{
	$searchText = arg(2);
	$output = drupal_get_form (openabmma_getSearchText);

	if ($searchText != '')
		$output .= "<br/>" . openabmma_doSearch ($searchText);
	return $output;
}

function openabmma_doSearch ($searchText='')
{
	if ($searchText == '')
		return "";

	$count=0;

//	$searchText = strtolower ($searchText);

	$searchText = str_replace ('\'', '\\\'', $searchText);	// escape string because not using %s later, in db_query
	$searchText = str_replace ('"', '\\"', $searchText);	// escape string because not using %s later, in db_query
	$searchText = str_replace (',', ' ', $searchText);	// replace commas by spaces to assist searching

	$searchArray = explode (' ', $searchText);
	$keyCount = count ($searchArray);

	if ($keyCount == 0)
	{
		drupal_set_message ("<b><font color='red'>Please enter some text to search for.</font></b>");
		return;
	}

	for ($i=0; $i<$keyCount; $i++)
		$searchArray [$i] = "'%" . $searchArray [$i] . "%'";

	// slightly cryptic way of reducing lines of code to form a query using 'LIKE' for each keyword
	$searchTextKeyword = implode (" OR LOWER(keyword) LIKE ", $searchArray);
	$searchTextName = implode (" OR LOWER(name) LIKE ", $searchArray);
	$searchTextTitle = implode (" OR LOWER(title) LIKE ", $searchArray);

	// doing only plain keyword search. not checking title, description or other fields...
	$query = "SELECT DISTINCT (SELECT DISTINCT (id) as 'ID' FROM openabm_model WHERE (name) LIKE " . $searchTextName . " OR (title) LIKE " . $searchTextTitle . " UNION SELECT DISTINCT (model_id) as 'ID' FROM openabm_model_keywords WHERE (keyword) LIKE " . $searchTextKeyword . ") AS 'ID'";

//	drupal_set_message ($query);
	// not using %s because the quotes should not be escaped
	$result1 = db_query ($query);
	$count = 0;

	while ($proj = db_fetch_object ($result1))
	{
		if ($proj->ID == "")
			continue;

		$count++;

		$pName = openabmma_getModelName ($proj->ID);
		$owner = openabmma_getModelOwner ($pName);
		$output .= l (openabmma_getModelTitle ($proj->ID) . " [" . $pName . "]", "mymodels/" . $pName) . "<br/><small>Owner: " . $owner . "</small><br/>";
	}

	$output = $count . " result(s) matched your query.<br/>&nbsp;<br/>" . $output;
	return $output;
}

function openabmma_getModelName ($id)
{
	if ($id == '' || $id < 0)
		return '';

	$query = "SELECT name FROM openabm_model WHERE id=%d";
	$result = (array) db_fetch_object (db_query ($query, $id));
	return $result ['name'];
}

function openabmma_getModelTitle ($id)
{
	if ($id == '' || $id < 0)
		return '';

	$query = "SELECT title FROM openabm_model WHERE id=%d";
	$result = (array) db_fetch_object (db_query ($query, $id));
	return $result ['title'];
}

function openabmma_getSearchText_submit ($form_id, $edit)
{
	drupal_goto ("models/search/" . $edit ["text"]);
}

function openabmma_getSearchText ()
{
	$form["details"] = array(
		"#type" => 'fieldset',
		"#collapsible" => FALSE,
		"#collapsed" => FALSE,
		"#title" => null,
		"#description" => NULL,
	);

	$form ["details"]["text"] = array (
		"#type" => "textfield",
		"#title" => t("Search for:"),
		"#default_value" => null,
		"#description" => "",
		"#maxlength" => 210,
		"#required" => true
	);

	$form ["details"]["submit"] = array (
		"#type" => "submit",
		"#value" => t("Submit"),
		"#submit" => TRUE
	);

	return ($form);
}

function openabmma_deleteMember ($name, $uid)
{
	global $user;
	// check if user is owner of this model
	$owner = openabmma_getModelOwner ($name);
	if ($owner != $user->name)
		return "Only the model owner can perform this function.";

	$query = "DELETE FROM openabm_model_member WHERE project_id=%d AND user_id=%d";

	$projectId = openabmma_getModelId ($name);

	if ($projectId == -1)
		return "Invalid project name";

	db_query ($query, $projectId, $uid);
	drupal_goto ("mymodels/" . $name . "/members");
	return "";
}

function openabmma_manageMembers ($name='')
{
	global $user;
	if ($name == '')
		drupal_goto ("models");


	$owner = openabmma_getModelOwner ($name);
#	$query = "SELECT visible FROM openabm_model WHERE name='%s'";
#	$result = (array) db_fetch_object (db_query ($query, $name));
#	if (($result ["visible"] == 0 || $result ["visible"] == "0") && $user->name != $owner /* && in (model_member_list, $user->name) */)
#		return "This is a privately managed model.";

	$output = "<br/><u>Owner</u>: " . $owner;

	$members = openabmma_showModelMembers ($name);

	if ($members != "")	$output .= "<br/>&nbsp;<br/><u>Members</u>: " . $members;
	else			$output .= "<br/>&nbsp;<br/>Currently there are no members in this model.";

	if ($user->name == $owner)
		$output .= "<br/>&nbsp;<br/>" . drupal_get_form (openabmma_addMember);

	$output .= "<p></p>To go to the model workspace, click " . l ("here", "mymodels/" . $name);

	return $output;
}

function openabmma_addMember ()
{
	global $user;

	$projName = arg (1);
	if ($projName == "")
		return null;

	if ($user->name != openabmma_getModelOwner ($projName))
	{
		$form["details"] = array(
			"#type" => 'fieldset',
			"#collapsible" => FALSE,
			"#collapsed" => FALSE,
			"#title" => null,
			"#description" => ""
		);

		$form ["details"]["msg"] = array (
			'#type' => "item",
			'#value' => "This feature is only available to model owners."
		);

		return $form;
	}

	$form["details"] = array(
		"#type" => 'fieldset',
		"#collapsible" => FALSE,
		"#collapsed" => FALSE,
		"#title" => null,
		"#description" => t("You can add a new member for this model here."),
	);

	$form ["details"]["projName"] = array (
		"#type" => "hidden",
		"#value" => $projName
	);

	$form ["details"]["name"] = array (
		"#type" => "textfield",
		"#title" => t("User name:"),
		'#autocomplete_path' => 'user/autocomplete',
		"#default_value" => null,
		"#description" => "",
		"#maxlength" => 210
	);

/*	$roleTypes = array ();
	$result = db_query ("SELECT id, name FROM openabm_role");
	while ($node = db_fetch_object ($result))
		$roleTypes [$node->id] = $node->name;

	$form ["details"]["role"] = array (
		"#type" => "select",
		"#title" => t("Role:"),
		"#default_value" => "1",
		"#options" => $roleTypes,
		"#description" => null
	);
*/
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

function openabmma_addMember_submit ($form_id, $edit)
{
	global $user;
	$pName = arg(1);

	if ($_POST ["op"] == "Back")
		drupal_goto ("mymodels/" . $pName);

	$username = $edit ["name"];
	$projName = arg(1);

	if ($user->name != openabmma_getModelOwner ($projName))
	{
	        openabmma_accessError ('Only model members can add memebers for a model.');
		return;
	}

	if ($username == "")
	{
		drupal_set_message ("<b><font color='red'>Please enter the name of user whom you want to add into your project</font></b>");
		return;
	}

	if ($username == $user->name)
	{
		drupal_set_message ("You already have owner privileges. You cannot add yourself as a Developer for this model.");
		return;
	}

	$userid = openabmma_getUserId ($username);
	if ($userid == -1)
	{
		drupal_set_message ("Invalid user name specified.");
		return;
	}

	$projName = $edit ["projName"];
	// get identifier (number) of the project
	$model_id = openabmma_getModelId ($projName);

	$query = "SELECT id FROM openabm_role WHERE name='Developer'";
	$result = (array) db_fetch_object (db_query ($query));
	$roleId = $result ['id'];

	$query = "SELECT id FROM openabm_model_member WHERE user_id=%d AND project_id=%d AND role=%d";
	$result = (array) db_fetch_object (db_query ($query, $userid, $model_id, $roleId));
	if ($result ['id'] != "")
	{
		drupal_set_message ("<b><font color='red'>This user has already been added as a member for this project.</font></b>");
		return;
	}

	$query = "INSERT INTO openabm_model_member (project_id, user_id, role) VALUES (%d, %d, $roleId)";
	db_query ($query, $model_id, $userid);
//	drupal_goto ("roles");
}

function openabmma_getModelId ($name)
{
	if ($name == '')
		return -1;

	$result = (array) db_fetch_object (db_query ("SELECT id FROM openabm_model WHERE name='%s'", $name));
	$pId = $result ['id'];
	return $pId;
}

function openabmma_getModelMemberArray ($name='')
{
	if ($name == '')
		return "";

	$query = "SELECT user_id FROM openabm_model_member WHERE project_id=%d";
	$result = db_query ($query, openabmma_getModelId ($name));
	$i = 0;
	while ($users = db_fetch_object ($result))
	{
		$userArr [$i] = openabmma_getUserName ($users->user_id);
		$i++;
	}

	if($i == 0)
		return null;

	return $userArr;
}

function openabmma_showModelMembers ($name='')
{
	global $user;
	if ($name == '')
		return "";

	// get identifier (number) of the project
	$result = (array) db_fetch_object (db_query ("SELECT id FROM openabm_model WHERE name='%s'", $name));
	$model_id = $result ["id"];

	$query = "SELECT user_id, role FROM openabm_model_member WHERE project_id=%d";
	$result = db_query ($query, $model_id);
	while ($users = db_fetch_object ($result))
	{
		$output .= "<br/>" . openabmma_getUserName ($users->user_id) . "&nbsp;<small>[" . openabmma_getRoleName ($users->role) . "]";
		if ($user->name == openabmma_getModelOwner ($name))
			 $output .= " - " . "<a href=\"javascript:if(confirm('Are you sure you want to remove this user from your model?')) window.location.replace('" . url("mymodels/" . $name . "/members/delete/" . $users->user_id) . "');\">Remove this user from my model</a></small>";
	}

	return $output;
}

function openabmma_getModelOwner ($name='')
{
	if ($name == '')
		return "";

	$result = (array) db_fetch_object (db_query ("SELECT name FROM users WHERE uid = (SELECT owner_uid FROM openabm_model WHERE name='%s')", $name));
	$owner = $result['name'];
	return $owner;
}

function openabmma_openProject ($pName='')
{
	global $user;
	if ($pName == '')
		drupal_goto ("models");

	$owner = openabmma_getModelOwner ($pName);

	$output = "<br/>You can now add or change information on the model project, or add versions of the model.<br/>&nbsp;<br/><u>Metadata information:</u><br/>";

/*
    if ($user->name != $owner && openabmma_inList ($user->name, openabmma_getModelMemberArray ($pName)) == -1)
    {
        openabmma_accessError ('Only model members can view metadata for a model.');
        return '';
    }
*/
    drupal_add_css (openabmma_get_css_path ());

    $query = "SELECT owner_uid, name, title, replicatedModel, replicators, reference from openabm_model WHERE name='%s'";
    $result = (array) db_fetch_object (db_query ($query, $pName));
    $owner_uid = $result ['owner_uid'];
    $name = $result ['name'];
    $title = $result ['title'];
    $replicated = $result ['replicatedModel'] == "1" ? "Yes" : "No";
    $replicators = $result ['replicators'];
    $reference_url = $result ['reference'];

    $keywordList = '';
    $query = "SELECT keyword FROM openabm_model_keywords WHERE model_id=%d";
    $result = db_query ($query, openabmma_getModelId ($pName));
    while ($element = db_fetch_object ($result))
        $keywordList .= $element->keyword . ", ";
    $keywordList = substr ($keywordList, 0, strlen($keywordList)-2);

    $output .= "<p><table border='0' cellpadding='0' cellspacing='0' width='100%'>";
    $output .= "<tr class='openabmData'><td width='30%'><b>Model name:</b></td><td><i>" . $name . "</i></td></tr>";
    $output .= "<tr class='openabmData'><td><b>Model owner:</b></td><td><i>" . openabmma_getModelOwner ($name) . "</i></td></tr>";
    $output .= "<tr class='openabmData'><td><b>Model title:</b></td><td><i>" . $title . "</i></td></tr>";
    $output .= "<tr class='openabmData'><td><b>Replicated model:</b></td><td><i>" . $replicated . "</i></td></tr>";
    if ($replicated == "Yes")
    {
        $output .= "<tr class='openabmData'><td><b>Replicators:</b></td><td><i>" . $replicators . "</i></td></tr>";
        $output .= "<tr class='openabmData'><td><b>Reference URL:</b></td><td><i>" . $reference_url . "</i></td></tr>";
    }

    $output .= "<tr class='openabmData'><td><b>Model keywords:</b></td><td><i>" . $keywordList . "</i></td></tr>";
    $output .= "</table>";

	if ($owner == $user->name)
	{
		$output .= "<br/>" . l ("To change model metadata, click here", "models/edit/" . $pName);
		$output .= "<br/>" . l ("Manage members in this model", "mymodels/" . $pName . "/members");
	}

	$output .= openabmma_getFormattedVersionList ($pName);

	return $output;
}

function openabmma_getFormattedVersionList ($pName)
{
	global $user;
	if ($pName == '')
		return "";

	$owner = openabmma_getModelOwner ($pName);
	drupal_add_css (openabmma_get_css_path ());
	$versionCount = 0;
	$output  = "<p>&nbsp;</p><table border='0' cellpadding='1' cellspacing='0' width='100%'>";
	$output .= "<tr class='openabmData'><td class='openabmCol' width='10%'><b>Version Num</b></td><td class='openabmCol' width='50%'><b>Description</b></td><td class='openabmCol' width='5%'><b>Download</b></td><td class='openabmCol' width='5%'><b>Public</b></td><td class='openabmCol' width='5%'><b>Submitted</b></td><td class='openabmCol' width='20%'><b>Date last modified</b></td><td>&nbsp;</td><td>&nbsp;</td></tr>";

	$query = "SELECT visible, version_num, description, date_modified, submittedReview FROM openabm_model_version WHERE model_id = %d ORDER BY version_num ASC";
	$result = db_query ($query, openabmma_getModelId ($pName));
	while ($item = db_fetch_object ($result))
	{
		$versionCount++;
		$submitted = $item->submittedReview == "1" ? "Yes" : "No";
		$visible = $item->visible == "1" ? "Yes" : "No";
		$desc = substr ($item->description, 0, 49);

		if ($desc == "")
			$desc = "(none)";

		$dlStr = "";
	    $files_root = "files/models/" . $pName . "/v" . $item->version_num;
	    if (openabmma_getFileCount ($files_root . "/code") != 0)
	    {
		$filename = openabmma_getFirstFile ($files_root . "/code");
		$dlStr = l ("Code", "download/" . $pName . "/version" . $item->version_num . "/code") . "<br/>";
	    }

	    $files_root = "files/models/" . $pName . "/v" . $item->version_num;
	    if (openabmma_getFileCount ($files_root . "/doc") != 0)
	    {
		$filename = openabmma_getFirstFile ($files_root . "/doc");
		$dlStr .= l ("Documentation", "download/" . $pName . "/version" . $item->version_num . "/doc") . "<br/>";
	    }

	    $files_root = "files/models/" . $pName . "/v" . $item->version_num;
	    if (openabmma_getFileCount ($files_root . "/sensitivity") != 0)
	    {
		$filename = openabmma_getFirstFile ($files_root . "/sensitivity");
		$dlStr .= l ("Sensitivity", "download/" . $pName . "/version" . $item->version_num . "/sensitivity") . "<br/>";
	    }

	    $files_root = "files/models/" . $pName . "/v" . $item->version_num;
	    if (openabmma_getFileCount ($files_root . "/dataset") != 0)
	    {
		$filename = openabmma_getFirstFile ($files_root . "/dataset");
		$dlStr .= l ("Dataset", "download/" . $pName . "/version" . $item->version_num . "/dataset") . "<br/>";
	    }

	    $files_root = "files/models/" . $pName . "/v" . $item->version_num;
	    if (openabmma_getFileCount ($files_root . "/other") != 0)
	    {
		$filename = openabmma_getFirstFile ($files_root . "/other");
		$dlStr .= l ("Other", "download/" . $pName . "/version" . $item->version_num . "/other") . "<br/>";
	    }
		if (strlen ($item->description) > 50)
			$desc .= "...";
		$output .= "<tr class='openabmData'><td class='openabmCol'>" . $item->version_num . "</td><td class='openabmCol'>" . l ($desc, "mymodels/". $pName . "/version" . $item->version_num . "/metadata") . "</td><td class='openabmCol'>" . $dlStr . "</td><td class='openabmCol'>" . $visible . "</td><td class='openabmCol'>" . $submitted . "</td><td class='openabmCol'>". $item->date_modified . "</td><td>";

		if ($user->name == $owner)
			$output .= l ("edit", "mymodels/" . $pName . "/edit/version" . $item->version_num . "/step01");
		else
			$output .= "&nbsp;";

		$output .= "</td><td>";

		if ($user->name == $owner)
			$output .= "<a href=\"javascript:if(confirm('Are you sure you want to delete this version?\\nOnce deleted, no recovery is possible.')) window.location.replace('" . url("mymodels/" . $pName . "/delete/version" . $item->version_num) . "');\">delete</a>";
		else
			$output .= "&nbsp;";

		$output .= "</td></tr>";
	}

	$output .= "</table>";

	$finalOutput = "<br/>&nbsp;<br/><u>Versions (Currently " . $versionCount . " version(s)):</u><br/>" . l ("Upload a new version", "mymodels/" . $pName . "/add/version") . "<br/>";
	if ($versionCount != 0)
		$finalOutput .= $output;

	return $finalOutput;
}


