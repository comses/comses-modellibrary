<?php
// ;Id$

function _model_cck_export() {

$content['type']  = array(
  'name' => 'Model',
  'type' => 'model',
  'description' => '*DO NOT USE OUTSIDE OF THE MODEL ARCHIVE MODULE*  An agent- or individual-based model.',
  'title_label' => 'Title',
  'body_label' => 'Description',
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
  'teaser_field' => 0,
  'ffp_upload' => 
  array(
    'file_path' => 'models/[nid]',
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
  'old_type' => 'model',
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
  'csm_create_msg_en' => 'Submitted',
  'csm_update_msg_en' => 'Submitted',
  'csm_delete_msg_en' => '[type-name] <em>[title]</em> has been deleted.',
  'csm_form_title_en' => 'Create [type-name]',
  'ant' => '0',
  'ant_pattern' => '',
  'ant_php' => '',
);
$content['fields']  = array(
  0 => 
  array(
    'label' => 'Locked?',
    'field_name' => 'field_model_locked',
    'type' => 'number_integer',
    'widget_type' => 'optionwidgets_onoff',
    'change' => 'Change basic information',
    'weight' => '-9',
    'description' => '',
    'default_value' => 
    array(
      0 => 
      array(
        'value' => 0,
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
    'allowed_values' => '0|Unlocked
1|Locked',
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
      'weight' => '-9',
      'parent' => '',
      2 => 
      array(
        'format' => 'hidden',
        'exclude' => 0,
      ),
      3 => 
      array(
        'format' => 'hidden',
        'exclude' => 0,
      ),
      'label' => 
      array(
        'format' => 'above',
      ),
      'teaser' => 
      array(
        'format' => 'hidden',
        'exclude' => 0,
      ),
      'full' => 
      array(
        'format' => 'hidden',
        'exclude' => 0,
      ),
      5 => 
      array(
        'format' => 'default',
        'exclude' => 0,
      ),
      4 => 
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
    'label' => 'URI',
    'field_name' => 'field_model_uri',
    'type' => 'text',
    'widget_type' => 'text_textfield',
    'change' => 'Change basic information',
    'weight' => '-7',
    'rows' => 5,
    'size' => '60',
    'description' => '',
    'default_value' => 
    array(
      0 => 
      array(
        'value' => '',
        '_error_element' => 'default_value_widget][field_model_uri][0][value',
      ),
    ),
    'default_value_php' => '',
    'default_value_widget' => 
    array(
      'field_model_uri' => 
      array(
        0 => 
        array(
          'value' => '',
          '_error_element' => 'default_value_widget][field_model_uri][0][value',
        ),
      ),
    ),
    'group' => FALSE,
    'required' => 0,
    'multiple' => '0',
    'text_processing' => '0',
    'max_length' => '50',
    'allowed_values' => '',
    'allowed_values_php' => '',
    'op' => 'Save field settings',
    'module' => 'text',
    'widget_module' => 'text',
    'columns' => 
    array(
      'value' => 
      array(
        'type' => 'varchar',
        'length' => '50',
        'not null' => FALSE,
        'sortable' => TRUE,
        'views' => TRUE,
      ),
    ),
    'display_settings' => 
    array(
      'weight' => '-7',
      'parent' => '',
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
      'label' => 
      array(
        'format' => 'above',
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
      5 => 
      array(
        'format' => 'default',
        'exclude' => 0,
      ),
      4 => 
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
    'label' => 'Teaser',
    'field_name' => 'field_model_teaser',
    'type' => 'text',
    'widget_type' => 'text_textarea',
    'change' => 'Change basic information',
    'weight' => '-6',
    'rows' => '3',
    'size' => 60,
    'description' => '',
    'default_value' => 
    array(
      0 => 
      array(
        'value' => '',
        '_error_element' => 'default_value_widget][field_model_teaser][0][value',
      ),
    ),
    'default_value_php' => '',
    'default_value_widget' => 
    array(
      'field_model_teaser' => 
      array(
        0 => 
        array(
          'value' => '',
          '_error_element' => 'default_value_widget][field_model_teaser][0][value',
        ),
      ),
    ),
    'group' => FALSE,
    'required' => 1,
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
      'weight' => '-6',
      'parent' => '',
      2 => 
      array(
        'format' => 'hidden',
        'exclude' => 0,
      ),
      3 => 
      array(
        'format' => 'hidden',
        'exclude' => 0,
      ),
      'label' => 
      array(
        'format' => 'above',
      ),
      'teaser' => 
      array(
        'format' => 'default',
        'exclude' => 0,
      ),
      'full' => 
      array(
        'format' => 'hidden',
        'exclude' => 0,
      ),
      5 => 
      array(
        'format' => 'default',
        'exclude' => 0,
      ),
      4 => 
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
    'label' => 'Image',
    'field_name' => 'field_model_image',
    'type' => 'filefield',
    'widget_type' => 'imagefield_widget',
    'change' => 'Change basic information',
    'weight' => '-4',
    'file_extensions' => 'png gif jpg jpeg',
    'progress_indicator' => 'bar',
    'file_path' => '',
    'max_filesize_per_file' => '',
    'max_filesize_per_node' => '',
    'max_resolution' => 0,
    'min_resolution' => 0,
    'custom_alt' => 0,
    'alt' => '',
    'custom_title' => 0,
    'title_type' => 'textfield',
    'title' => '',
    'use_default_image' => 0,
    'default_image_upload' => '',
    'default_image' => NULL,
    'description' => '',
    'ffp_field_model_image' => 
    array(
      'file_path' => 'models/[nid]',
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
    'widget_module' => 'imagefield',
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
      'weight' => '-4',
      'parent' => '',
      2 => 
      array(
        'format' => 'image_plain',
        'exclude' => 0,
      ),
      3 => 
      array(
        'format' => 'image_plain',
        'exclude' => 0,
      ),
      'label' => 
      array(
        'format' => 'above',
      ),
      'teaser' => 
      array(
        'format' => 'image_plain',
        'exclude' => 0,
      ),
      'full' => 
      array(
        'format' => 'image_plain',
        'exclude' => 0,
      ),
      5 => 
      array(
        'format' => 'image_plain',
        'exclude' => 0,
      ),
      4 => 
      array(
        'format' => 'image_plain',
        'exclude' => 0,
      ),
      'token' => 
      array(
        'format' => 'image_plain',
        'exclude' => 0,
      ),
    ),
  ),
  4 => 
  array(
    'label' => 'Video',
    'field_name' => 'field_model_video',
    'type' => 'filefield',
    'widget_type' => 'uploadfield_widget',
    'change' => 'Change basic information',
    'weight' => '-3',
    'file_extensions' => 'mov mp4 mpeg avi mpg wmv flv',
    'progress_indicator' => 'bar',
    'file_path' => '',
    'max_filesize_per_file' => '',
    'max_filesize_per_node' => '',
    'default_dimensions' => '320x240',
    'default_player_dimensions' => '320x240',
    'autoconversion' => 0,
    'autothumbnail' => 'no',
    'default_video_thumb_upload' => '',
    'default_video_thumb' => 
    array(
      'filename' => 'video_thumbnail.png',
      'filepath' => 'files/video_thumbs/video_thumbnail.png',
      'filemime' => 'image/png',
      'source' => 'default_video_thumb_upload',
      'destination' => 'files/video_thumbs/video_thumbnail.png',
      'filesize' => 4213,
      'uid' => '1',
      'status' => 1,
      'timestamp' => 1297142888,
      'origname' => '',
      'fid' => '486',
    ),
    'description' => '',
    'ffp_field_model_video' => 
    array(
      'file_path' => 'models/[nid]',
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
    'widget_module' => 'uploadfield',
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
      'weight' => '-3',
      'parent' => '',
      2 => 
      array(
        'format' => 'video_plain',
        'exclude' => 0,
      ),
      3 => 
      array(
        'format' => 'video_plain',
        'exclude' => 0,
      ),
      'label' => 
      array(
        'format' => 'above',
      ),
      'teaser' => 
      array(
        'format' => 'Thumbnail__video_nodelink',
        'exclude' => 0,
      ),
      'full' => 
      array(
        'format' => 'swftools_no_file',
        'exclude' => 0,
      ),
      5 => 
      array(
        'format' => 'video_plain',
        'exclude' => 0,
      ),
      4 => 
      array(
        'format' => 'video_plain',
        'exclude' => 0,
      ),
      'token' => 
      array(
        'format' => 'video_plain',
        'exclude' => 0,
      ),
    ),
  ),
  5 => 
  array(
    'label' => 'Featured Model?',
    'field_name' => 'field_model_featured',
    'type' => 'number_integer',
    'widget_type' => 'optionwidgets_onoff',
    'change' => 'Change basic information',
    'weight' => '-2',
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
      'field_model_featured' => 
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
    'allowed_values' => '0|Not Featured
1|Featured',
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
      'weight' => '-2',
      'parent' => '',
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
      'label' => 
      array(
        'format' => 'above',
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
      5 => 
      array(
        'format' => 'default',
        'exclude' => 0,
      ),
      4 => 
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
    'label' => 'Replicated Model?',
    'field_name' => 'field_model_replicated',
    'type' => 'number_integer',
    'widget_type' => 'optionwidgets_onoff',
    'change' => 'Change basic information',
    'weight' => '1',
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
      'field_model_replicated' => 
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
    'allowed_values' => '0|Not Replicated
1|Replicated',
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
      'weight' => '1',
      'parent' => '',
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
      'label' => 
      array(
        'format' => 'above',
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
      5 => 
      array(
        'format' => 'default',
        'exclude' => 0,
      ),
      4 => 
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
    'label' => 'Reference',
    'field_name' => 'field_model_reference',
    'type' => 'text',
    'widget_type' => 'text_textarea',
    'change' => 'Change basic information',
    'weight' => '2',
    'rows' => '5',
    'size' => 60,
    'description' => '',
    'default_value' => 
    array(
      0 => 
      array(
        'value' => '',
        '_error_element' => 'default_value_widget][field_model_reference][0][value',
      ),
    ),
    'default_value_php' => '',
    'default_value_widget' => 
    array(
      'field_model_reference' => 
      array(
        0 => 
        array(
          'value' => '',
          '_error_element' => 'default_value_widget][field_model_reference][0][value',
          'format' => 1,
        ),
      ),
    ),
    'group' => FALSE,
    'required' => 0,
    'multiple' => '0',
    'text_processing' => '1',
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
      'format' => 
      array(
        'type' => 'int',
        'unsigned' => TRUE,
        'not null' => FALSE,
        'views' => FALSE,
      ),
    ),
    'display_settings' => 
    array(
      'weight' => '2',
      'parent' => '',
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
      'label' => 
      array(
        'format' => 'above',
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
      5 => 
      array(
        'format' => 'default',
        'exclude' => 0,
      ),
      4 => 
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
    'label' => 'Categories',
    'field_name' => 'field_model_tags',
    'type' => 'content_taxonomy',
    'widget_type' => 'content_taxonomy_autocomplete',
    'change' => 'Change basic information',
    'weight' => '3',
    'new_terms' => 'insert',
    'extra_parent' => '0',
    'maxlength' => '255',
    'active_tags' => 1,
    'description' => '<p>This field stores the category keywords you can associate with, or tag onto, the model.  Enter each tag one at a time.  As you type a tag, the field will suggest possible tags that are already in use.  If your tag is not already in use, then click "Add."  Once the tag has been added, you may then add another tag.</p><p>If you wish to use a multiword tag, such as "Evolution of cooperation", enter it without spaces: "evolutionofcooperation".</p>',
    'default_value' => 
    array(
    ),
    'default_value_php' => '',
    'default_value_widget' => 
    array(
      'field_model_tags' => 
      array(
        'value' => '',
      ),
    ),
    'group' => FALSE,
    'required' => 0,
    'multiple' => '1',
    'save_term_node' => 1,
    'vid' => '6',
    'parent' => '0',
    'parent_php_code' => '',
    'depth' => '',
    'op' => 'Save field settings',
    'module' => 'content_taxonomy',
    'widget_module' => 'content_taxonomy_autocomplete',
    'columns' => 
    array(
      'value' => 
      array(
        'type' => 'int',
        'not null' => FALSE,
        'sortable' => FALSE,
      ),
    ),
    'display_settings' => 
    array(
      'weight' => '3',
      'parent' => '',
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
      'label' => 
      array(
        'format' => 'above',
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
      5 => 
      array(
        'format' => 'default',
        'exclude' => 0,
      ),
      4 => 
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
    'label' => 'Enabled',
    'field_name' => 'field_model_enabled',
    'type' => 'number_integer',
    'widget_type' => 'optionwidgets_onoff',
    'change' => 'Change basic information',
    'weight' => '4',
    'description' => '',
    'default_value' => 
    array(
      0 => 
      array(
        'value' => 0,
      ),
    ),
    'default_value_php' => '',
    'default_value_widget' => 
    array(
      'field_model_enabled' => 
      array(
        'value' => FALSE,
      ),
    ),
    'group' => FALSE,
    'required' => 0,
    'multiple' => '0',
    'min' => '0',
    'max' => '1',
    'prefix' => '',
    'suffix' => '',
    'allowed_values' => '0|Disabled
1|Enabled',
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
      'weight' => '4',
      'parent' => '',
      2 => 
      array(
        'format' => 'hidden',
        'exclude' => 0,
      ),
      3 => 
      array(
        'format' => 'hidden',
        'exclude' => 0,
      ),
      'label' => 
      array(
        'format' => 'above',
      ),
      'teaser' => 
      array(
        'format' => 'hidden',
        'exclude' => 0,
      ),
      'full' => 
      array(
        'format' => 'hidden',
        'exclude' => 0,
      ),
      5 => 
      array(
        'format' => 'default',
        'exclude' => 0,
      ),
      4 => 
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
  'title' => '-8',
  'body_field' => '-5',
  'revision_information' => '6',
  'author' => '11',
  'options' => '12',
  'comment_settings' => '7',
  'menu' => '8',
  'taxonomy' => '-1',
  'book' => '9',
  'path' => '10',
  'detailed_question' => '10',
  'path_redirect' => '5',
  'url' => '19',
  'click_count' => '18',
  'last_click' => '20',
  'last_status' => '17',
  'last_status_info' => '16',
  'last_checked' => '13',
  'urlhash' => '14',
  'reciprocal' => '15',
);

  return $content;
}