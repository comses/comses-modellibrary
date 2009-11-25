<?php
function openabmma_showLicenses() {
	$count = 0;
	$output = '';
	$query = "SELECT id, name, license_text, url FROM openabm_license";
	$result = db_query($query);
	while ($node = db_fetch_object($result)) {
            $count++;
            $output .= "<b>". $node->name ."</b>&nbsp;<small><a href=\"javascript:if(confirm('Are you sure you want to delete this license?')) window.location.replace('". url("config/licenses/delete/". $node->id) ."');\"><small>[Delete this]</small></a></small><br/>";
	}
        return "There are currently ". $count ." license(s) available. <br/>&nbsp;<br/>". $output ."<hr/><br/>". drupal_get_form(openabmma_addLicenseForm);
}

function openabmma_deleteLicense($id='') {
	if ($id != "")
	{
		$query = "DELETE FROM openabm_license WHERE id=%d";
		db_query ($query, $id);
	}

	drupal_goto("config/licenses");	
	return '';
}

function openabmma_addLicenseForm() {
	$form["details"] = array(
		"#type" => 'fieldset',
		"#collapsible" => FALSE,
		"#collapsed" => FALSE,
		"#title" => NULL,
		"#description" => t("You can add a new license here."),
	);

	$form ["details"]["name"] = array(
		"#type" => "textfield",
		"#title" => t("License Name"),
		"#default_value" => NULL,
		"#description" => t("Name of the license"),
		"#maxlength" => 210,
		"#required" => TRUE,
	);
	
	$form ["details"]["license_text"] = array(
		"#type" => "textarea",
		"#title" => t("License Text"),
		"#default_value" => NULL,
		"#description" => t("Full license text"),
		"#required" => TRUE,
	);

	$form ["details"]["url"] = array(
		"#type" => "textfield",
		"#title" => t("URL"),
		"#default_value" => NULL,
		"#description" => t("Web address of license document"),
		"#maxlength" => 210,
		"#required" => TRUE,
	);

	$form ["details"]["submit"] = array(
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

function openabmma_addLicenseForm_submit($form, &$form_state) {
	$table = "openabm_license";
	$record = new stdClass();

	$record->name = $form_state['values']['name'];
	$record->license_text = $form_state['values']['license_text'];
	$record->url = $form_state['values']['url'];
	
	drupal_write_record($table, $record);
	$form_state['redirect'] = "config/licenses";
}


function openabmma_showRoles() {
  $count = 0;
  
  $query = "SELECT id, name FROM openabm_role";
  $result = db_query($query);
  
  while ($node = db_fetch_object($result)) {
    $count++;
    $output .= "<br/><b>". $node->name ."</b>&nbsp; <a href=\"javascript:if(confirm('Are you sure you want to delete this role?')) window.location.replace('". url("config/roles/delete/". $node->id) ."');\"><small>[delete this]</small></a>";
  }

  $output = "<br/>Total ". $count ." role(s).<br/>". $output ."<br/>&nbsp;". drupal_get_form(openabmma_addRole);
  return $output;
}

function openabmma_addRole() {
  $form["details"] = array(
    "#type" => 'fieldset',
    "#collapsible" => FALSE,
    "#collapsed" => FALSE,
    "#title" => NULL,
    "#description" => t("You can add a new member role here."),
  );

  $form["details"]["name"] = array(
    "#type" => "textfield",
    "#title" => t("Role name"),
    "#default_value" => NULL,
    "#description" => "",
    "#maxlength" => 210,
    "#required" => TRUE,
  );

  $form["details"]["submit"] = array(
    "#type" => "submit",
    "#value" => t("Submit"),
  );

  return ($form);
}

function openabmma_addRole_submit($form, &$form_state) {
	$table = "openabm_role";
	$record = new stdClass();
	$record->name = $form_state['values']['name'];
	
	drupal_write_record($table, $record);
	$form_state['redirect'] = "config/roles";
}

function openabmma_deleteRole($id='') {
  if ($id != "") {
    $query = "DELETE FROM openabm_role WHERE id=%d";
    db_query($query, $id);
  }

  drupal_goto("config/roles");
  return '';
}


function openabmma_showPLanguages() {
  $count = 0;
  
  $query = "SELECT id, name FROM openabm_model_language";
  $result = db_query($query);
  
  while ($node = db_fetch_object($result)) {
    $count++;
    $output .= "<br/><b>". $node->name ."</b>&nbsp; <a href=\"javascript:if(confirm('Are you sure you want to delete this language from the list?')) window.location.replace('". url("config/planguages/delete/". $node->id) ."');\"><small>[delete this]</small></a>";
  }

  $output = "<br/>Total ". $count ." language(s).<br/>". $output ."<br/>&nbsp;". drupal_get_form(openabmma_addPLanguage);
  return $output;
}

function openabmma_addPLanguage() {
  $form["details"] = array(
    "#type" => 'fieldset',
    "#collapsible" => FALSE,
    "#collapsed" => FALSE,
    "#title" => NULL,
    "#description" => t("You can add a new programming languages to the list here."),
  );

  $form["details"]["name"] = array(
    "#type" => "textfield",
    "#title" => t("Language name"),
    "#default_value" => NULL,
    "#description" => "",
    "#maxlength" => 210,
    "#required" => TRUE,
  );

  $form["details"]["submit"] = array(
    "#type" => "submit",
    "#value" => t("Submit"),
  );

  return ($form);
}

function openabmma_addPLanguage_submit($form, &$form_state) {
	$table = "openabm_model_language";
	$record = new stdClass();
	$record->name = $form_state['values']['name'];
	
	drupal_write_record($table, $record);
	$form_state['redirect'] = "config/planguages";
}

function openabmma_deletePLanguages($id='') {
  if ($id != "") {
    $query = "DELETE FROM openabm_model_language WHERE id=%d";
    db_query ($query, $id);
  }

  drupal_goto("config/planguages"); 
  return '';
}


function openabmma_showFrameworks() {
  $count = 0;
  
  $query = "SELECT id, name FROM openabm_framework";
  $result = db_query($query);
  
  while ($node = db_fetch_object($result)) {
    $count++;
    $output .= "<br/><b>". $node->name ."</b>&nbsp; <a href=\"javascript:if(confirm('Are you sure you want to delete this framework from the list?')) window.location.replace('". url("config/frameworks/delete/". $node->id) ."');\"><small>[delete this]</small></a>";
  }

  $output = "<br/>Total ". $count ." framework(s).<br/>". $output ."<br/>&nbsp;". drupal_get_form(openabmma_addFramework);
  return $output;
}

function openabmma_addFramework() {
  $form["details"] = array(
    "#type" => 'fieldset',
    "#collapsible" => FALSE,
    "#collapsed" => FALSE,
    "#title" => NULL,
    "#description" => t("You can add a new framework to the list here."),
  );

  $form["details"]["name"] = array(
    "#type" => "textfield",
    "#title" => t("Framework name"),
    "#default_value" => NULL,
    "#description" => "",
    "#maxlength" => 210,
    "#required" => TRUE,
  );

  $form["details"]["submit"] = array(
    "#type" => "submit",
    "#value" => t("Submit"),
  );

  return ($form);
}

function openabmma_addFramework_submit($form, &$form_state) {
	$table = "openabm_framework";
	$record = new stdClass();
	$record->name = $form_state['values']['name'];
	
	drupal_write_record($table, $record);
	$form_state['redirect'] = "config/frameworks";
}

function openabmma_deleteFramework($id='') {
  if ($id != "") {
    $query = "DELETE FROM openabm_framework WHERE id=%d";
    db_query($query, $id);
  }

  drupal_goto("config/frameworks");
  return '';
}