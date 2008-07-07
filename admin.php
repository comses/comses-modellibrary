<?php

function openabmma_deletePLanguages ($id='')
{
	if ($id != "")
	{
		$query = "DELETE FROM openabm_model_language WHERE id=%d";
		db_query ($query, $id);
	}

	drupal_goto ("config/planguages");	
	return '';
}

function openabmma_showPLanguages ()
{
	$count = 0;
	$query = "SELECT id, name FROM openabm_model_language";
	$result = db_query ($query);
	while ($node = db_fetch_object ($result))
	{
		$count++;
		$output .= "<br/><b>" . $node->name . "</b>&nbsp;" . "<a href=\"javascript:if(confirm('Are you sure you want to delete this language from the list?')) window.location.replace('" . url("config/planguages/delete/" . $node->id) . "');\"><small>[delete this]</small></a>";
	}

	$output = "<br/>Total " . $count . " language(s).<br/>" . $output . "<br/>&nbsp;" . drupal_get_form (openabmma_add_pLanguage);
	return $output;
}

function openabmma_add_pLanguage_submit ($form_id, $edit)
{
	$query = "INSERT INTO openabm_model_language (name) VALUES ('%s')";
	db_query ($query, $edit ['name']);
	drupal_goto ("config/planguages");
}

function openabmma_add_pLanguage ()
{
	$form["details"] = array(
		"#type" => 'fieldset',
		"#collapsible" => FALSE,
		"#collapsed" => FALSE,
		"#title" => null,
		"#description" => t("You can add a new programming languages to the list here."),
	);

	$form ["details"]["name"] = array (
		"#type" => "textfield",
		"#title" => t("Language name"),
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

function openabmma_deleteFramework ($id='')
{
	if ($id != "")
	{
		$query = "DELETE FROM openabm_framework WHERE id=%d";
		db_query ($query, $id);
	}

	drupal_goto ("config/frameworks");
	return '';
}

function openabmma_showFrameworks ()
{
	$count = 0;
	$query = "SELECT id, name FROM openabm_framework";
	$result = db_query ($query);
	while ($node = db_fetch_object ($result))
	{
		$count++;
		$output .= "<br/><b>" . $node->name . "</b>&nbsp;" . "<a href=\"javascript:if(confirm('Are you sure you want to delete this framework from the list?')) window.location.replace('" . url("config/frameworks/delete/" . $node->id) . "');\"><small>[delete this]</small></a>";
	}

	$output = "<br/>Total " . $count . " framework(s).<br/>" . $output . "<br/>&nbsp;" . drupal_get_form (openabmma_add_framework);
	return $output;
}

function openabmma_add_framework_submit ($form_id, $edit)
{
	$query = "INSERT INTO openabm_framework (name) VALUES ('%s')";
	db_query ($query, $edit ['name']);
	drupal_goto ("config/frameworks");
}

function openabmma_add_framework ()
{
	$form["details"] = array(
		"#type" => 'fieldset',
		"#collapsible" => FALSE,
		"#collapsed" => FALSE,
		"#title" => null,
		"#description" => t("You can add a new framework to the list here."),
	);

	$form ["details"]["name"] = array (
		"#type" => "textfield",
		"#title" => t("Framework name"),
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

function openabmma_showRoles ()
{
	$count = 0;
	$query = "SELECT id, name FROM openabm_role";
	$result = db_query ($query);
	while ($node = db_fetch_object ($result))
	{
		$count++;
		$output .= "<br/><b>" . $node->name . "</b>&nbsp;" . "<a href=\"javascript:if(confirm('Are you sure you want to delete this role?')) window.location.replace('" . url("config/roles/delete/" . $node->id) . "');\"><small>[delete this]</small></a>";
// l ("[delete]", "roles/delete/" . $node->id);
	}

	$output = "<br/>Total " . $count . " role(s).<br/>" . $output . "<br/>&nbsp;" . drupal_get_form (openabmma_add_role);
	return $output;
}

function openabmma_add_role_submit ($form_id, $edit)
{
	$query = "INSERT INTO openabm_role (name) VALUES ('%s')";
	db_query ($query, $edit ['name']);
	drupal_goto ("config/roles");
}

function openabmma_add_role ()
{
	$form["details"] = array(
		"#type" => 'fieldset',
		"#collapsible" => FALSE,
		"#collapsed" => FALSE,
		"#title" => null,
		"#description" => t("You can add a new member role here."),
	);

	$form ["details"]["name"] = array (
		"#type" => "textfield",
		"#title" => t("Role name:"),
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

function openabmma_showLicenses ()
{
	$count = 0;
	$output = '';
	$query = "SELECT id, name, license_text, url FROM openabm_license";
	$result = db_query ($query);
	while ($node = db_fetch_object ($result))
	{
            $count++;
            $output .= "<b>" . $node->name . "</b>&nbsp;<small>" . "<a href=\"javascript:if(confirm('Are you sure you want to delete this license?')) window.location.replace('" . url("config/licenses/delete/" . $node->id) . "');\"><small>[Delete this]</small></a></small><br/>";
            //l ("[delete this]", "licenses/delete/" . $node->id) . "<br/>" . l($node->url, $node->url) . "</small><br/>&nbsp;<br/>";
	}
        return "There are currently " . $count . " license(s) available. <br/>&nbsp;<br/>" . $output . "<hr/><br/>" . drupal_get_form(openabmma_addLicense_form);
}

function openabmma_deleteLicense ($id='')
{
	if ($id != "")
	{
		$query = "DELETE FROM openabm_license WHERE id=%d";
		db_query ($query, $id);
	}

	drupal_goto ("config/licenses");	
	return '';
}

function openabmma_deleteRole ($id='')
{
	if ($id != "")
	{
		$query = "DELETE FROM openabm_role WHERE id=%d";
		db_query ($query, $id);
	}

	drupal_goto ("config/roles");
	return '';
}

function openabmma_addLicense_form_submit ($formid, $edit) {
    $query = "INSERT INTO openabm_license (name, license_text, url) VALUES ('%s', '%s', '%s')";
    db_query ($query, $edit['name'], $edit['license_text'], $edit ['url']);
}

function openabmma_addLicense_form ()
{
	$form["details"] = array(
		"#type" => 'fieldset',
		"#collapsible" => FALSE,
		"#collapsed" => FALSE,
		"#title" => null,
		"#description" => t("You can add a new license here."),
	);

	$form ["details"]["name"] = array (
		"#type" => "textfield",
		"#title" => t("License Name"),
		"#default_value" => null,
		"#description" => t("Name of the license"),
		"#maxlength" => 210,
		"#required" => true
	);
	$form ["details"]["license_text"] = array (
		"#type" => "textarea",
		"#title" => t("License Text"),
		"#default_value" => null,
		"#description" => t("Full license text"),
		"#required" => true
	);

	$form ["details"]["url"] = array (
		"#type" => "textfield",
		"#title" => t("URL"),
		"#default_value" => null,
		"#description" => t("Web address of license document"),
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
