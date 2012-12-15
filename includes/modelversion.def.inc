<?php
// ;Id$

function _modelversion_cck_export() {

$content['type']  = array(
  'name' => 'Model Version',
  'type' => 'modelversion',
  'description' => '*DO NOT USE OUTSIDE OF THE MODEL ARCHIVE MODULE*  A single version of a given model.  This version is associated with a model, and multiple model_versions can be linked to a given model.  Different versions relate to major changes and feature variations in implementation, such as implemented in NetLogo and in RePast.  Minor updates in code and bugfixes are not separate versions here.  Instead, edits to a given model version are tracked through Drupal\'s version tracking system, so the history of edits for a given model version can be viewed and retrieved.',
  'title_label' => 'Title',
  'body_label' => 'Notes',
  'min_word_count' => '0',
  'help' => '',
  'node_options' => 
  array(
    'status' => TRUE,
    'promote' => FALSE,
    'sticky' => FALSE,
    'revision' => FALSE,
  ),
  'language_content_type' => '0',
  'upload' => '0',
  'teaser_field' => '0',
  'ffp_upload' => 
  array(
    'file_path' => 'models/[field_modelversion_modelnid-raw]/v[field_modelversion_number-raw]',
    'file_path_cleanup' => 
    array(
      'file_path_pathauto' => 0,
      'file_path_tolower' => 0,
      'file_path_transliterate' => 0,
    ),
    'file_name' => '[filefield-onlyname-original].[filefield-extension-original]',
    'file_name_cleanup' => 
    array(
      'file_name_pathauto' => 0,
      'file_name_tolower' => 0,
      'file_name_transliterate' => 0,
    ),
    'retroactive_update' => '',
    'active_updating' => 0,
  ),
  'upload_inline' => 0,
  'old_type' => 'modelversion',
  'orig_type' => '',
  'module' => 'node',
  'custom' => '1',
  'modified' => '1',
  'locked' => '0',
  'image_attach' => '0',
  'image_attach_maximum' => '0',
  'image_attach_size_teaser' => 'thumbnail',
  'image_attach_size_body' => 'thumbnail',
  'content_profile_use' => 0,
  'comment' => '2',
  'comment_default_mode' => '4',
  'comment_default_order' => '1',
  'comment_default_per_page' => '50',
  'comment_controls' => '3',
  'comment_anonymous' => '0',
  'comment_subject_field' => '1',
  'comment_preview' => '1',
  'comment_form_location' => '0',
  'csm_create_msg_en' => 'S',
  'csm_update_msg_en' => 'S',
  'csm_delete_msg_en' => '[type-name] <em>[title]</em> has been deleted.',
  'csm_form_title_en' => 'Create [type-name]',
  'ant' => '0',
  'ant_pattern' => '',
  'ant_php' => 0,
);
$content['fields']  = array(
  0 => 
  array(
    'label' => 'Locked?',
    'field_name' => 'field_modelversion_locked',
    'type' => 'number_integer',
    'widget_type' => 'optionwidgets_onoff',
    'change' => 'Change basic information',
    'weight' => '-4',
    'description' => '',
    'default_value' => 
    array(
      0 => 
      array(
        'value' => NULL,
      ),
    ),
    'default_value_php' => '',
    'default_value_widget' => NULL,
    'group' => FALSE,
    'required' => 0,
    'multiple' => '0',
    'min' => '',
    'max' => '',
    'prefix' => '',
    'suffix' => '',
    'allowed_values' => '|Unlocked
|Locked',
    'allowed_values_php' => '',
    'op' => 'Save field settings',
    'module' => 'number',
    'widget_module' => 'optionwidgets',
    'columns' => 
    array(
      'value' => 
      array(
        'type' => 'int',
        'not null' => FALSE,
        'sortable' => TRUE,
      ),
    ),
    'display_settings' => 
    array(
      'label' => 
      array(
        'format' => 'above',
        'exclude' => 0,
      ),
      5 => 
      array(
        'format' => 'default',
        'exclude' => 0,
      ),
      'teaser' => 
      array(
        'format' => 'default',
        'exclude' => 0,
      ),
      'full' => 
      array(
        'format' => 'default',
        'exclude' => 0,
      ),
      4 => 
      array(
        'format' => 'default',
        'exclude' => 0,
      ),
      2 => 
      array(
        'format' => 'default',
        'exclude' => 0,
      ),
      3 => 
      array(
        'format' => 'default',
        'exclude' => 0,
      ),
      'token' => 
      array(
        'format' => 'default',
        'exclude' => 0,
      ),
    ),
  ),
  1 => 
  array(
    'label' => 'Version Number',
    'field_name' => 'field_modelversion_number',
    'type' => 'number_integer',
    'widget_type' => 'number',
    'change' => 'Change basic information',
    'weight' => '-3',
    'description' => '',
    'default_value' => 
    array(
      0 => 
      array(
        'value' => '',
        '_error_element' => 'default_value_widget][field_modelversion_number][0][value',
      ),
    ),
    'default_value_php' => '',
    'default_value_widget' => 
    array(
      'field_modelversion_number' => 
      array(
        0 => 
        array(
          'value' => '',
          '_error_element' => 'default_value_widget][field_modelversion_number][0][value',
        ),
      ),
    ),
    'group' => FALSE,
    'required' => 0,
    'multiple' => '0',
    'min' => '',
    'max' => '',
    'prefix' => '',
    'suffix' => '',
    'allowed_values' => '',
    'allowed_values_php' => '',
    'op' => 'Save field settings',
    'module' => 'number',
    'widget_module' => 'number',
    'columns' => 
    array(
      'value' => 
      array(
        'type' => 'int',
        'not null' => FALSE,
        'sortable' => TRUE,
      ),
    ),
    'display_settings' => 
    array(
      'label' => 
      array(
        'format' => 'above',
        'exclude' => 0,
      ),
      5 => 
      array(
        'format' => 'default',
        'exclude' => 0,
      ),
      'teaser' => 
      array(
        'format' => 'default',
        'exclude' => 0,
      ),
      'full' => 
      array(
        'format' => 'default',
        'exclude' => 0,
      ),
      4 => 
      array(
        'format' => 'default',
        'exclude' => 0,
      ),
      2 => 
      array(
        'format' => 'default',
        'exclude' => 0,
      ),
      3 => 
      array(
        'format' => 'default',
        'exclude' => 0,
      ),
      'token' => 
      array(
        'format' => 'default',
        'exclude' => 0,
      ),
    ),
  ),
  2 => 
  array(
    'label' => 'Model URI',
    'field_name' => 'field_modelversion_uri',
    'type' => 'text',
    'widget_type' => 'text_textfield',
    'change' => 'Change basic information',
    'weight' => '-2',
    'rows' => 5,
    'size' => '60',
    'description' => '',
    'default_value' => 
    array(
      0 => 
      array(
        'value' => '',
        '_error_element' => 'default_value_widget][field_modelversion_uri][0][value',
      ),
    ),
    'default_value_php' => '',
    'default_value_widget' => 
    array(
      'field_modelversion_uri' => 
      array(
        0 => 
        array(
          'value' => '',
          '_error_element' => 'default_value_widget][field_modelversion_uri][0][value',
        ),
      ),
    ),
    'group' => FALSE,
    'required' => 0,
    'multiple' => '0',
    'text_processing' => '0',
    'max_length' => '',
    'allowed_values' => '',
    'allowed_values_php' => '',
    'op' => 'Save field settings',
    'module' => 'text',
    'widget_module' => 'text',
    'columns' => 
    array(
      'value' => 
      array(
        'type' => 'text',
        'size' => 'big',
        'not null' => FALSE,
        'sortable' => TRUE,
        'views' => TRUE,
      ),
    ),
    'display_settings' => 
    array(
      'label' => 
      array(
        'format' => 'above',
        'exclude' => 0,
      ),
      5 => 
      array(
        'format' => 'default',
        'exclude' => 0,
      ),
      'teaser' => 
      array(
        'format' => 'default',
        'exclude' => 0,
      ),
      'full' => 
      array(
        'format' => 'default',
        'exclude' => 0,
      ),
      4 => 
      array(
        'format' => 'default',
        'exclude' => 0,
      ),
      2 => 
      array(
        'format' => 'default',
        'exclude' => 0,
      ),
      3 => 
      array(
        'format' => 'default',
        'exclude' => 0,
      ),
      'token' => 
      array(
        'format' => 'default',
        'exclude' => 0,
      ),
    ),
  ),
  3 => 
  array(
    'label' => 'Model NID',
    'field_name' => 'field_modelversion_modelnid',
    'type' => 'number_integer',
    'widget_type' => 'number',
    'change' => 'Change basic information',
    'weight' => '-1',
    'description' => '',
    'default_value' => 
    array(
      0 => 
      array(
        'value' => '0',
        '_error_element' => 'default_value_widget][field_modelversion_modelnid][0][value',
      ),
    ),
    'default_value_php' => '',
    'default_value_widget' => 
    array(
      'field_modelversion_modelnid' => 
      array(
        0 => 
        array(
          'value' => '0',
          '_error_element' => 'default_value_widget][field_modelversion_modelnid][0][value',
        ),
      ),
    ),
    'group' => FALSE,
    'required' => 0,
    'multiple' => '0',
    'min' => '',
    'max' => '',
    'prefix' => '',
    'suffix' => '',
    'allowed_values' => '',
    'allowed_values_php' => '',
    'op' => 'Save field settings',
    'module' => 'number',
    'widget_module' => 'number',
    'columns' => 
    array(
      'value' => 
      array(
        'type' => 'int',
        'not null' => FALSE,
        'sortable' => TRUE,
      ),
    ),
    'display_settings' => 
    array(
      'label' => 
      array(
        'format' => 'above',
        'exclude' => 0,
      ),
      5 => 
      array(
        'format' => 'default',
        'exclude' => 0,
      ),
      'teaser' => 
      array(
        'format' => 'default',
        'exclude' => 0,
      ),
      'full' => 
      array(
        'format' => 'default',
        'exclude' => 0,
      ),
      4 => 
      array(
        'format' => 'default',
        'exclude' => 0,
      ),
      2 => 
      array(
        'format' => 'default',
        'exclude' => 0,
      ),
      3 => 
      array(
        'format' => 'default',
        'exclude' => 0,
      ),
      'token' => 
      array(
        'format' => 'default',
        'exclude' => 0,
      ),
    ),
  ),
  4 => 
  array(
    'label' => 'Platform',
    'field_name' => 'field_modelversion_platform',
    'type' => 'number_integer',
    'widget_type' => 'optionwidgets_select',
    'change' => 'Change basic information',
    'weight' => '1',
    'description' => '',
    'default_value' => 
    array(
      0 => 
      array(
        'value' => '',
      ),
    ),
    'default_value_php' => '',
    'default_value_widget' => 
    array(
      'field_modelversion_platform' => 
      array(
        'value' => '',
      ),
    ),
    'group' => FALSE,
    'required' => 0,
    'multiple' => '0',
    'min' => '',
    'max' => '',
    'prefix' => '',
    'suffix' => '',
    'allowed_values' => '1|Ascape 5
2|Breve
3|Cormas
4|DEVSJAVA
5|Ecolab
6|Mason
7|MASS
8|MobiDyc
9|NetLogo
10|Repast
11|Sesam
12|StarLogo
13|Swarm',
    'allowed_values_php' => '',
    'op' => 'Save field settings',
    'module' => 'number',
    'widget_module' => 'optionwidgets',
    'columns' => 
    array(
      'value' => 
      array(
        'type' => 'int',
        'not null' => FALSE,
        'sortable' => TRUE,
      ),
    ),
    'display_settings' => 
    array(
      'label' => 
      array(
        'format' => 'above',
        'exclude' => 0,
      ),
      5 => 
      array(
        'format' => 'default',
        'exclude' => 0,
      ),
      'teaser' => 
      array(
        'format' => 'default',
        'exclude' => 0,
      ),
      'full' => 
      array(
        'format' => 'default',
        'exclude' => 0,
      ),
      4 => 
      array(
        'format' => 'default',
        'exclude' => 0,
      ),
      2 => 
      array(
        'format' => 'default',
        'exclude' => 0,
      ),
      3 => 
      array(
        'format' => 'default',
        'exclude' => 0,
      ),
      'token' => 
      array(
        'format' => 'default',
        'exclude' => 0,
      ),
    ),
  ),
  5 => 
  array(
    'label' => 'Platform Version',
    'field_name' => 'field_modelversion_platformver',
    'type' => 'text',
    'widget_type' => 'text_textfield',
    'change' => 'Change basic information',
    'weight' => '2',
    'rows' => 5,
    'size' => '10',
    'description' => '',
    'default_value' => 
    array(
      0 => 
      array(
        'value' => '',
        '_error_element' => 'default_value_widget][field_modelversion_platformver][0][value',
      ),
    ),
    'default_value_php' => '',
    'default_value_widget' => 
    array(
      'field_modelversion_platformver' => 
      array(
        0 => 
        array(
          'value' => '',
          '_error_element' => 'default_value_widget][field_modelversion_platformver][0][value',
        ),
      ),
    ),
    'group' => FALSE,
    'required' => 0,
    'multiple' => '0',
    'text_processing' => '0',
    'max_length' => '',
    'allowed_values' => '',
    'allowed_values_php' => '',
    'op' => 'Save field settings',
    'module' => 'text',
    'widget_module' => 'text',
    'columns' => 
    array(
      'value' => 
      array(
        'type' => 'text',
        'size' => 'big',
        'not null' => FALSE,
        'sortable' => TRUE,
        'views' => TRUE,
      ),
    ),
    'display_settings' => 
    array(
      'label' => 
      array(
        'format' => 'above',
        'exclude' => 0,
      ),
      5 => 
      array(
        'format' => 'default',
        'exclude' => 0,
      ),
      'teaser' => 
      array(
        'format' => 'default',
        'exclude' => 0,
      ),
      'full' => 
      array(
        'format' => 'default',
        'exclude' => 0,
      ),
      4 => 
      array(
        'format' => 'default',
        'exclude' => 0,
      ),
      2 => 
      array(
        'format' => 'default',
        'exclude' => 0,
      ),
      3 => 
      array(
        'format' => 'default',
        'exclude' => 0,
      ),
      'token' => 
      array(
        'format' => 'default',
        'exclude' => 0,
      ),
    ),
  ),
  6 => 
  array(
    'label' => 'Programming Language',
    'field_name' => 'field_modelversion_language',
    'type' => 'number_integer',
    'widget_type' => 'optionwidgets_select',
    'change' => 'Change basic information',
    'weight' => '3',
    'description' => '',
    'default_value' => 
    array(
      0 => 
      array(
        'value' => '',
      ),
    ),
    'default_value_php' => '',
    'default_value_widget' => 
    array(
      'field_modelversion_language' => 
      array(
        'value' => '',
      ),
    ),
    'group' => FALSE,
    'required' => 0,
    'multiple' => '0',
    'min' => '',
    'max' => '',
    'prefix' => '',
    'suffix' => '',
    'allowed_values' => '1|C
2|C++
3|Java
4|Logo (variant)
5|Perl
6|Python
7|Other',
    'allowed_values_php' => '',
    'op' => 'Save field settings',
    'module' => 'number',
    'widget_module' => 'optionwidgets',
    'columns' => 
    array(
      'value' => 
      array(
        'type' => 'int',
        'not null' => FALSE,
        'sortable' => TRUE,
      ),
    ),
    'display_settings' => 
    array(
      'label' => 
      array(
        'format' => 'above',
        'exclude' => 0,
      ),
      5 => 
      array(
        'format' => 'default',
        'exclude' => 0,
      ),
      'teaser' => 
      array(
        'format' => 'default',
        'exclude' => 0,
      ),
      'full' => 
      array(
        'format' => 'default',
        'exclude' => 0,
      ),
      4 => 
      array(
        'format' => 'default',
        'exclude' => 0,
      ),
      2 => 
      array(
        'format' => 'default',
        'exclude' => 0,
      ),
      3 => 
      array(
        'format' => 'default',
        'exclude' => 0,
      ),
      'token' => 
      array(
        'format' => 'default',
        'exclude' => 0,
      ),
    ),
  ),
  7 => 
  array(
    'label' => 'Other Language',
    'field_name' => 'field_modelversion_otherlang',
    'type' => 'text',
    'widget_type' => 'text_textfield',
    'change' => 'Change basic information',
    'weight' => '4',
    'rows' => 5,
    'size' => '10',
    'description' => '',
    'default_value' => 
    array(
      0 => 
      array(
        'value' => '',
        '_error_element' => 'default_value_widget][field_modelversion_otherlang][0][value',
      ),
    ),
    'default_value_php' => '',
    'default_value_widget' => 
    array(
      'field_modelversion_otherlang' => 
      array(
        0 => 
        array(
          'value' => '',
          '_error_element' => 'default_value_widget][field_modelversion_otherlang][0][value',
        ),
      ),
    ),
    'group' => FALSE,
    'required' => 0,
    'multiple' => '0',
    'text_processing' => '0',
    'max_length' => '',
    'allowed_values' => '',
    'allowed_values_php' => '',
    'op' => 'Save field settings',
    'module' => 'text',
    'widget_module' => 'text',
    'columns' => 
    array(
      'value' => 
      array(
        'type' => 'text',
        'size' => 'big',
        'not null' => FALSE,
        'sortable' => TRUE,
        'views' => TRUE,
      ),
    ),
    'display_settings' => 
    array(
      'label' => 
      array(
        'format' => 'above',
        'exclude' => 0,
      ),
      5 => 
      array(
        'format' => 'default',
        'exclude' => 0,
      ),
      'teaser' => 
      array(
        'format' => 'default',
        'exclude' => 0,
      ),
      'full' => 
      array(
        'format' => 'default',
        'exclude' => 0,
      ),
      4 => 
      array(
        'format' => 'default',
        'exclude' => 0,
      ),
      2 => 
      array(
        'format' => 'default',
        'exclude' => 0,
      ),
      3 => 
      array(
        'format' => 'default',
        'exclude' => 0,
      ),
      'token' => 
      array(
        'format' => 'default',
        'exclude' => 0,
      ),
    ),
  ),
  8 => 
  array(
    'label' => 'Language Version',
    'field_name' => 'field_modelversion_langversion',
    'type' => 'text',
    'widget_type' => 'text_textfield',
    'change' => 'Change basic information',
    'weight' => '5',
    'rows' => 5,
    'size' => '10',
    'description' => '',
    'default_value' => 
    array(
      0 => 
      array(
        'value' => '',
        '_error_element' => 'default_value_widget][field_modelversion_langversion][0][value',
      ),
    ),
    'default_value_php' => '',
    'default_value_widget' => 
    array(
      'field_modelversion_langversion' => 
      array(
        0 => 
        array(
          'value' => '',
          '_error_element' => 'default_value_widget][field_modelversion_langversion][0][value',
        ),
      ),
    ),
    'group' => FALSE,
    'required' => 0,
    'multiple' => '0',
    'text_processing' => '0',
    'max_length' => '',
    'allowed_values' => '',
    'allowed_values_php' => '',
    'op' => 'Save field settings',
    'module' => 'text',
    'widget_module' => 'text',
    'columns' => 
    array(
      'value' => 
      array(
        'type' => 'text',
        'size' => 'big',
        'not null' => FALSE,
        'sortable' => TRUE,
        'views' => TRUE,
      ),
    ),
    'display_settings' => 
    array(
      'label' => 
      array(
        'format' => 'above',
        'exclude' => 0,
      ),
      5 => 
      array(
        'format' => 'default',
        'exclude' => 0,
      ),
      'teaser' => 
      array(
        'format' => 'default',
        'exclude' => 0,
      ),
      'full' => 
      array(
        'format' => 'default',
        'exclude' => 0,
      ),
      4 => 
      array(
        'format' => 'default',
        'exclude' => 0,
      ),
      2 => 
      array(
        'format' => 'default',
        'exclude' => 0,
      ),
      3 => 
      array(
        'format' => 'default',
        'exclude' => 0,
      ),
      'token' => 
      array(
        'format' => 'default',
        'exclude' => 0,
      ),
    ),
  ),
  9 => 
  array(
    'label' => 'OS',
    'field_name' => 'field_modelversion_os',
    'type' => 'number_integer',
    'widget_type' => 'optionwidgets_select',
    'change' => 'Change basic information',
    'weight' => '6',
    'description' => '',
    'default_value' => 
    array(
      0 => 
      array(
        'value' => '',
      ),
    ),
    'default_value_php' => '',
    'default_value_widget' => 
    array(
      'field_modelversion_os' => 
      array(
        'value' => '',
      ),
    ),
    'group' => FALSE,
    'required' => 0,
    'multiple' => '0',
    'min' => '',
    'max' => '',
    'prefix' => '',
    'suffix' => '',
    'allowed_values' => '1|Linux
2|Mac
3|Windows
4|Platform Independent
5|Other',
    'allowed_values_php' => '',
    'op' => 'Save field settings',
    'module' => 'number',
    'widget_module' => 'optionwidgets',
    'columns' => 
    array(
      'value' => 
      array(
        'type' => 'int',
        'not null' => FALSE,
        'sortable' => TRUE,
      ),
    ),
    'display_settings' => 
    array(
      'label' => 
      array(
        'format' => 'above',
        'exclude' => 0,
      ),
      5 => 
      array(
        'format' => 'default',
        'exclude' => 0,
      ),
      'teaser' => 
      array(
        'format' => 'default',
        'exclude' => 0,
      ),
      'full' => 
      array(
        'format' => 'default',
        'exclude' => 0,
      ),
      4 => 
      array(
        'format' => 'default',
        'exclude' => 0,
      ),
      2 => 
      array(
        'format' => 'default',
        'exclude' => 0,
      ),
      3 => 
      array(
        'format' => 'default',
        'exclude' => 0,
      ),
      'token' => 
      array(
        'format' => 'default',
        'exclude' => 0,
      ),
    ),
  ),
  10 => 
  array(
    'label' => 'OS Version',
    'field_name' => 'field_modelversion_osversion',
    'type' => 'text',
    'widget_type' => 'text_textfield',
    'change' => 'Change basic information',
    'weight' => '7',
    'rows' => 5,
    'size' => '10',
    'description' => '',
    'default_value' => 
    array(
      0 => 
      array(
        'value' => '',
        '_error_element' => 'default_value_widget][field_modelversion_osversion][0][value',
      ),
    ),
    'default_value_php' => '',
    'default_value_widget' => 
    array(
      'field_modelversion_osversion' => 
      array(
        0 => 
        array(
          'value' => '',
          '_error_element' => 'default_value_widget][field_modelversion_osversion][0][value',
        ),
      ),
    ),
    'group' => FALSE,
    'required' => 0,
    'multiple' => '0',
    'text_processing' => '0',
    'max_length' => '',
    'allowed_values' => '',
    'allowed_values_php' => '',
    'op' => 'Save field settings',
    'module' => 'text',
    'widget_module' => 'text',
    'columns' => 
    array(
      'value' => 
      array(
        'type' => 'text',
        'size' => 'big',
        'not null' => FALSE,
        'sortable' => TRUE,
        'views' => TRUE,
      ),
    ),
    'display_settings' => 
    array(
      'label' => 
      array(
        'format' => 'above',
        'exclude' => 0,
      ),
      5 => 
      array(
        'format' => 'default',
        'exclude' => 0,
      ),
      'teaser' => 
      array(
        'format' => 'default',
        'exclude' => 0,
      ),
      'full' => 
      array(
        'format' => 'default',
        'exclude' => 0,
      ),
      4 => 
      array(
        'format' => 'default',
        'exclude' => 0,
      ),
      2 => 
      array(
        'format' => 'default',
        'exclude' => 0,
      ),
      3 => 
      array(
        'format' => 'default',
        'exclude' => 0,
      ),
      'token' => 
      array(
        'format' => 'default',
        'exclude' => 0,
      ),
    ),
  ),
  11 => 
  array(
    'label' => 'License',
    'field_name' => 'field_modelversion_license',
    'type' => 'number_integer',
    'widget_type' => 'optionwidgets_select',
    'change' => 'Change basic information',
    'weight' => '8',
    'description' => '',
    'default_value' => 
    array(
      0 => 
      array(
        'value' => '',
      ),
    ),
    'default_value_php' => '',
    'default_value_widget' => 
    array(
      'field_modelversion_license' => 
      array(
        'value' => '',
      ),
    ),
    'group' => FALSE,
    'required' => 1,
    'multiple' => '0',
    'min' => '',
    'max' => '',
    'prefix' => '',
    'suffix' => '',
    'allowed_values' => '1|GNU GPL, Version 2
2|GNU GPL, Version 3
3|Apache License, Version 2.0
4|Creative Commons (cc by)
5|Creative Commons(cc by-sa)
6|Creative Commons (cc by-nd)
7|Creative Commons (cc by-nc)
8|Creative Commons (cc by-nc-sa)
9|Creative Commons (cc by-nc-nd)
10|Academic Free License 3.0',
    'allowed_values_php' => '',
    'op' => 'Save field settings',
    'module' => 'number',
    'widget_module' => 'optionwidgets',
    'columns' => 
    array(
      'value' => 
      array(
        'type' => 'int',
        'not null' => FALSE,
        'sortable' => TRUE,
      ),
    ),
    'display_settings' => 
    array(
      'label' => 
      array(
        'format' => 'above',
        'exclude' => 0,
      ),
      5 => 
      array(
        'format' => 'default',
        'exclude' => 0,
      ),
      'teaser' => 
      array(
        'format' => 'default',
        'exclude' => 0,
      ),
      'full' => 
      array(
        'format' => 'default',
        'exclude' => 0,
      ),
      4 => 
      array(
        'format' => 'default',
        'exclude' => 0,
      ),
      2 => 
      array(
        'format' => 'default',
        'exclude' => 0,
      ),
      3 => 
      array(
        'format' => 'default',
        'exclude' => 0,
      ),
      'token' => 
      array(
        'format' => 'default',
        'exclude' => 0,
      ),
    ),
  ),
  12 => 
  array(
    'label' => 'Submitted for Review?',
    'field_name' => 'field_modelversion_review',
    'type' => 'number_integer',
    'widget_type' => 'optionwidgets_onoff',
    'change' => 'Change basic information',
    'weight' => '9',
    'description' => '',
    'default_value' => 
    array(
      0 => 
      array(
        'value' => NULL,
      ),
    ),
    'default_value_php' => '',
    'default_value_widget' => 
    array(
      'field_modelversion_review' => 
      array(
        'value' => FALSE,
      ),
    ),
    'group' => FALSE,
    'required' => 0,
    'multiple' => '0',
    'min' => '',
    'max' => '',
    'prefix' => '',
    'suffix' => '',
    'allowed_values' => '0|Not Review
1|Review',
    'allowed_values_php' => '',
    'op' => 'Save field settings',
    'module' => 'number',
    'widget_module' => 'optionwidgets',
    'columns' => 
    array(
      'value' => 
      array(
        'type' => 'int',
        'not null' => FALSE,
        'sortable' => TRUE,
      ),
    ),
    'display_settings' => 
    array(
      'label' => 
      array(
        'format' => 'above',
        'exclude' => 0,
      ),
      5 => 
      array(
        'format' => 'default',
        'exclude' => 0,
      ),
      'teaser' => 
      array(
        'format' => 'default',
        'exclude' => 0,
      ),
      'full' => 
      array(
        'format' => 'default',
        'exclude' => 0,
      ),
      4 => 
      array(
        'format' => 'default',
        'exclude' => 0,
      ),
      2 => 
      array(
        'format' => 'default',
        'exclude' => 0,
      ),
      3 => 
      array(
        'format' => 'default',
        'exclude' => 0,
      ),
      'token' => 
      array(
        'format' => 'default',
        'exclude' => 0,
      ),
    ),
  ),
  13 => 
  array(
    'label' => 'Run Conditions',
    'field_name' => 'field_modelversion_runconditions',
    'type' => 'text',
    'widget_type' => 'text_textarea',
    'change' => 'Change basic information',
    'weight' => '10',
    'rows' => '5',
    'size' => 60,
    'description' => '',
    'default_value' => 
    array(
      0 => 
      array(
        'value' => '',
        '_error_element' => 'default_value_widget][field_modelversion_runconditions][0][value',
      ),
    ),
    'default_value_php' => '',
    'default_value_widget' => 
    array(
      'field_modelversion_runconditions' => 
      array(
        0 => 
        array(
          'value' => '',
          '_error_element' => 'default_value_widget][field_modelversion_runconditions][0][value',
        ),
      ),
    ),
    'group' => FALSE,
    'required' => 0,
    'multiple' => '0',
    'text_processing' => '0',
    'max_length' => '',
    'allowed_values' => '',
    'allowed_values_php' => '',
    'op' => 'Save field settings',
    'module' => 'text',
    'widget_module' => 'text',
    'columns' => 
    array(
      'value' => 
      array(
        'type' => 'text',
        'size' => 'big',
        'not null' => FALSE,
        'sortable' => TRUE,
        'views' => TRUE,
      ),
    ),
    'display_settings' => 
    array(
      'label' => 
      array(
        'format' => 'above',
        'exclude' => 0,
      ),
      5 => 
      array(
        'format' => 'default',
        'exclude' => 0,
      ),
      'teaser' => 
      array(
        'format' => 'default',
        'exclude' => 0,
      ),
      'full' => 
      array(
        'format' => 'default',
        'exclude' => 0,
      ),
      4 => 
      array(
        'format' => 'default',
        'exclude' => 0,
      ),
      2 => 
      array(
        'format' => 'default',
        'exclude' => 0,
      ),
      3 => 
      array(
        'format' => 'default',
        'exclude' => 0,
      ),
      'token' => 
      array(
        'format' => 'default',
        'exclude' => 0,
      ),
    ),
  ),
  14 => 
  array(
    'label' => 'Code',
    'field_name' => 'field_modelversion_code',
    'type' => 'filefield',
    'widget_type' => 'filefield_widget',
    'change' => 'Change basic information',
    'weight' => '11',
    'file_extensions' => 'txt nlogo zip rar',
    'progress_indicator' => 'bar',
    'file_path' => '',
    'max_filesize_per_file' => '',
    'max_filesize_per_node' => '',
    'description' => '',
    'ffp_field_modelversion_code' => 
    array(
      'file_path' => 'models/[field_modelversion_modelnid-raw]/v[field_modelversion_number-raw]/code',
      'file_path_cleanup' => 
      array(
        'file_path_pathauto' => 0,
        'file_path_tolower' => 0,
        'file_path_transliterate' => 0,
      ),
      'file_name' => '[filefield-onlyname-original].[filefield-extension-original]',
      'file_name_cleanup' => 
      array(
        'file_name_pathauto' => 0,
        'file_name_tolower' => 0,
        'file_name_transliterate' => 0,
      ),
      'retroactive_update' => '',
      'active_updating' => 0,
    ),
    'title_text' => '0',
    'group' => FALSE,
    'required' => 1,
    'multiple' => '0',
    'list_field' => '0',
    'list_default' => 1,
    'description_field' => '0',
    'op' => 'Save field settings',
    'module' => 'filefield',
    'widget_module' => 'filefield',
    'columns' => 
    array(
      'fid' => 
      array(
        'type' => 'int',
        'not null' => FALSE,
        'views' => TRUE,
      ),
      'list' => 
      array(
        'type' => 'int',
        'size' => 'tiny',
        'not null' => FALSE,
        'views' => TRUE,
      ),
      'data' => 
      array(
        'type' => 'text',
        'serialize' => TRUE,
        'views' => TRUE,
      ),
    ),
    'display_settings' => 
    array(
      'label' => 
      array(
        'format' => 'above',
        'exclude' => 0,
      ),
      5 => 
      array(
        'format' => 'default',
        'exclude' => 0,
      ),
      'teaser' => 
      array(
        'format' => 'default',
        'exclude' => 0,
      ),
      'full' => 
      array(
        'format' => 'default',
        'exclude' => 0,
      ),
      4 => 
      array(
        'format' => 'default',
        'exclude' => 0,
      ),
      2 => 
      array(
        'format' => 'default',
        'exclude' => 0,
      ),
      3 => 
      array(
        'format' => 'default',
        'exclude' => 0,
      ),
      'token' => 
      array(
        'format' => 'default',
        'exclude' => 0,
      ),
    ),
  ),
  15 => 
  array(
    'label' => 'Documentation',
    'field_name' => 'field_modelversion_documentation',
    'type' => 'filefield',
    'widget_type' => 'filefield_widget',
    'change' => 'Change basic information',
    'weight' => '12',
    'file_extensions' => 'txt rtf doc docx pdf zip rar',
    'progress_indicator' => 'bar',
    'file_path' => '',
    'max_filesize_per_file' => '',
    'max_filesize_per_node' => '',
    'description' => '',
    'ffp_field_modelversion_documentation' => 
    array(
      'file_path' => 'models/[field_modelversion_modelnid-raw]/v[field_modelversion_number-raw]/doc',
      'file_path_cleanup' => 
      array(
        'file_path_pathauto' => 0,
        'file_path_tolower' => 0,
        'file_path_transliterate' => 0,
      ),
      'file_name' => '[filefield-onlyname-original].[filefield-extension-original]',
      'file_name_cleanup' => 
      array(
        'file_name_pathauto' => 0,
        'file_name_tolower' => 0,
        'file_name_transliterate' => 0,
      ),
      'retroactive_update' => '',
      'active_updating' => 0,
    ),
    'title_text' => '0',
    'group' => FALSE,
    'required' => 1,
    'multiple' => '0',
    'list_field' => '0',
    'list_default' => 1,
    'description_field' => '0',
    'op' => 'Save field settings',
    'module' => 'filefield',
    'widget_module' => 'filefield',
    'columns' => 
    array(
      'fid' => 
      array(
        'type' => 'int',
        'not null' => FALSE,
        'views' => TRUE,
      ),
      'list' => 
      array(
        'type' => 'int',
        'size' => 'tiny',
        'not null' => FALSE,
        'views' => TRUE,
      ),
      'data' => 
      array(
        'type' => 'text',
        'serialize' => TRUE,
        'views' => TRUE,
      ),
    ),
    'display_settings' => 
    array(
      'label' => 
      array(
        'format' => 'above',
        'exclude' => 0,
      ),
      5 => 
      array(
        'format' => 'default',
        'exclude' => 0,
      ),
      'teaser' => 
      array(
        'format' => 'default',
        'exclude' => 0,
      ),
      'full' => 
      array(
        'format' => 'default',
        'exclude' => 0,
      ),
      4 => 
      array(
        'format' => 'default',
        'exclude' => 0,
      ),
      2 => 
      array(
        'format' => 'default',
        'exclude' => 0,
      ),
      3 => 
      array(
        'format' => 'default',
        'exclude' => 0,
      ),
      'token' => 
      array(
        'format' => 'default',
        'exclude' => 0,
      ),
    ),
  ),
  16 => 
  array(
    'label' => 'Sensitivity',
    'field_name' => 'field_modelversion_sensitivity',
    'type' => 'filefield',
    'widget_type' => 'filefield_widget',
    'change' => 'Change basic information',
    'weight' => '13',
    'file_extensions' => 'txt rtf csv doc docx xls xlsx pdf zip rar',
    'progress_indicator' => 'bar',
    'file_path' => '',
    'max_filesize_per_file' => '',
    'max_filesize_per_node' => '',
    'description' => '',
    'ffp_field_modelversion_sensitivity' => 
    array(
      'file_path' => 'models/[field_modelversion_modelnid-raw]/v[field_modelversion_number-raw]/sensitivity',
      'file_path_cleanup' => 
      array(
        'file_path_pathauto' => 0,
        'file_path_tolower' => 0,
        'file_path_transliterate' => 0,
      ),
      'file_name' => '[filefield-onlyname-original].[filefield-extension-original]',
      'file_name_cleanup' => 
      array(
        'file_name_pathauto' => 0,
        'file_name_tolower' => 0,
        'file_name_transliterate' => 0,
      ),
      'retroactive_update' => '',
      'active_updating' => 0,
    ),
    'title_text' => '0',
    'group' => FALSE,
    'required' => 0,
    'multiple' => '0',
    'list_field' => '0',
    'list_default' => 1,
    'description_field' => '0',
    'op' => 'Save field settings',
    'module' => 'filefield',
    'widget_module' => 'filefield',
    'columns' => 
    array(
      'fid' => 
      array(
        'type' => 'int',
        'not null' => FALSE,
        'views' => TRUE,
      ),
      'list' => 
      array(
        'type' => 'int',
        'size' => 'tiny',
        'not null' => FALSE,
        'views' => TRUE,
      ),
      'data' => 
      array(
        'type' => 'text',
        'serialize' => TRUE,
        'views' => TRUE,
      ),
    ),
    'display_settings' => 
    array(
      'label' => 
      array(
        'format' => 'above',
        'exclude' => 0,
      ),
      5 => 
      array(
        'format' => 'default',
        'exclude' => 0,
      ),
      'teaser' => 
      array(
        'format' => 'default',
        'exclude' => 0,
      ),
      'full' => 
      array(
        'format' => 'default',
        'exclude' => 0,
      ),
      4 => 
      array(
        'format' => 'default',
        'exclude' => 0,
      ),
      2 => 
      array(
        'format' => 'default',
        'exclude' => 0,
      ),
      3 => 
      array(
        'format' => 'default',
        'exclude' => 0,
      ),
      'token' => 
      array(
        'format' => 'default',
        'exclude' => 0,
      ),
    ),
  ),
  17 => 
  array(
    'label' => 'Dataset',
    'field_name' => 'field_modelversion_dataset',
    'type' => 'filefield',
    'widget_type' => 'filefield_widget',
    'change' => 'Change basic information',
    'weight' => '14',
    'file_extensions' => 'txt rtf csv doc docx xls xlsx pdf zip rar',
    'progress_indicator' => 'bar',
    'file_path' => '',
    'max_filesize_per_file' => '',
    'max_filesize_per_node' => '',
    'description' => '',
    'ffp_field_modelversion_dataset' => 
    array(
      'file_path' => 'models/[field_modelversion_modelnid-raw]/v[field_modelversion_number-raw]/dataset',
      'file_path_cleanup' => 
      array(
        'file_path_pathauto' => 0,
        'file_path_tolower' => 0,
        'file_path_transliterate' => 0,
      ),
      'file_name' => '[filefield-onlyname-original].[filefield-extension-original]',
      'file_name_cleanup' => 
      array(
        'file_name_pathauto' => 0,
        'file_name_tolower' => 0,
        'file_name_transliterate' => 0,
      ),
      'retroactive_update' => '',
      'active_updating' => 0,
    ),
    'title_text' => '0',
    'group' => FALSE,
    'required' => 0,
    'multiple' => '0',
    'list_field' => '0',
    'list_default' => 1,
    'description_field' => '0',
    'op' => 'Save field settings',
    'module' => 'filefield',
    'widget_module' => 'filefield',
    'columns' => 
    array(
      'fid' => 
      array(
        'type' => 'int',
        'not null' => FALSE,
        'views' => TRUE,
      ),
      'list' => 
      array(
        'type' => 'int',
        'size' => 'tiny',
        'not null' => FALSE,
        'views' => TRUE,
      ),
      'data' => 
      array(
        'type' => 'text',
        'serialize' => TRUE,
        'views' => TRUE,
      ),
    ),
    'display_settings' => 
    array(
      'label' => 
      array(
        'format' => 'above',
        'exclude' => 0,
      ),
      5 => 
      array(
        'format' => 'default',
        'exclude' => 0,
      ),
      'teaser' => 
      array(
        'format' => 'default',
        'exclude' => 0,
      ),
      'full' => 
      array(
        'format' => 'default',
        'exclude' => 0,
      ),
      4 => 
      array(
        'format' => 'default',
        'exclude' => 0,
      ),
      2 => 
      array(
        'format' => 'default',
        'exclude' => 0,
      ),
      3 => 
      array(
        'format' => 'default',
        'exclude' => 0,
      ),
      'token' => 
      array(
        'format' => 'default',
        'exclude' => 0,
      ),
    ),
  ),
  18 => 
  array(
    'label' => 'Additional Files',
    'field_name' => 'field_modelversion_addfiles',
    'type' => 'filefield',
    'widget_type' => 'filefield_widget',
    'change' => 'Change basic information',
    'weight' => '15',
    'file_extensions' => 'txt rtf doc docx xls xlsx pdf zip rar',
    'progress_indicator' => 'bar',
    'file_path' => '',
    'max_filesize_per_file' => '',
    'max_filesize_per_node' => '',
    'description' => '',
    'ffp_field_modelversion_addfiles' => 
    array(
      'file_path' => 'models/[field_modelversion_modelnid-raw]/v[field_modelversion_number-raw]/other',
      'file_path_cleanup' => 
      array(
        'file_path_pathauto' => 0,
        'file_path_tolower' => 0,
        'file_path_transliterate' => 0,
      ),
      'file_name' => '[filefield-onlyname-original].[filefield-extension-original]',
      'file_name_cleanup' => 
      array(
        'file_name_pathauto' => 0,
        'file_name_tolower' => 0,
        'file_name_transliterate' => 0,
      ),
      'retroactive_update' => '',
      'active_updating' => 0,
    ),
    'title_text' => '0',
    'group' => FALSE,
    'required' => 0,
    'multiple' => '0',
    'list_field' => '0',
    'list_default' => 1,
    'description_field' => '0',
    'op' => 'Save field settings',
    'module' => 'filefield',
    'widget_module' => 'filefield',
    'columns' => 
    array(
      'fid' => 
      array(
        'type' => 'int',
        'not null' => FALSE,
        'views' => TRUE,
      ),
      'list' => 
      array(
        'type' => 'int',
        'size' => 'tiny',
        'not null' => FALSE,
        'views' => TRUE,
      ),
      'data' => 
      array(
        'type' => 'text',
        'serialize' => TRUE,
        'views' => TRUE,
      ),
    ),
    'display_settings' => 
    array(
      'label' => 
      array(
        'format' => 'above',
        'exclude' => 0,
      ),
      5 => 
      array(
        'format' => 'default',
        'exclude' => 0,
      ),
      'teaser' => 
      array(
        'format' => 'default',
        'exclude' => 0,
      ),
      'full' => 
      array(
        'format' => 'default',
        'exclude' => 0,
      ),
      4 => 
      array(
        'format' => 'default',
        'exclude' => 0,
      ),
      2 => 
      array(
        'format' => 'default',
        'exclude' => 0,
      ),
      3 => 
      array(
        'format' => 'default',
        'exclude' => 0,
      ),
      'token' => 
      array(
        'format' => 'default',
        'exclude' => 0,
      ),
    ),
  ),
);
$content['extra']  = array(
  'title' => '6',
  'body_field' => '11',
  'revision_information' => '31',
  'author' => '34',
  'options' => '35',
  'comment_settings' => '33',
  'menu' => '29',
  'book' => '30',
  'path' => '32',
  'detailed_question' => '28',
  'path_redirect' => '27',
  'url' => '41',
  'click_count' => '42',
  'last_click' => '43',
  'last_status' => '39',
  'last_status_info' => '36',
  'last_checked' => '37',
  'urlhash' => '40',
  'reciprocal' => '38',
);

  return $content;
}