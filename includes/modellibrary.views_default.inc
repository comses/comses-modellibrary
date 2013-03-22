<?php
// ;Id$

/**
 * Implementation of hook_views_default_views().
 */
function modellibrary_views_default_views() {
$view = new view();
  $view->name = 'model_library';
  $view->description = '';
  $view->tag = 'default';
  $view->base_table = 'node';
  $view->human_name = 'Model Library';
  $view->core = 7;
  $view->api_version = '3.0';
  $view->disabled = FALSE; /* Edit this to true to make a default view disabled initially */

  /* Display: Master */
  $handler = $view->new_display('default', 'Master', 'default');
  $handler->display->display_options['title'] = 'CoMSES Computational Model Library';
  $handler->display->display_options['use_more_always'] = FALSE;
  $handler->display->display_options['access']['type'] = 'perm';
  $handler->display->display_options['cache']['type'] = 'none';
  $handler->display->display_options['query']['type'] = 'views_query';
  $handler->display->display_options['exposed_form']['type'] = 'basic';
  $handler->display->display_options['pager']['type'] = 'full';
  $handler->display->display_options['pager']['options']['items_per_page'] = '10';
  $handler->display->display_options['style_plugin'] = 'table';
  $handler->display->display_options['style_options']['columns'] = array(
    'nid' => 'nid',
    'title' => 'title',
    'uid' => 'uid',
    'field_model_author1first' => 'field_model_author1first',
    'field_model_author1middle' => 'field_model_author1middle',
    'field_model_author1last' => 'field_model_author1last',
    'body' => 'body',
    'created' => 'created',
  );
  $handler->display->display_options['style_options']['default'] = 'title';
  $handler->display->display_options['style_options']['info'] = array(
    'nid' => array(
      'sortable' => 0,
      'default_sort_order' => 'asc',
      'align' => '',
      'separator' => '',
      'empty_column' => 0,
    ),
    'title' => array(
      'sortable' => 1,
      'default_sort_order' => 'asc',
      'align' => '',
      'separator' => '',
      'empty_column' => 0,
    ),
    'uid' => array(
      'align' => '',
      'separator' => '',
      'empty_column' => 0,
    ),
    'field_model_author1first' => array(
      'sortable' => 0,
      'default_sort_order' => 'asc',
      'align' => '',
      'separator' => '',
      'empty_column' => 0,
    ),
    'field_model_author1middle' => array(
      'sortable' => 0,
      'default_sort_order' => 'asc',
      'align' => '',
      'separator' => '',
      'empty_column' => 0,
    ),
    'field_model_author1last' => array(
      'sortable' => 1,
      'default_sort_order' => 'asc',
      'align' => '',
      'separator' => '',
      'empty_column' => 0,
    ),
    'body' => array(
      'sortable' => 0,
      'default_sort_order' => 'asc',
      'align' => '',
      'separator' => '',
      'empty_column' => 0,
    ),
    'created' => array(
      'sortable' => 1,
      'default_sort_order' => 'desc',
      'align' => '',
      'separator' => '',
      'empty_column' => 0,
    ),
  );
  /* Relationship: Content: Author */
  $handler->display->display_options['relationships']['uid']['id'] = 'uid';
  $handler->display->display_options['relationships']['uid']['table'] = 'node';
  $handler->display->display_options['relationships']['uid']['field'] = 'uid';
  $handler->display->display_options['relationships']['uid']['label'] = 'Submitter';
  /* Relationship: User: Profile */
  $handler->display->display_options['relationships']['profile']['id'] = 'profile';
  $handler->display->display_options['relationships']['profile']['table'] = 'users';
  $handler->display->display_options['relationships']['profile']['field'] = 'profile';
  $handler->display->display_options['relationships']['profile']['relationship'] = 'uid';
  $handler->display->display_options['relationships']['profile']['bundle_types'] = array(
    'main' => 'main',
  );
  /* Field: Content: Nid */
  $handler->display->display_options['fields']['nid']['id'] = 'nid';
  $handler->display->display_options['fields']['nid']['table'] = 'node';
  $handler->display->display_options['fields']['nid']['field'] = 'nid';
  /* Field: Content: Title */
  $handler->display->display_options['fields']['title']['id'] = 'title';
  $handler->display->display_options['fields']['title']['table'] = 'node';
  $handler->display->display_options['fields']['title']['field'] = 'title';
  $handler->display->display_options['fields']['title']['alter']['make_link'] = TRUE;
  $handler->display->display_options['fields']['title']['alter']['path'] = 'model/[nid]';
  $handler->display->display_options['fields']['title']['alter']['word_boundary'] = FALSE;
  $handler->display->display_options['fields']['title']['alter']['ellipsis'] = FALSE;
  $handler->display->display_options['fields']['title']['element_label_colon'] = FALSE;
  $handler->display->display_options['fields']['title']['link_to_node'] = FALSE;
  /* Field: Content: Body */
  $handler->display->display_options['fields']['body']['id'] = 'body';
  $handler->display->display_options['fields']['body']['table'] = 'field_data_body';
  $handler->display->display_options['fields']['body']['field'] = 'body';
  $handler->display->display_options['fields']['body']['alter']['max_length'] = '250';
  $handler->display->display_options['fields']['body']['alter']['strip_tags'] = TRUE;
  $handler->display->display_options['fields']['body']['alter']['trim'] = TRUE;
  $handler->display->display_options['fields']['body']['type'] = 'text_summary_or_trimmed';
  $handler->display->display_options['fields']['body']['settings'] = array(
    'trim_length' => '300',
  );
  /* Field: User: Name */
  $handler->display->display_options['fields']['name']['id'] = 'name';
  $handler->display->display_options['fields']['name']['table'] = 'users';
  $handler->display->display_options['fields']['name']['field'] = 'name';
  $handler->display->display_options['fields']['name']['relationship'] = 'uid';
  $handler->display->display_options['fields']['name']['label'] = '';
  $handler->display->display_options['fields']['name']['element_label_colon'] = FALSE;
  $handler->display->display_options['fields']['name']['link_to_user'] = FALSE;
  /* Field: Profile: First Name or Initial */
  $handler->display->display_options['fields']['field_profile2_firstname']['id'] = 'field_profile2_firstname';
  $handler->display->display_options['fields']['field_profile2_firstname']['table'] = 'field_data_field_profile2_firstname';
  $handler->display->display_options['fields']['field_profile2_firstname']['field'] = 'field_profile2_firstname';
  $handler->display->display_options['fields']['field_profile2_firstname']['relationship'] = 'profile';
  $handler->display->display_options['fields']['field_profile2_firstname']['exclude'] = TRUE;
  $handler->display->display_options['fields']['field_profile2_firstname']['alter']['max_length'] = '1';
  $handler->display->display_options['fields']['field_profile2_firstname']['alter']['word_boundary'] = FALSE;
  $handler->display->display_options['fields']['field_profile2_firstname']['alter']['ellipsis'] = FALSE;
  $handler->display->display_options['fields']['field_profile2_firstname']['alter']['trim'] = TRUE;
  /* Field: Profile: Middle Name or Initial */
  $handler->display->display_options['fields']['field_profile2_middlename']['id'] = 'field_profile2_middlename';
  $handler->display->display_options['fields']['field_profile2_middlename']['table'] = 'field_data_field_profile2_middlename';
  $handler->display->display_options['fields']['field_profile2_middlename']['field'] = 'field_profile2_middlename';
  $handler->display->display_options['fields']['field_profile2_middlename']['relationship'] = 'profile';
  $handler->display->display_options['fields']['field_profile2_middlename']['exclude'] = TRUE;
  $handler->display->display_options['fields']['field_profile2_middlename']['alter']['max_length'] = '1';
  $handler->display->display_options['fields']['field_profile2_middlename']['alter']['word_boundary'] = FALSE;
  $handler->display->display_options['fields']['field_profile2_middlename']['alter']['ellipsis'] = FALSE;
  $handler->display->display_options['fields']['field_profile2_middlename']['alter']['trim'] = TRUE;
  /* Field: Profile: Last Name */
  $handler->display->display_options['fields']['field_profile2_lastname']['id'] = 'field_profile2_lastname';
  $handler->display->display_options['fields']['field_profile2_lastname']['table'] = 'field_data_field_profile2_lastname';
  $handler->display->display_options['fields']['field_profile2_lastname']['field'] = 'field_profile2_lastname';
  $handler->display->display_options['fields']['field_profile2_lastname']['relationship'] = 'profile';
  $handler->display->display_options['fields']['field_profile2_lastname']['label'] = 'Submitter';
  $handler->display->display_options['fields']['field_profile2_lastname']['alter']['alter_text'] = TRUE;
  $handler->display->display_options['fields']['field_profile2_lastname']['alter']['text'] = '[field_profile2_firstname][field_profile2_middlename] [field_profile2_lastname]';
  $handler->display->display_options['fields']['field_profile2_lastname']['empty'] = '[name]';
  $handler->display->display_options['fields']['field_profile2_lastname']['hide_alter_empty'] = FALSE;
  /* Field: Content: Post date */
  $handler->display->display_options['fields']['created']['id'] = 'created';
  $handler->display->display_options['fields']['created']['table'] = 'node';
  $handler->display->display_options['fields']['created']['field'] = 'created';
  $handler->display->display_options['fields']['created']['label'] = 'Submitted';
  $handler->display->display_options['fields']['created']['date_format'] = 'short_do';
  /* Sort criterion: Content: Title */
  $handler->display->display_options['sorts']['title']['id'] = 'title';
  $handler->display->display_options['sorts']['title']['table'] = 'node';
  $handler->display->display_options['sorts']['title']['field'] = 'title';
  /* Filter criterion: Content: Published */
  $handler->display->display_options['filters']['status']['id'] = 'status';
  $handler->display->display_options['filters']['status']['table'] = 'node';
  $handler->display->display_options['filters']['status']['field'] = 'status';
  $handler->display->display_options['filters']['status']['value'] = 1;
  $handler->display->display_options['filters']['status']['group'] = 1;
  $handler->display->display_options['filters']['status']['expose']['operator'] = FALSE;
  /* Filter criterion: Content: Type */
  $handler->display->display_options['filters']['type']['id'] = 'type';
  $handler->display->display_options['filters']['type']['table'] = 'node';
  $handler->display->display_options['filters']['type']['field'] = 'type';
  $handler->display->display_options['filters']['type']['value'] = array(
    'model' => 'model',
  );

  /* Display: Browse Models Page */
  $handler = $view->new_display('page', 'Browse Models Page', 'page');
  $handler->display->display_options['defaults']['hide_admin_links'] = FALSE;
  $handler->display->display_options['path'] = 'models';

  /* Display: User Models Page */
  $handler = $view->new_display('page', 'User Models Page', 'page_1');
  $handler->display->display_options['defaults']['title'] = FALSE;
  $handler->display->display_options['title'] = 'User\'s Models';
  $handler->display->display_options['defaults']['hide_admin_links'] = FALSE;
  $handler->display->display_options['defaults']['relationships'] = FALSE;
  $handler->display->display_options['defaults']['fields'] = FALSE;
  /* Field: Content: Nid */
  $handler->display->display_options['fields']['nid']['id'] = 'nid';
  $handler->display->display_options['fields']['nid']['table'] = 'node';
  $handler->display->display_options['fields']['nid']['field'] = 'nid';
  /* Field: Content: Title */
  $handler->display->display_options['fields']['title']['id'] = 'title';
  $handler->display->display_options['fields']['title']['table'] = 'node';
  $handler->display->display_options['fields']['title']['field'] = 'title';
  $handler->display->display_options['fields']['title']['label'] = '';
  $handler->display->display_options['fields']['title']['alter']['word_boundary'] = FALSE;
  $handler->display->display_options['fields']['title']['alter']['ellipsis'] = FALSE;
  /* Field: Content: Body */
  $handler->display->display_options['fields']['body']['id'] = 'body';
  $handler->display->display_options['fields']['body']['table'] = 'field_data_body';
  $handler->display->display_options['fields']['body']['field'] = 'body';
  $handler->display->display_options['fields']['body']['alter']['strip_tags'] = TRUE;
  $handler->display->display_options['fields']['body']['type'] = 'text_summary_or_trimmed';
  $handler->display->display_options['fields']['body']['settings'] = array(
    'trim_length' => '200',
  );
  /* Field: Content: Post date */
  $handler->display->display_options['fields']['created']['id'] = 'created';
  $handler->display->display_options['fields']['created']['table'] = 'node';
  $handler->display->display_options['fields']['created']['field'] = 'created';
  $handler->display->display_options['fields']['created']['date_format'] = 'short_do';
  /* Field: Content: Published */
  $handler->display->display_options['fields']['status']['id'] = 'status';
  $handler->display->display_options['fields']['status']['table'] = 'node';
  $handler->display->display_options['fields']['status']['field'] = 'status';
  $handler->display->display_options['fields']['status']['not'] = 0;
  $handler->display->display_options['defaults']['arguments'] = FALSE;
  /* Contextual filter: Content: Author uid */
  $handler->display->display_options['arguments']['uid']['id'] = 'uid';
  $handler->display->display_options['arguments']['uid']['table'] = 'node';
  $handler->display->display_options['arguments']['uid']['field'] = 'uid';
  $handler->display->display_options['arguments']['uid']['default_action'] = 'not found';
  $handler->display->display_options['arguments']['uid']['default_argument_type'] = 'fixed';
  $handler->display->display_options['arguments']['uid']['summary']['number_of_records'] = '0';
  $handler->display->display_options['arguments']['uid']['summary']['format'] = 'default_summary';
  $handler->display->display_options['arguments']['uid']['summary_options']['items_per_page'] = '25';
  $handler->display->display_options['defaults']['filter_groups'] = FALSE;
  $handler->display->display_options['defaults']['filters'] = FALSE;
  /* Filter criterion: Content: Type */
  $handler->display->display_options['filters']['type']['id'] = 'type';
  $handler->display->display_options['filters']['type']['table'] = 'node';
  $handler->display->display_options['filters']['type']['field'] = 'type';
  $handler->display->display_options['filters']['type']['value'] = array(
    'model' => 'model',
  );
  $handler->display->display_options['path'] = 'user/%/models';
  $handler->display->display_options['menu']['type'] = 'tab';
  $handler->display->display_options['menu']['title'] = 'User\'s Models';
  $handler->display->display_options['menu']['weight'] = '0';
  $handler->display->display_options['menu']['context'] = 0;

  /* Display: Featured Model pane */
  $handler = $view->new_display('panel_pane', 'Featured Model pane', 'panel_pane_1');
  $handler->display->display_options['defaults']['title'] = FALSE;
  $handler->display->display_options['title'] = 'Featured Model';
  $handler->display->display_options['defaults']['hide_admin_links'] = FALSE;
  $handler->display->display_options['defaults']['pager'] = FALSE;
  $handler->display->display_options['pager']['type'] = 'some';
  $handler->display->display_options['pager']['options']['items_per_page'] = '1';
  $handler->display->display_options['pager']['options']['offset'] = '0';
  $handler->display->display_options['defaults']['style_plugin'] = FALSE;
  $handler->display->display_options['style_plugin'] = 'default';
  $handler->display->display_options['style_options']['row_class_special'] = FALSE;
  $handler->display->display_options['defaults']['style_options'] = FALSE;
  $handler->display->display_options['defaults']['row_plugin'] = FALSE;
  $handler->display->display_options['row_plugin'] = 'fields';
  $handler->display->display_options['defaults']['row_options'] = FALSE;
  $handler->display->display_options['defaults']['footer'] = FALSE;
  /* Footer: Global: Text area */
  $handler->display->display_options['footer']['area']['id'] = 'area';
  $handler->display->display_options['footer']['area']['table'] = 'views';
  $handler->display->display_options['footer']['area']['field'] = 'area';
  $handler->display->display_options['footer']['area']['content'] = '<span></span>';
  $handler->display->display_options['footer']['area']['format'] = '3';
  $handler->display->display_options['defaults']['relationships'] = FALSE;
  $handler->display->display_options['defaults']['fields'] = FALSE;
  /* Field: Content: Nid */
  $handler->display->display_options['fields']['nid']['id'] = 'nid';
  $handler->display->display_options['fields']['nid']['table'] = 'node';
  $handler->display->display_options['fields']['nid']['field'] = 'nid';
  /* Field: Content: Title */
  $handler->display->display_options['fields']['title']['id'] = 'title';
  $handler->display->display_options['fields']['title']['table'] = 'node';
  $handler->display->display_options['fields']['title']['field'] = 'title';
  $handler->display->display_options['fields']['title']['label'] = '';
  $handler->display->display_options['fields']['title']['alter']['make_link'] = TRUE;
  $handler->display->display_options['fields']['title']['alter']['path'] = 'model/[nid]';
  $handler->display->display_options['fields']['title']['alter']['word_boundary'] = FALSE;
  $handler->display->display_options['fields']['title']['alter']['ellipsis'] = FALSE;
  $handler->display->display_options['fields']['title']['element_label_colon'] = FALSE;
  $handler->display->display_options['fields']['title']['link_to_node'] = FALSE;
  /* Field: Content: Body */
  $handler->display->display_options['fields']['body']['id'] = 'body';
  $handler->display->display_options['fields']['body']['table'] = 'field_data_body';
  $handler->display->display_options['fields']['body']['field'] = 'body';
  $handler->display->display_options['fields']['body']['alter']['strip_tags'] = TRUE;
  $handler->display->display_options['fields']['body']['type'] = 'text_summary_or_trimmed';
  $handler->display->display_options['fields']['body']['settings'] = array(
    'trim_length' => '200',
  );
  /* Field: Content: Post date */
  $handler->display->display_options['fields']['created']['id'] = 'created';
  $handler->display->display_options['fields']['created']['table'] = 'node';
  $handler->display->display_options['fields']['created']['field'] = 'created';
  $handler->display->display_options['fields']['created']['date_format'] = 'short_do';
  /* Field: Content: Image */
  $handler->display->display_options['fields']['field_model_image']['id'] = 'field_model_image';
  $handler->display->display_options['fields']['field_model_image']['table'] = 'field_data_field_model_image';
  $handler->display->display_options['fields']['field_model_image']['field'] = 'field_model_image';
  $handler->display->display_options['fields']['field_model_image']['label'] = '';
  $handler->display->display_options['fields']['field_model_image']['alter']['make_link'] = TRUE;
  $handler->display->display_options['fields']['field_model_image']['alter']['path'] = 'model/[nid]';
  $handler->display->display_options['fields']['field_model_image']['element_label_colon'] = FALSE;
  $handler->display->display_options['fields']['field_model_image']['click_sort_column'] = 'fid';
  $handler->display->display_options['fields']['field_model_image']['settings'] = array(
    'image_style' => 'thumbnail',
    'image_link' => '',
  );
  $handler->display->display_options['defaults']['sorts'] = FALSE;
  /* Sort criterion: Global: Random */
  $handler->display->display_options['sorts']['random']['id'] = 'random';
  $handler->display->display_options['sorts']['random']['table'] = 'views';
  $handler->display->display_options['sorts']['random']['field'] = 'random';
  $handler->display->display_options['defaults']['filter_groups'] = FALSE;
  $handler->display->display_options['defaults']['filters'] = FALSE;
  /* Filter criterion: Content: Published */
  $handler->display->display_options['filters']['status']['id'] = 'status';
  $handler->display->display_options['filters']['status']['table'] = 'node';
  $handler->display->display_options['filters']['status']['field'] = 'status';
  $handler->display->display_options['filters']['status']['value'] = 1;
  $handler->display->display_options['filters']['status']['group'] = 1;
  $handler->display->display_options['filters']['status']['expose']['operator'] = FALSE;
  /* Filter criterion: Content: Type */
  $handler->display->display_options['filters']['type']['id'] = 'type';
  $handler->display->display_options['filters']['type']['table'] = 'node';
  $handler->display->display_options['filters']['type']['field'] = 'type';
  $handler->display->display_options['filters']['type']['value'] = array(
    'model' => 'model',
  );
  /* Filter criterion: Content: Featured Model? (field_model_featured) */
  $handler->display->display_options['filters']['field_model_featured_value']['id'] = 'field_model_featured_value';
  $handler->display->display_options['filters']['field_model_featured_value']['table'] = 'field_data_field_model_featured';
  $handler->display->display_options['filters']['field_model_featured_value']['field'] = 'field_model_featured_value';
  $handler->display->display_options['filters']['field_model_featured_value']['value'] = array(
    1 => '1',
  );

  /* Display: Models by Keyword Page */
  $handler = $view->new_display('page', 'Models by Keyword Page', 'page_2');
  $handler->display->display_options['defaults']['title'] = FALSE;
  $handler->display->display_options['title'] = 'Models by Keyword';
  $handler->display->display_options['defaults']['hide_admin_links'] = FALSE;
  $handler->display->display_options['defaults']['arguments'] = FALSE;
  /* Contextual filter: Content: Keywords (taxonomy_vocabulary_6) */
  $handler->display->display_options['arguments']['taxonomy_vocabulary_6_tid']['id'] = 'taxonomy_vocabulary_6_tid';
  $handler->display->display_options['arguments']['taxonomy_vocabulary_6_tid']['table'] = 'field_data_taxonomy_vocabulary_6';
  $handler->display->display_options['arguments']['taxonomy_vocabulary_6_tid']['field'] = 'taxonomy_vocabulary_6_tid';
  $handler->display->display_options['arguments']['taxonomy_vocabulary_6_tid']['default_action'] = 'not found';
  $handler->display->display_options['arguments']['taxonomy_vocabulary_6_tid']['default_argument_type'] = 'fixed';
  $handler->display->display_options['arguments']['taxonomy_vocabulary_6_tid']['summary']['number_of_records'] = '0';
  $handler->display->display_options['arguments']['taxonomy_vocabulary_6_tid']['summary']['format'] = 'default_summary';
  $handler->display->display_options['arguments']['taxonomy_vocabulary_6_tid']['summary_options']['items_per_page'] = '25';
  $handler->display->display_options['path'] = 'models/%/keyword';

  /* Display: Models Feed */
  $handler = $view->new_display('feed', 'Models Feed', 'feed_1');
  $handler->display->display_options['defaults']['title'] = FALSE;
  $handler->display->display_options['title'] = 'OpenABM Newest Models';
  $handler->display->display_options['defaults']['hide_admin_links'] = FALSE;
  $handler->display->display_options['pager']['type'] = 'some';
  $handler->display->display_options['style_plugin'] = 'rss';
  $handler->display->display_options['row_plugin'] = 'node_rss';
  $handler->display->display_options['defaults']['sorts'] = FALSE;
  /* Sort criterion: Content: Post date */
  $handler->display->display_options['sorts']['created']['id'] = 'created';
  $handler->display->display_options['sorts']['created']['table'] = 'node';
  $handler->display->display_options['sorts']['created']['field'] = 'created';
  $handler->display->display_options['sorts']['created']['order'] = 'DESC';
  $handler->display->display_options['path'] = 'models/models.xml';
  $handler->display->display_options['sitename_title'] = 0;

  /* Display: View Model Page */
  $handler = $view->new_display('page', 'View Model Page', 'page_3');
  $handler->display->display_options['defaults']['title'] = FALSE;
  $handler->display->display_options['defaults']['hide_admin_links'] = FALSE;
  $handler->display->display_options['defaults']['pager'] = FALSE;
  $handler->display->display_options['pager']['type'] = 'none';
  $handler->display->display_options['pager']['options']['offset'] = '0';
  $handler->display->display_options['defaults']['style_plugin'] = FALSE;
  $handler->display->display_options['style_plugin'] = 'default';
  $handler->display->display_options['style_options']['row_class_special'] = FALSE;
  $handler->display->display_options['defaults']['style_options'] = FALSE;
  $handler->display->display_options['defaults']['row_plugin'] = FALSE;
  $handler->display->display_options['row_plugin'] = 'fields';
  $handler->display->display_options['defaults']['row_options'] = FALSE;
  $handler->display->display_options['defaults']['relationships'] = FALSE;
  /* Relationship: Content: Model (field_modelversion_model) */
  $handler->display->display_options['relationships']['field_modelversion_model_nid']['id'] = 'field_modelversion_model_nid';
  $handler->display->display_options['relationships']['field_modelversion_model_nid']['table'] = 'field_data_field_modelversion_model';
  $handler->display->display_options['relationships']['field_modelversion_model_nid']['field'] = 'field_modelversion_model_nid';
  $handler->display->display_options['relationships']['field_modelversion_model_nid']['label'] = 'Model';
  $handler->display->display_options['relationships']['field_modelversion_model_nid']['required'] = TRUE;
  $handler->display->display_options['relationships']['field_modelversion_model_nid']['delta'] = '-1';
  /* Relationship: Content: Author */
  $handler->display->display_options['relationships']['uid']['id'] = 'uid';
  $handler->display->display_options['relationships']['uid']['table'] = 'node';
  $handler->display->display_options['relationships']['uid']['field'] = 'uid';
  $handler->display->display_options['relationships']['uid']['relationship'] = 'field_modelversion_model_nid';
  $handler->display->display_options['relationships']['uid']['label'] = 'Submitter';
  $handler->display->display_options['defaults']['fields'] = FALSE;
  /* Field: Content: Nid */
  $handler->display->display_options['fields']['nid']['id'] = 'nid';
  $handler->display->display_options['fields']['nid']['table'] = 'node';
  $handler->display->display_options['fields']['nid']['field'] = 'nid';
  $handler->display->display_options['fields']['nid']['element_type'] = '0';
  $handler->display->display_options['fields']['nid']['element_wrapper_type'] = '0';
  /* Field: Content: Model URI */
  $handler->display->display_options['fields']['field_modelversion_uri']['id'] = 'field_modelversion_uri';
  $handler->display->display_options['fields']['field_modelversion_uri']['table'] = 'field_data_field_modelversion_uri';
  $handler->display->display_options['fields']['field_modelversion_uri']['field'] = 'field_modelversion_uri';
  $handler->display->display_options['fields']['field_modelversion_uri']['element_type'] = '0';
  $handler->display->display_options['fields']['field_modelversion_uri']['element_wrapper_type'] = '0';
  /* Field: Content: Title */
  $handler->display->display_options['fields']['title']['id'] = 'title';
  $handler->display->display_options['fields']['title']['table'] = 'node';
  $handler->display->display_options['fields']['title']['field'] = 'title';
  $handler->display->display_options['fields']['title']['relationship'] = 'field_modelversion_model_nid';
  $handler->display->display_options['fields']['title']['element_type'] = '0';
  $handler->display->display_options['fields']['title']['element_wrapper_type'] = '0';
  $handler->display->display_options['fields']['title']['link_to_node'] = FALSE;
  /* Field: Content: Body */
  $handler->display->display_options['fields']['body']['id'] = 'body';
  $handler->display->display_options['fields']['body']['table'] = 'field_data_body';
  $handler->display->display_options['fields']['body']['field'] = 'body';
  $handler->display->display_options['fields']['body']['relationship'] = 'field_modelversion_model_nid';
  $handler->display->display_options['fields']['body']['label'] = 'Abstract';
  $handler->display->display_options['fields']['body']['alter']['strip_tags'] = TRUE;
  $handler->display->display_options['fields']['body']['settings'] = array(
    'trim_length' => '200',
  );
  /* Field: Content: Version Number */
  $handler->display->display_options['fields']['field_modelversion_number']['id'] = 'field_modelversion_number';
  $handler->display->display_options['fields']['field_modelversion_number']['table'] = 'field_data_field_modelversion_number';
  $handler->display->display_options['fields']['field_modelversion_number']['field'] = 'field_modelversion_number';
  $handler->display->display_options['fields']['field_modelversion_number']['element_type'] = '0';
  $handler->display->display_options['fields']['field_modelversion_number']['element_wrapper_type'] = '0';
  $handler->display->display_options['fields']['field_modelversion_number']['element_default_classes'] = FALSE;
  $handler->display->display_options['fields']['field_modelversion_number']['settings'] = array(
    'thousand_separator' => '',
    'prefix_suffix' => 1,
  );
  /* Field: Content: Post date */
  $handler->display->display_options['fields']['created']['id'] = 'created';
  $handler->display->display_options['fields']['created']['table'] = 'node';
  $handler->display->display_options['fields']['created']['field'] = 'created';
  $handler->display->display_options['fields']['created']['date_format'] = 'medium_do';
  /* Field: Content: Updated date */
  $handler->display->display_options['fields']['changed']['id'] = 'changed';
  $handler->display->display_options['fields']['changed']['table'] = 'node';
  $handler->display->display_options['fields']['changed']['field'] = 'changed';
  $handler->display->display_options['fields']['changed']['label'] = 'Last Updated';
  $handler->display->display_options['fields']['changed']['date_format'] = 'medium_do';
  /* Field: Content: Published */
  $handler->display->display_options['fields']['status']['id'] = 'status';
  $handler->display->display_options['fields']['status']['table'] = 'node';
  $handler->display->display_options['fields']['status']['field'] = 'status';
  $handler->display->display_options['fields']['status']['element_type'] = '0';
  $handler->display->display_options['fields']['status']['element_wrapper_type'] = '0';
  $handler->display->display_options['fields']['status']['not'] = 0;
  /* Field: User: Uid */
  $handler->display->display_options['fields']['uid']['id'] = 'uid';
  $handler->display->display_options['fields']['uid']['table'] = 'users';
  $handler->display->display_options['fields']['uid']['field'] = 'uid';
  $handler->display->display_options['fields']['uid']['relationship'] = 'uid';
  $handler->display->display_options['fields']['uid']['label'] = '';
  $handler->display->display_options['fields']['uid']['element_type'] = '0';
  $handler->display->display_options['fields']['uid']['element_label_colon'] = FALSE;
  $handler->display->display_options['fields']['uid']['element_wrapper_type'] = '0';
  $handler->display->display_options['fields']['uid']['element_default_classes'] = FALSE;
  $handler->display->display_options['fields']['uid']['link_to_user'] = FALSE;
  /* Field: User: Name */
  $handler->display->display_options['fields']['name']['id'] = 'name';
  $handler->display->display_options['fields']['name']['table'] = 'users';
  $handler->display->display_options['fields']['name']['field'] = 'name';
  $handler->display->display_options['fields']['name']['relationship'] = 'uid';
  $handler->display->display_options['fields']['name']['label'] = 'Submitter username';
  /* Field: Content: Body */
  $handler->display->display_options['fields']['body_1']['id'] = 'body_1';
  $handler->display->display_options['fields']['body_1']['table'] = 'field_data_body';
  $handler->display->display_options['fields']['body_1']['field'] = 'body';
  $handler->display->display_options['fields']['body_1']['label'] = 'Version Notes';
  /* Field: Content: Additional Files */
  $handler->display->display_options['fields']['field_modelversion_addfiles']['id'] = 'field_modelversion_addfiles';
  $handler->display->display_options['fields']['field_modelversion_addfiles']['table'] = 'field_data_field_modelversion_addfiles';
  $handler->display->display_options['fields']['field_modelversion_addfiles']['field'] = 'field_modelversion_addfiles';
  $handler->display->display_options['fields']['field_modelversion_addfiles']['element_type'] = 'span';
  $handler->display->display_options['fields']['field_modelversion_addfiles']['hide_empty'] = TRUE;
  $handler->display->display_options['fields']['field_modelversion_addfiles']['click_sort_column'] = 'fid';
  /* Field: Content: Code */
  $handler->display->display_options['fields']['field_modelversion_code']['id'] = 'field_modelversion_code';
  $handler->display->display_options['fields']['field_modelversion_code']['table'] = 'field_data_field_modelversion_code';
  $handler->display->display_options['fields']['field_modelversion_code']['field'] = 'field_modelversion_code';
  $handler->display->display_options['fields']['field_modelversion_code']['element_type'] = 'span';
  $handler->display->display_options['fields']['field_modelversion_code']['click_sort_column'] = 'fid';
  /* Field: Content: Dataset */
  $handler->display->display_options['fields']['field_modelversion_dataset']['id'] = 'field_modelversion_dataset';
  $handler->display->display_options['fields']['field_modelversion_dataset']['table'] = 'field_data_field_modelversion_dataset';
  $handler->display->display_options['fields']['field_modelversion_dataset']['field'] = 'field_modelversion_dataset';
  $handler->display->display_options['fields']['field_modelversion_dataset']['element_type'] = 'span';
  $handler->display->display_options['fields']['field_modelversion_dataset']['hide_empty'] = TRUE;
  $handler->display->display_options['fields']['field_modelversion_dataset']['click_sort_column'] = 'fid';
  /* Field: Content: Documentation */
  $handler->display->display_options['fields']['field_modelversion_documentation']['id'] = 'field_modelversion_documentation';
  $handler->display->display_options['fields']['field_modelversion_documentation']['table'] = 'field_data_field_modelversion_documentation';
  $handler->display->display_options['fields']['field_modelversion_documentation']['field'] = 'field_modelversion_documentation';
  $handler->display->display_options['fields']['field_modelversion_documentation']['element_type'] = 'span';
  $handler->display->display_options['fields']['field_modelversion_documentation']['click_sort_column'] = 'fid';
  /* Field: Content: Software License */
  $handler->display->display_options['fields']['field_modelversion_license']['id'] = 'field_modelversion_license';
  $handler->display->display_options['fields']['field_modelversion_license']['table'] = 'field_data_field_modelversion_license';
  $handler->display->display_options['fields']['field_modelversion_license']['field'] = 'field_modelversion_license';
  $handler->display->display_options['fields']['field_modelversion_license']['element_type'] = '0';
  $handler->display->display_options['fields']['field_modelversion_license']['element_wrapper_type'] = '0';
  /* Field: Content: Operating System */
  $handler->display->display_options['fields']['field_modelversion_os']['id'] = 'field_modelversion_os';
  $handler->display->display_options['fields']['field_modelversion_os']['table'] = 'field_data_field_modelversion_os';
  $handler->display->display_options['fields']['field_modelversion_os']['field'] = 'field_modelversion_os';
  $handler->display->display_options['fields']['field_modelversion_os']['element_type'] = '0';
  $handler->display->display_options['fields']['field_modelversion_os']['element_wrapper_type'] = '0';
  /* Field: Content: Modeling Platform */
  $handler->display->display_options['fields']['field_modelversion_platform']['id'] = 'field_modelversion_platform';
  $handler->display->display_options['fields']['field_modelversion_platform']['table'] = 'field_data_field_modelversion_platform';
  $handler->display->display_options['fields']['field_modelversion_platform']['field'] = 'field_modelversion_platform';
  $handler->display->display_options['fields']['field_modelversion_platform']['element_type'] = '0';
  $handler->display->display_options['fields']['field_modelversion_platform']['element_wrapper_type'] = '0';
  /* Field: Content: Programming Language */
  $handler->display->display_options['fields']['field_modelversion_language']['id'] = 'field_modelversion_language';
  $handler->display->display_options['fields']['field_modelversion_language']['table'] = 'field_data_field_modelversion_language';
  $handler->display->display_options['fields']['field_modelversion_language']['field'] = 'field_modelversion_language';
  $handler->display->display_options['fields']['field_modelversion_language']['element_type'] = '0';
  $handler->display->display_options['fields']['field_modelversion_language']['element_wrapper_type'] = '0';
  /* Field: Content: Run Conditions */
  $handler->display->display_options['fields']['field_modelversion_runconditions']['id'] = 'field_modelversion_runconditions';
  $handler->display->display_options['fields']['field_modelversion_runconditions']['table'] = 'field_data_field_modelversion_runconditions';
  $handler->display->display_options['fields']['field_modelversion_runconditions']['field'] = 'field_modelversion_runconditions';
  /* Field: Content: Sensitivity */
  $handler->display->display_options['fields']['field_modelversion_sensitivity']['id'] = 'field_modelversion_sensitivity';
  $handler->display->display_options['fields']['field_modelversion_sensitivity']['table'] = 'field_data_field_modelversion_sensitivity';
  $handler->display->display_options['fields']['field_modelversion_sensitivity']['field'] = 'field_modelversion_sensitivity';
  $handler->display->display_options['fields']['field_modelversion_sensitivity']['element_type'] = 'span';
  $handler->display->display_options['fields']['field_modelversion_sensitivity']['hide_empty'] = TRUE;
  $handler->display->display_options['fields']['field_modelversion_sensitivity']['click_sort_column'] = 'fid';
  /* Field: Content: Handle */
  $handler->display->display_options['fields']['field_model_handle']['id'] = 'field_model_handle';
  $handler->display->display_options['fields']['field_model_handle']['table'] = 'field_data_field_model_handle';
  $handler->display->display_options['fields']['field_model_handle']['field'] = 'field_model_handle';
  $handler->display->display_options['fields']['field_model_handle']['relationship'] = 'field_modelversion_model_nid';
  $handler->display->display_options['fields']['field_model_handle']['alter']['alter_text'] = TRUE;
  $handler->display->display_options['fields']['field_model_handle']['alter']['text'] = 'http://hdl.handle.net/[field_model_handle-value]';
  $handler->display->display_options['fields']['field_model_handle']['element_type'] = '0';
  /* Field: Content: Image */
  $handler->display->display_options['fields']['field_model_image']['id'] = 'field_model_image';
  $handler->display->display_options['fields']['field_model_image']['table'] = 'field_data_field_model_image';
  $handler->display->display_options['fields']['field_model_image']['field'] = 'field_model_image';
  $handler->display->display_options['fields']['field_model_image']['relationship'] = 'field_modelversion_model_nid';
  $handler->display->display_options['fields']['field_model_image']['click_sort_column'] = 'fid';
  $handler->display->display_options['fields']['field_model_image']['type'] = 'shadowbox';
  $handler->display->display_options['fields']['field_model_image']['settings'] = array(
    'image_style' => 'model',
    'image_link' => '',
    'gallery' => '',
    'compact' => 0,
    'title' => '',
  );
  /* Field: Content: Model Publication */
  $handler->display->display_options['fields']['field_model_publication_text']['id'] = 'field_model_publication_text';
  $handler->display->display_options['fields']['field_model_publication_text']['table'] = 'field_data_field_model_publication_text';
  $handler->display->display_options['fields']['field_model_publication_text']['field'] = 'field_model_publication_text';
  $handler->display->display_options['fields']['field_model_publication_text']['relationship'] = 'field_modelversion_model_nid';
  /* Field: Content: Replicated Model? */
  $handler->display->display_options['fields']['field_model_replicated']['id'] = 'field_model_replicated';
  $handler->display->display_options['fields']['field_model_replicated']['table'] = 'field_data_field_model_replicated';
  $handler->display->display_options['fields']['field_model_replicated']['field'] = 'field_model_replicated';
  $handler->display->display_options['fields']['field_model_replicated']['relationship'] = 'field_modelversion_model_nid';
  $handler->display->display_options['fields']['field_model_replicated']['alter']['strip_tags'] = TRUE;
  $handler->display->display_options['fields']['field_model_replicated']['element_type'] = '0';
  $handler->display->display_options['fields']['field_model_replicated']['element_default_classes'] = FALSE;
  $handler->display->display_options['fields']['field_model_replicated']['type'] = 'list_key';
  /* Field: Content: Replicated Reference */
  $handler->display->display_options['fields']['field_model_reference']['id'] = 'field_model_reference';
  $handler->display->display_options['fields']['field_model_reference']['table'] = 'field_data_field_model_reference';
  $handler->display->display_options['fields']['field_model_reference']['field'] = 'field_model_reference';
  $handler->display->display_options['fields']['field_model_reference']['relationship'] = 'field_modelversion_model_nid';
  /* Field: Content: Video */
  $handler->display->display_options['fields']['field_model_video']['id'] = 'field_model_video';
  $handler->display->display_options['fields']['field_model_video']['table'] = 'field_data_field_model_video';
  $handler->display->display_options['fields']['field_model_video']['field'] = 'field_model_video';
  $handler->display->display_options['fields']['field_model_video']['relationship'] = 'field_modelversion_model_nid';
  $handler->display->display_options['fields']['field_model_video']['element_type'] = '0';
  $handler->display->display_options['fields']['field_model_video']['element_wrapper_type'] = '0';
  $handler->display->display_options['fields']['field_model_video']['element_default_classes'] = FALSE;
  $handler->display->display_options['fields']['field_model_video']['click_sort_column'] = 'fid';
  $handler->display->display_options['fields']['field_model_video']['type'] = 'file_url_plain';
  $handler->display->display_options['fields']['field_model_video']['settings'] = array(
    'jwplayer_preset' => 'model_video',
  );
  /* Field: Content: Language Version */
  $handler->display->display_options['fields']['field_modelversion_language_ver']['id'] = 'field_modelversion_language_ver';
  $handler->display->display_options['fields']['field_modelversion_language_ver']['table'] = 'field_data_field_modelversion_language_ver';
  $handler->display->display_options['fields']['field_modelversion_language_ver']['field'] = 'field_modelversion_language_ver';
  $handler->display->display_options['fields']['field_modelversion_language_ver']['label'] = '';
  $handler->display->display_options['fields']['field_modelversion_language_ver']['element_label_colon'] = FALSE;
  $handler->display->display_options['fields']['field_modelversion_language_ver']['type'] = 'text_plain';
  /* Field: Content: OS Version */
  $handler->display->display_options['fields']['field_modelversion_os_version']['id'] = 'field_modelversion_os_version';
  $handler->display->display_options['fields']['field_modelversion_os_version']['table'] = 'field_data_field_modelversion_os_version';
  $handler->display->display_options['fields']['field_modelversion_os_version']['field'] = 'field_modelversion_os_version';
  $handler->display->display_options['fields']['field_modelversion_os_version']['label'] = '';
  $handler->display->display_options['fields']['field_modelversion_os_version']['element_label_colon'] = FALSE;
  $handler->display->display_options['fields']['field_modelversion_os_version']['type'] = 'text_plain';
  /* Field: Content: Other Language */
  $handler->display->display_options['fields']['field_modelversion_language_oth']['id'] = 'field_modelversion_language_oth';
  $handler->display->display_options['fields']['field_modelversion_language_oth']['table'] = 'field_data_field_modelversion_language_oth';
  $handler->display->display_options['fields']['field_modelversion_language_oth']['field'] = 'field_modelversion_language_oth';
  $handler->display->display_options['fields']['field_modelversion_language_oth']['label'] = '';
  $handler->display->display_options['fields']['field_modelversion_language_oth']['element_label_colon'] = FALSE;
  $handler->display->display_options['fields']['field_modelversion_language_oth']['type'] = 'text_plain';
  /* Field: Content: Other Platform */
  $handler->display->display_options['fields']['field_modelversion_platform_oth']['id'] = 'field_modelversion_platform_oth';
  $handler->display->display_options['fields']['field_modelversion_platform_oth']['table'] = 'field_data_field_modelversion_platform_oth';
  $handler->display->display_options['fields']['field_modelversion_platform_oth']['field'] = 'field_modelversion_platform_oth';
  $handler->display->display_options['fields']['field_modelversion_platform_oth']['label'] = '';
  $handler->display->display_options['fields']['field_modelversion_platform_oth']['element_label_colon'] = FALSE;
  $handler->display->display_options['fields']['field_modelversion_platform_oth']['type'] = 'text_plain';
  /* Field: Content: Platform Version */
  $handler->display->display_options['fields']['field_modelversion_platform_ver']['id'] = 'field_modelversion_platform_ver';
  $handler->display->display_options['fields']['field_modelversion_platform_ver']['table'] = 'field_data_field_modelversion_platform_ver';
  $handler->display->display_options['fields']['field_modelversion_platform_ver']['field'] = 'field_modelversion_platform_ver';
  $handler->display->display_options['fields']['field_modelversion_platform_ver']['label'] = '';
  $handler->display->display_options['fields']['field_modelversion_platform_ver']['element_label_colon'] = FALSE;
  $handler->display->display_options['fields']['field_modelversion_platform_ver']['type'] = 'text_plain';
  /* Field: Content: Model */
  $handler->display->display_options['fields']['field_modelversion_model']['id'] = 'field_modelversion_model';
  $handler->display->display_options['fields']['field_modelversion_model']['table'] = 'field_data_field_modelversion_model';
  $handler->display->display_options['fields']['field_modelversion_model']['field'] = 'field_modelversion_model';
  $handler->display->display_options['fields']['field_modelversion_model']['label'] = '';
  $handler->display->display_options['fields']['field_modelversion_model']['element_type'] = '0';
  $handler->display->display_options['fields']['field_modelversion_model']['element_label_colon'] = FALSE;
  $handler->display->display_options['fields']['field_modelversion_model']['element_default_classes'] = FALSE;
  $handler->display->display_options['fields']['field_modelversion_model']['type'] = 'node_reference_nid';
  $handler->display->display_options['defaults']['sorts'] = FALSE;
  $handler->display->display_options['defaults']['arguments'] = FALSE;
  /* Contextual filter: Content: Model (field_modelversion_model) */
  $handler->display->display_options['arguments']['field_modelversion_model_nid']['id'] = 'field_modelversion_model_nid';
  $handler->display->display_options['arguments']['field_modelversion_model_nid']['table'] = 'field_data_field_modelversion_model';
  $handler->display->display_options['arguments']['field_modelversion_model_nid']['field'] = 'field_modelversion_model_nid';
  $handler->display->display_options['arguments']['field_modelversion_model_nid']['default_action'] = 'not found';
  $handler->display->display_options['arguments']['field_modelversion_model_nid']['default_argument_type'] = 'fixed';
  $handler->display->display_options['arguments']['field_modelversion_model_nid']['summary']['number_of_records'] = '0';
  $handler->display->display_options['arguments']['field_modelversion_model_nid']['summary']['format'] = 'default_summary';
  $handler->display->display_options['arguments']['field_modelversion_model_nid']['summary_options']['items_per_page'] = '25';
  /* Contextual filter: Content: Version Number (field_modelversion_number) */
  $handler->display->display_options['arguments']['field_modelversion_number_value']['id'] = 'field_modelversion_number_value';
  $handler->display->display_options['arguments']['field_modelversion_number_value']['table'] = 'field_data_field_modelversion_number';
  $handler->display->display_options['arguments']['field_modelversion_number_value']['field'] = 'field_modelversion_number_value';
  $handler->display->display_options['arguments']['field_modelversion_number_value']['default_action'] = 'not found';
  $handler->display->display_options['arguments']['field_modelversion_number_value']['default_argument_type'] = 'fixed';
  $handler->display->display_options['arguments']['field_modelversion_number_value']['summary']['number_of_records'] = '0';
  $handler->display->display_options['arguments']['field_modelversion_number_value']['summary']['format'] = 'default_summary';
  $handler->display->display_options['arguments']['field_modelversion_number_value']['summary_options']['items_per_page'] = '25';
  $handler->display->display_options['defaults']['filter_groups'] = FALSE;
  $handler->display->display_options['defaults']['filters'] = FALSE;
  /* Filter criterion: Content: Type */
  $handler->display->display_options['filters']['type']['id'] = 'type';
  $handler->display->display_options['filters']['type']['table'] = 'node';
  $handler->display->display_options['filters']['type']['field'] = 'type';
  $handler->display->display_options['filters']['type']['value'] = array(
    'modelversion' => 'modelversion',
  );
  $handler->display->display_options['path'] = 'model/%/version/%/view';
  $handler->display->display_options['menu']['type'] = 'default tab';
  $handler->display->display_options['menu']['title'] = 'View';
  $handler->display->display_options['menu']['weight'] = '-10';
  $handler->display->display_options['menu']['context'] = 0;
  $handler->display->display_options['tab_options']['type'] = 'normal';
  $handler->display->display_options['tab_options']['title'] = 'View Model';
  $handler->display->display_options['tab_options']['weight'] = '0';
  $translatables['model_library'] = array(
    t('Master'),
    t('CoMSES Computational Model Library'),
    t('more'),
    t('Apply'),
    t('Reset'),
    t('Sort by'),
    t('Asc'),
    t('Desc'),
    t('Items per page'),
    t('- All -'),
    t('Offset'),
    t('« first'),
    t('‹ previous'),
    t('next ›'),
    t('last »'),
    t('Submitter'),
    t('Profile'),
    t('Nid'),
    t('Title'),
    t('Body'),
    t('First Name or Initial'),
    t('Middle Name or Initial'),
    t('[field_profile2_firstname][field_profile2_middlename] [field_profile2_lastname]'),
    t('[name]'),
    t('Submitted'),
    t('Browse Models Page'),
    t('User Models Page'),
    t('User\'s Models'),
    t('Post date'),
    t('Published'),
    t('All'),
    t('Featured Model pane'),
    t('Featured Model'),
    t('<span></span>'),
    t('View panes'),
    t('Models by Keyword Page'),
    t('Models by Keyword'),
    t('Models Feed'),
    t('OpenABM Newest Models'),
    t('View Model Page'),
    t('Model'),
    t('Model URI'),
    t('Abstract'),
    t('Version Number'),
    t('Last Updated'),
    t('Submitter username'),
    t('Version Notes'),
    t('Additional Files'),
    t('Code'),
    t('Dataset'),
    t('Documentation'),
    t('Software License'),
    t('Operating System'),
    t('Modeling Platform'),
    t('Programming Language'),
    t('Run Conditions'),
    t('Sensitivity'),
    t('Handle'),
    t('http://hdl.handle.net/[field_model_handle-value]'),
    t('Image'),
    t('Model Publication'),
    t('Replicated Model?'),
    t('Replicated Reference'),
    t('Video'),
  );

  $views[$view->name] = $view;

  return $views;
}


