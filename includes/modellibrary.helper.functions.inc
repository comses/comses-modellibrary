<?php

/*
 * Helper function to load the latest version of a model, given that model's NID
 */
function _load_modelversion($model_nid) {
    $view_args = array($model_nid);
    $display_id = 'page_1'; // Model view
    $version_view = views_get_view('modelversion');
    $version_view->set_arguments($view_args);
    $version_view->set_display($display_id);
    $version_view->pre_execute();
    $version_view->execute();

    return node_load($version_view->render_field('nid', 0));
}

/**
 * Retrieve highest version number in use for a given model.
 *
 * TODO Make field_modelversion_modelnid_value db structure agnostic, like the version number field
 *
 * @param $modelnid
 *
 * @return
 * Boolean indicating whether submitted value is already in use.
 */
function modellibrary_helper_get_max_versionnum($modelnid) {
  $table_versionnum = modellibrary_helper_cck_field_sql('field_modelversion_number', 'table');
  $column_versionnum = modellibrary_helper_cck_field_sql('field_modelversion_number', 'column');

  return db_result(db_query("SELECT MAX(t.%s) FROM {%s} t WHERE t.field_modelversion_modelnid_value = %d", $column_versionnum, $table_versionnum, $modelnid));
}

/**
 * Retrieve highest version number for a given model and return the next available number.
 *
 * TODO Make field_modelversion_modelnid_value db structure agnostic, like the version number field
 *
 * @param $modelnid
 *
 * @return
 * Boolean indicating whether submitted value is already in use.
 */
function modellibrary_helper_get_next_versionnum($modelnid) {
  $table_versionnum = modellibrary_helper_cck_field_sql('field_modelversion_number', 'table');
  $column_versionnum = modellibrary_helper_cck_field_sql('field_modelversion_number', 'column');

  return db_result(db_query("SELECT MAX(t.%s) FROM {%s} t WHERE t.field_modelversion_modelnid_value = %d", $column_versionnum, $table_versionnum, $modelnid)) + 1;
}

/**
 * Query appropriate table to check if a field value already exists in any given content.
 *
 * @param $field
 * @param $value
 *
 * @return
 * Boolean indicating whether submitted value is already in use.
 */
function modellibrary_helper_check_if_cck_fieldvalue_exists($field, $value) {
  $table = modellibrary_helper_cck_field_sql($field, 'table');
  $column = modellibrary_helper_cck_field_sql($field, 'column');

  if (db_result(db_query("SELECT COUNT(t.%s) FROM {%s} t WHERE LOWER(t.%s) = LOWER('%s')", $column, $table, $column, $value)) > 0) {
    return TRUE;
  }
  else {
    return FALSE;
  }
}

/**
 * function to get the TABLE or COLUMN for a CCK FIELD
 * method adapted from http://drewish.com/content/2010/06/correctly_accessing_cck_fields_in_sql_queries *
 *
 * This method is needed because CCK fields can be stored in the database in two different ways
 * depending on whether the field is shared between multiple content types or not.
 *
 * @param $field_name
 * @param $type 'table' or 'column' *
 * to get both table and column, run function twice with each $type
 */
function modellibrary_helper_cck_field_sql($field_name, $type) {
  $field = content_fields($field_name);
  $db_info = content_database_info($field);

  if ($type == 'table' && isset($db_info['table'])) {
    return $db_info['table'];
  }
  elseif ($type == 'column' && isset($db_info['columns']['value']['column'])) {
    return $db_info['columns']['value']['column'];
  }
    return NULL;
}
