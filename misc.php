<?php

function openabmma_parseVersionNumber($versionString) {
  return substr($versionString, 7);
}

// what is the difference between this and in_array?  Other than the fact that this may be slower.
function openabmma_inList($value, $arr) {
  $c = 0;
  if ($value == null || $arr == null) {
    return -1;
  }

  foreach ($arr as $val) {
    if ($val == $value) {
      return $c;
    }
    $c++;
  }

  return -1;
}

function openabmma_get_js_path() {
  return drupal_get_path('module', 'openabmma') . '/openabmma.js';
}

function openabmma_get_css_path() {
  return drupal_get_path('module', 'openabmma') . '/openabmma.css';
}

function openabmma_ownerError() {
  return openabmma_formAccessError("Only model owners can change metadata details of any model. You are not registered as the owner of this model.");
}

function openabmma_getRoleName($rid) {
  if ($rid == '') {
    return "";
  }

  $result = (array) db_fetch_object(db_query("SELECT name FROM openabm_role WHERE id=%d", $rid));
  $name = $result['name'];

  return $name;
}

function openabmma_accessError($errorString) {
  //drupal_set_message("<b><font color='red'>Access Error:</font></b><br/>" . $errorString . "<p>Click " . l("here", "models") . " to go to your model workspace.</p>");
	drupal_set_message(t('Access Error'), $type = 'error');
}

function openabmma_formAccessError($errorString) {
  $form["details"] = array(
    "#type" => 'fieldset',
    "#collapsible" => FALSE,
    "#collapsed" => FALSE,
    "#title" => null,
    "#description" => null,
  );

  $form["details"]["name"] = array(
    "#type" => "item",
    "#title" => null,
    "#value" => "<b><font color='red'>Access error:</font></b><p>" . $errorString . "</p>",
  );

  return ($form);
}
