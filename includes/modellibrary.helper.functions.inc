<?php

/*
 * Helper function to load the latest version of a model, given that model's NID
 *
 * @param $modelnid
 *
 * @return
 * Modelversion node
 */
function load_modelversion($modelnid) {
  $query = "SELECT node.nid FROM {node} node LEFT JOIN {field_data_field_modelversion_model} modelversion_modelnid "
         . "ON node.nid = modelversion_modelnid.entity_id AND (modelversion_modelnid.entity_type = 'node'AND modelversion_modelnid.deleted = '0') "
         . "LEFT JOIN {field_data_field_modelversion_number} modelversion_number "
         . "ON node.nid = modelversion_number.entity_id AND (modelversion_number.entity_type = 'node' AND modelversion_number.deleted = '0') "
         . "WHERE (((modelversion_modelnid.field_modelversion_model_nid = :nid)) AND (((node.status = '1') AND (node.type IN ('modelversion'))))) "
         . "ORDER BY modelversion_number.field_modelversion_number_value DESC";
  return node_load(db_query_range($query, 0, 1, array(':nid' => $modelnid))->fetchField());
}

/**
 * Retrieve highest version number in use for a given model.
 *
 * @param $modelnid
 *
 * @return
 * Integer highest model version number associated with the model.
 */
function modellibrary_helper_get_max_versionnum($modelnid) {
  $query = "SELECT modelversion_number.field_modelversion_number_value AS version_num FROM {node} node "
         . "LEFT JOIN {field_data_field_modelversion_model} modelversion_modelnid "
         . "ON node.nid = modelversion_modelnid.entity_id AND (modelversion_modelnid.entity_type = 'node' AND modelversion_modelnid.deleted = '0') "
         . "LEFT JOIN {field_data_field_modelversion_number} modelversion_number "
         . "ON node.nid = modelversion_number.entity_id AND (modelversion_number.entity_type = 'node' AND modelversion_number.deleted = '0') "
         . "WHERE (((modelversion_modelnid.field_modelversion_model_nid = :nid)) AND (((node.status = '1') AND (node.type IN ('modelversion'))))) "
         . "ORDER BY version_num DESC";

  return db_query_range($query, 0, 1, array(':nid' => $modelnid))->fetchField();
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
  $query = "SELECT modelversion_number.field_modelversion_number_value AS version_num FROM {node} node "
          ."LEFT JOIN {field_data_field_modelversion_model} modelversion_modelnid "
          ."ON node.nid = modelversion_modelnid.entity_id AND (modelversion_modelnid.entity_type = 'node' AND modelversion_modelnid.deleted = '0') "
          ."LEFT JOIN {field_data_field_modelversion_number} modelversion_number "
          ."ON node.nid = modelversion_number.entity_id AND (modelversion_number.entity_type = 'node' AND modelversion_number.deleted = '0') "
          ."WHERE (((modelversion_modelnid.field_modelversion_model_nid = :nid)) AND (((node.status = '1') AND (node.type IN ('modelversion'))))) "
          ."ORDER BY version_num DESC LIMIT 1 OFFSET 0 ";
  return db_query($query, array(':nid' => $modelnid))->fetchField() + 1;
}

