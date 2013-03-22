<?php

/**
 * Page callback: Handles multistep precessing.
 *
 * @return string
 *   Multistep wizard output.
 *
 * @see modellibrary_menu()
 */
function modellibrary_wizard($js = NULL, $step = NULL) {
  if ($js) {
    ctools_include('modal');
    ctools_include('ajax');
  }

  if (arg(2) == 'add')
    $action = 'add';
  else {
    $action = 'edit';
    $modelnid = arg(1); 
  }

  // Define array for ctools multistep wizard.
  $form_info = array(
    'id' => 'modellibrary',
    'path' => "model/" . ($action == 'add' ? (($js ? 'ajax' : 'nojs') . '/add/%step') : $modelnid . '/' . ($js ? 'ajax' : 'nojs') . '/edit/%step'),
    'action' => $action, // custom addition
    'show trail' => TRUE,
    'show back' => FALSE,
    'show cancel' => TRUE,
    'show return' => FALSE,
    'next callback' =>  'modellibrary_wizard_next',
    'finish callback' => 'modellibrary_wizard_finish',
    'cancel callback' => 'modellibrary_wizard_cancel',

   // Define forms order.
    'order' => array(
      'step1' => t('Step 1'),
      'step2' => t('Step 2'),
      'step3' => t('Step 3'),
      'step4' => t('Step 4'),
      'step5' => t('Step 5'),
      'step6' => t('Step 6'),
    ),

   // Define forms
    'forms' => array(
      'step1' => array(
        'form id' => 'modellibrary_wizard_step1'
      ),
      'step2' => array(
        'form id' => 'modellibrary_wizard_step2'
      ),
      'step3' => array(
        'form id' => 'modellibrary_wizard_step3'
      ),
      'step4' => array(
        'form id' => 'modellibrary_wizard_step4'
      ),
      'step5' => array(
        'form id' => 'modellibrary_wizard_step5'
      ),
      'step6' => array(
        'form id' => 'modellibrary_wizard_step6'
      ),
    ),
  );

  // We're not using any real storage here, so we're going to set our
  // object_id to 1. When using wizard forms, id management turns
  // out to be one of the hardest parts. Editing an object with an id
  // is easy, but new objects don't usually have ids until somewhere
  // in creation.
  //
  // We skip all this here by just using an id of 1.
  $object_id = 1;

  if (empty($step)) {

    // We reset the form when $step is NULL because that means they have
    // for whatever reason started over.
    modellibrary_cache_clear($object_id);
    $step = 'step1';
  }

  // This automatically gets defaults if there wasn't anything saved.
  $object = modellibrary_cache_get($object_id);

  // live $form_state changes.
  $form_state = array(
    'ajax' => $js,

    // Put our object and ID into the form state cache so we can easily find it.
    'object_id' => $object_id,
    'object' => &$object,
  );

  // Send this all off to our form. This is like drupal_get_form only wizardy.
  ctools_include('wizard');
  $form = ctools_wizard_multistep_form($form_info, $step, $form_state);
  $output = drupal_render($form);
  if ($js) {

    // If javascript is active, we have to use a render array.
    $commands = array();
    if ($output === FALSE || !empty($form_state['complete'])) {
      // Dismiss the modal.
      $commands[] = ajax_command_html('#ctools-sample', render(modellibrary_get_result($object)));
      $commands[] = ctools_modal_command_dismiss();
    }
    elseif (!empty($form_state['cancel'])) {

      // If cancelling, return to the activity.
      $commands[] = ctools_modal_command_dismiss();
    }
    else {
      $commands = ctools_modal_form_render($form_state, $output);
    }
    print ajax_render($commands);
  }
  else {
    if ($output === FALSE || !empty($form_state['complete'])) {

      return render(modellibrary_get_result($object)) . "\n\r" . l(t('Back'), (isset($modelnid) ? 'model/' . $modelnid : 'models'));
    }
    elseif (!empty($form_state['cancel'])) {
      drupal_goto(isset($modelnid) ? 'model/' . $modelnid : 'models');
    }
    else {
      return $output;
    }
  }
}

/**
 * Clears the wizard cache.
 *
 * @param integer $id
 *   cache id.
 */
function modellibrary_cache_clear($id) {
  ctools_include('object-cache');
  ctools_object_cache_clear('modellibrary', $id);
}

/**
 * Stores our little cache so that we can retain data from form to form.
 *
 * @param integer $id
 *   cache id.
 * @param object $object
 *   object with form values.
 */
function modellibrary_cache_set($id, $object) {
  ctools_include('object-cache');
  ctools_object_cache_set('modellibrary', $id, $object);
}

/**
 * Gets the current object from the cache, or default.
 *
 * @param integer $id
 *   cache id.
 *
 * @return object
 *   cache with stored stuff.
 */
function modellibrary_cache_get($id) {
  ctools_include('object-cache');
  $object = ctools_object_cache_get('modellibrary', $id);
  if (!$object) {
    // Create a default object.
    $object = new stdClass;
  }

  return $object;
}

/**
 * Handles the 'next' click on the add/edit pane form wizard.
 *
 * All we need to do is store the updated pane in the cache.
 */
function modellibrary_wizard_next(&$form_state) {
  modellibrary_cache_set($form_state['object_id'], $form_state['object']);
}

/**
 * Handles the 'finish' click on the add/edit pane form wizard.
 *
 * All we need to do is set a flag so the return can handle adding
 * the pane.
 */
function modellibrary_wizard_finish(&$form_state) {
  $form_state['complete'] = TRUE;
}

/**
 * Handles the 'cancel' click on the add/edit pane form wizard.
 */
function modellibrary_wizard_cancel(&$form_state) {
  $form_state['cancel'] = TRUE;
}

/**
 * Generates first form.
 *
 * @ingroup forms
 */
function modellibrary_wizard_step1($form, &$form_state) {
  if ($form_state['form_info']['action'] == 'edit') {
    $action = 'edit';
    $node = node_load(arg(1));

    // Count the number of authors in the model
    if (isset($node->field_model_author[LANGUAGE_NONE]))
      $node_count = count($node->field_model_author[LANGUAGE_NONE]);
  }
  else {
    $action = 'add';
    $node = new stdClass();
    $node->type = 'model';
  }

  if (empty($form_state['num_authors'])) {
    if ($action == 'edit')
      $form_state['num_authors'] = $node_count;
    else
      $form_state['num_authors'] = 1;
  }

  // We will have many fields with the same name, so we need to be able to
  // access the form hierarchically.
  $form['#tree'] = TRUE;

  $form['title'] = array(
    '#type' => 'textfield',
    '#title' => t('Title'),
    '#description' => t('This is the name of the model. [REQUIRED]'),
    '#size' => 60,
    '#maxlength' => 128,
    '#required' => TRUE,
    '#default_value' => $action == 'edit' ? $node->title : (isset($form_state['object']->title) ? $form_state['object']->title : ''),
    #'#weight' => 0,
  );

  $form['summary'] = array(
    '#type' => 'textarea',
    '#title' => t('Summary'),
    '#description' => t('You must provide a short summary of the model.  This teaser is displayed in the library model listings and must not exceed 250 characters. [REQUIRED]'),
    '#cols' => 50,
    '#rows' => 3,
    '#required' => TRUE,
    '#default_value' => $action == 'edit' ? $node->body[LANGUAGE_NONE][0]['summary'] : (isset($form_state['object']->summary) ? $form_state['object']->summary : NULL),
    #'#weight' => 5,
  );

  $form['description'] = array(
    '#type' => 'text_format',
    '#title' => t('Description'),
    '#description' => t('This field contains the model\'s full description.  Please be clear about the model\'s purpose and design. [REQUIRED]'),
    '#cols' => 50,
    '#rows' => 10,
    '#required' => TRUE,
    '#default_value' => $action == 'edit' ? $node->body[LANGUAGE_NONE][0]['value'] : (isset($form_state['object']->description) ? $form_state['object']->description : ''),
    #'#format' => $form_state['form_info']['action'] == 'edit' ? $node->body[LANGUAGE_NONE][0]['format'] : (isset($form_state['object']->description['format']) ? $form_state['object']->description['format'] : ''),
    '#format' => NULL,
    #'#weight' => 6,
  );


  // Build the number of author fieldsets indicated by $form_state['num_authors']
  for ($i = 0; $i < $form_state['num_authors']; $i++) {
    // Only if we are editing AND we are only going to load a model author
    // that exists, load the model author field collection
    if ($form_state['form_info']['action'] == 'edit' && $i < $node_count) {
      $field_collection_id = $node->field_model_author[LANGUAGE_NONE][$i]['value'];
      $field_model_author = entity_load('field_collection_item', array($field_collection_id));
    }

    $form['model_author'][$i] = array(
      '#type' => 'fieldset',
      '#title' => t('Author #@num', array('@num' => $i + 1)),
      '#collapsible' => TRUE,
      '#collapsed' => FALSE,
    );

    $form['model_author'][$i]['firstname'] = array(
      '#type' => 'textfield',
      '#title' => t('First Name or Initial'),
      '#description' => t("Enter your first name."),
      '#size' => 60,
      '#maxlength' => 120,
      '#required' => TRUE,
      '#default_value' => $action == 'edit' && $i < $node_count ? $field_model_author[$field_collection_id]->field_model_authorfirst[LANGUAGE_NONE][0]['value'] : (isset($form_state['object']->model_author[$i]['firstname']) ? $form_state['object']->model_author[$i]['firstname'] : ''),
      #'#weight' => 0,
    );
    $form['model_author'][$i]['middlename'] = array(
      '#type' => 'textfield',
      '#title' => t('Middle Name or Initial'),
      '#description' => t("Enter your middle name."),
      '#size' => 60,
      '#maxlength' => 120,
      '#required' => FALSE,
      '#default_value' => $action == 'edit' && $i < $node_count ? $field_model_author[$field_collection_id]->field_model_authormiddle[LANGUAGE_NONE][0]['value'] : (isset($form_state['object']->model_author[$i]['middlename']) ? $form_state['object']->model_author[$i]['middlename'] : ''),
      #'#weight' => 0,
    );
    $form['model_author'][$i]['lastname'] = array(
      '#type' => 'textfield',
      '#title' => t('Last Name'),
      '#description' => t("Enter your last name."),
      '#size' => 60,
      '#maxlength' => 120,
      '#required' => TRUE,
      '#default_value' => $action == 'edit' && $i < $node_count ? $field_model_author[$field_collection_id]->field_model_authorlast[LANGUAGE_NONE][0]['value'] : (isset($form_state['object']->model_author[$i]['lastname']) ? $form_state['object']->model_author[$i]['lastname'] : ''),
      #'#weight' => 0,
    );
  }

  // Adds "Add another name" button
  $form['model_author']['add_author'] = array(
    '#type' => 'submit',
    '#value' => t('Add Author'),
    '#submit' => array('modellibrary_wizard_step1_add_author'),
  );

  // If we have more than one name, this button allows removal of the
  // last name.
  if ($form_state['num_authors'] > 1) {
    $form['remove_author'] = array(
      '#type' => 'submit',
      '#value' => t('Remove Author'),
      '#submit' => array('modellibrary_wizard_step1_remove_author'),
      // Since we are removing a name, don't validate until later.
      '#limit_validation_errors' => array(),
    );
  }

  // and attach the taxonomy widget to our form, using a custom function
  modellibrary_attach_form('taxonomy_vocabulary_6', 'node', $node, $form, $form_state, LANGUAGE_NONE);

  return $form;
}

/**
 * Handles submit of first form.
 */
function modellibrary_wizard_step1_submit($form, &$form_state) {
  $form_state['object']->title = $form_state['values']['title'];
  $form_state['object']->summary = $form_state['values']['summary'];
  $form_state['object']->description = $form_state['values']['description'];
  $form_state['object']->model_author = $form_state['values']['model_author'];
  unset($form_state['object']->model_author['add_author']);
  $form_state['object']->taxonomy_vocabulary_6 = $form_state['values']['taxonomy_vocabulary_6'];
}

/**
 * Submit handler for "Add another author" button on modellibrary_wizard_step1().
 *
 * $form_state['num_authors'] tells the form builder function how many name
 * fieldsets to build, so here we increment it.
 *
 * All elements of $form_state are persisted, so there's no need to use a
 * particular key, like the old $form_state['storage']. We can just use
 * $form_state['num_authors'].
 */
function modellibrary_wizard_step1_add_author($form, &$form_state) {
  $form_state['num_authors']++;

  // Setting $form_state['rebuild'] = TRUE causes the form to be rebuilt again.
  $form_state['rebuild'] = TRUE;
}


function modellibrary_wizard_step1_remove_author($form, &$form_state) {
  if ($form_state['num_authors'] > 1) {
    $form_state['num_authors']--;
  }

  // Setting $form_state['rebuild'] = TRUE causes the form to be rebuilt again.
  $form_state['rebuild'] = TRUE;
}


/**
 * Generates second form.
 *
 * @ingroup forms
 */
function modellibrary_wizard_step2($form, &$form_state) {
  if ($form_state['form_info']['action'] == 'edit')
    $node = node_load(arg(1));

  $form['field_model_publication_text'] = array(
    '#type' => 'textarea',
    '#title' => t('Model Reference'),
    '#description' => t('If this model has been published, you should provide the bibliographic reference to that publication.'),
    '#cols' => 50,
    '#rows' => 5,
    '#default_value' => $form_state['form_info']['action'] == 'edit' && isset($node->field_model_publication_text[LANGUAGE_NONE][0]['value']) ? $node->field_model_publication_text[LANGUAGE_NONE][0]['value'] : (isset($form_state['object']->field_model_publication_text) ? $form_state['object']->field_model_publication_text : ''),
  ); 

  $form['replication'] = array(
    '#type' => 'fieldset',
    '#title' => t('Model Replication Info'),
  );

  $form['replication']['field_model_replicated'] = array(
    '#type' => 'checkbox',
    '#title' => t('Replicated Model?'),
    '#description' => t('If this is checked, the model is a replication of another model, published or otherwise. You must also provide the bibliographic reference to that model in the field below.'),
    '#default_value' => $form_state['form_info']['action'] == 'edit' && isset($node->field_model_replicated[LANGUAGE_NONE][0]['value']) ? $node->field_model_replicated[LANGUAGE_NONE][0]['value'] : (isset($form_state['object']->field_model_replicated) ? $form_state['object']->field_model_replicated : ''),
  );

  $form['replication']['field_model_reference'] = array(
    '#type' => 'textarea',
    '#title' => t('Replication Reference'),
    '#description' => t('If this model is a replication of another model, you must provide the bibliographic reference for the original model implementation.'),
    '#cols' => 50,
    '#rows' => 5,
    '#default_value' => $form_state['form_info']['action'] == 'edit' && isset($node->field_model_reference[LANGUAGE_NONE][0]['value']) ? $node->field_model_reference[LANGUAGE_NONE][0]['value'] : (isset($form_state['object']->field_model_reference) ? $form_state['object']->field_model_reference : ''),
  ); 

  $form['file_image_fid'] = array(
    '#title' => t('Model Image'),
    '#type' => 'managed_file',
    '#description' => t(''),
    '#default_value' => $form_state['form_info']['action'] == 'edit' && isset($node->field_model_image[LANGUAGE_NONE][0]['fid']) ? $node->field_model_image[LANGUAGE_NONE][0]['fid'] : (isset($form_state['object']->file_image_fid) ? $form_state['object']->file_image_fid : ''),
    '#progress_indicator' => 'bar',
    '#upload_validators' => array(
      'file_validate_extensions' => array('bmp gif jpg jpeg png'),
    ),
  );

  $form['file_video_fid'] = array(
    '#title' => t('Model Video'),
    '#type' => 'managed_file',
    '#description' => t(''),
    '#default_value' => $form_state['form_info']['action'] == 'edit' && isset($node->field_model_video[LANGUAGE_NONE][0]['fid']) ? $node->field_model_video[LANGUAGE_NONE][0]['fid'] : (isset($form_state['object']->file_video_fid) ? $form_state['object']->file_video_fid : ''),
    '#progress_indicator' => 'bar',
    '#upload_validators' => array(
      'file_validate_extensions' => array('avi mov m4v mp4 mpeg wmv flv'),
    ),
  );

  return $form;
}

/**
 * Handles submit for second form.
 */
function modellibrary_wizard_step2_submit($form, &$form_state) {
  $form_state['object']->field_model_publication_text = $form_state['values']['field_model_publication_text'];
  $form_state['object']->field_model_replicated = $form_state['values']['field_model_replicated'];
  $form_state['object']->field_model_reference = $form_state['values']['field_model_reference'];
  $form_state['object']->file_image_fid = $form_state['values']['file_image_fid'];
  $form_state['object']->file_video_fid = $form_state['values']['file_video_fid'];
}

/**
 * Generates third form.
 *
 * @ingroup forms
 */
function modellibrary_wizard_step3($form, &$form_state) {
  if ($form_state['form_info']['action'] == 'edit') {
    $node = load_modelversion(arg(1));
  }
  
  $query = "SELECT id, name FROM {modellibrary_platform} ORDER BY weight, name";
  $result = db_query($query);

  while ($row = $result->fetchObject()) {
    $platform_options[$row->id] = $row->name;
  }

  $query = "SELECT id, name FROM {modellibrary_language} ORDER BY weight, name";
  $result = db_query($query);

  while ($row = $result->fetchObject()) {
    $language_options[$row->id] = $row->name;
  }

  $query = "SELECT id, name FROM {modellibrary_os} ORDER BY weight, name";
  $result = db_query($query);

  while ($row = $result->fetchObject()) {
    $os_options[$row->id] = $row->name;
  }

  $form['notes'] = array(
    '#type' => 'text_format',
    '#title' => t('Version Notes'),
    '#description' => t('If you have specific notes about this version of the model, distinct from the general model details, enter that information here.'),
    '#cols' => 50,
    '#rows' => 5,
    '#default_value' => $form_state['form_info']['action'] == 'edit' && isset($node->body[LANGUAGE_NONE][0]['value']) ? $node->body[LANGUAGE_NONE][0]['value'] : (isset($form_state['object']->notes) ? $form_state['object']->notes : ''),
  );

  $form['platform'] = array(
    '#type' => 'fieldset',
    '#title' => t('Software Platform'),
  );

  $form['platform']['platform'] = array(
    '#type' => 'select',
    '#title' => t('Modeling Platform'),
    '#description' => t(''),
    '#options' => $platform_options,
    '#default_value' => $form_state['form_info']['action'] == 'edit' && isset($node->field_modelversion_platform[LANGUAGE_NONE][0]['value']) ? $node->field_modelversion_platform[LANGUAGE_NONE][0]['value'] : (isset($form_state['object']->platform) ? $form_state['object']->platform : NULL),
    '#required' => TRUE,
  );

  $form['platform']['platform_other'] = array(
    '#type' => 'textfield',
    '#title' => t('Other Platform'),
    '#description' => t(''),
    '#default_value' => $form_state['form_info']['action'] == 'edit' && isset($node->field_modelversion_platform_oth[LANGUAGE_NONE][0]['value']) ? $node->field_modelversion_platform_oth[LANGUAGE_NONE][0]['value'] : (isset($form_state['object']->platform_other) ? $form_state['object']->platform_other : ''),
    '#states' => array(
      'visible' => array(
        ':input[name="platform"]' => array('value' => 16),
      ),
    ),
  );

  $form['platform']['platform_version'] = array(
    '#type' => 'textfield',
    '#title' => t('Platform Version'),
    '#description' => t(''),
    '#default_value' => $form_state['form_info']['action'] == 'edit' && isset($node->field_modelversion_platform_ver[LANGUAGE_NONE][0]['value']) ? $node->field_modelversion_platform_ver[LANGUAGE_NONE][0]['value'] : (isset($form_state['object']->platform_version) ? $form_state['object']->platform_version : ''),
  );

  $form['language'] = array(
    '#type' => 'fieldset',
    '#title' => t('Programming Language'),
  );

  $form['language']['language'] = array(
    '#type' => 'select',
    '#title' => t('Programming Language'),
    '#description' => t(''),
    '#options' => $language_options,
    '#default_value' => $form_state['form_info']['action'] == 'edit' && isset($node->field_modelversion_language[LANGUAGE_NONE][0]['value']) ? $node->field_modelversion_language[LANGUAGE_NONE][0]['value'] : (isset($form_state['object']->language) ? $form_state['object']->language : NULL),
    '#required' => TRUE,
  );

  $form['language']['language_other'] = array(
    '#type' => 'textfield',
    '#title' => t('Other Language'),
    '#description' => t(''),
    '#default_value' => $form_state['form_info']['action'] == 'edit' && isset($node->field_modelversion_language_oth[LANGUAGE_NONE][0]['value']) ? $node->field_modelversion_language_oth[LANGUAGE_NONE][0]['value'] : (isset($form_state['object']->language_other) ? $form_state['object']->language_other : ''),
    '#states' => array(
      'visible' => array(
        ':input[name="language"]' => array('value' => 7),
      ),
    ),
  );

  $form['language']['language_version'] = array(
    '#type' => 'textfield',
    '#title' => t('Language Version'),
    '#description' => t(''),
    '#default_value' => $form_state['form_info']['action'] == 'edit' && isset($node->field_modelversion_language_ver[LANGUAGE_NONE][0]['value']) ? $node->field_modelversion_language_ver[LANGUAGE_NONE][0]['value'] : (isset($form_state['object']->language_version) ? $form_state['object']->language_version : ''),
  );

  $form['os'] = array(
    '#type' => 'fieldset',
    '#title' => t('Operating System'),
  );

  $form['os']['os'] = array(
    '#type' => 'select',
    '#title' => t('Operating System'),
    '#description' => t(''),
    '#options' => $os_options,
    '#default_value' => $form_state['form_info']['action'] == 'edit' && isset($node->field_modelversion_os[LANGUAGE_NONE][0]['value']) ? $node->field_modelversion_os[LANGUAGE_NONE][0]['value'] : (isset($form_state['object']->os) ? $form_state['object']->os : NULL),
    '#required' => TRUE,
  );

  $form['os']['os_other'] = array(
    '#type' => 'textfield',
    '#title' => t('Other OS'),
    '#description' => t(''),
    '#default_value' => $form_state['form_info']['action'] == 'edit' && isset($node->field_modelversion_os_oth[LANGUAGE_NONE][0]['value']) ? $node->field_modelversion_os_oth[LANGUAGE_NONE][0]['value'] : (isset($form_state['object']->os_other) ? $form_state['object']->os_other : ''),
    '#states' => array(
      'visible' => array(
        ':input[name="os"]' => array('value' => 5),
      ),
    ),
  );

  $form['os']['os_version'] = array(
    '#type' => 'textfield',
    '#title' => t('OS Version'),
    '#description' => t(''),
    '#default_value' => $form_state['form_info']['action'] == 'edit' && isset($node->field_modelversion_os_version[LANGUAGE_NONE][0]['value']) ? $node->field_modelversion_os_version[LANGUAGE_NONE][0]['value'] : (isset($form_state['object']->os_version) ? $form_state['object']->os_version : ''),
  );

  return $form;
}

/**
 * Handles submit for third form.
 */
function modellibrary_wizard_step3_submit(&$form, &$form_state) {
  $form_state['object']->notes = $form_state['values']['notes'];
  $form_state['object']->platform = $form_state['values']['platform'];
  $form_state['object']->platform_other = $form_state['values']['platform_other'];
  $form_state['object']->platform_version = $form_state['values']['platform_version'];
  $form_state['object']->language = $form_state['values']['language'];
  $form_state['object']->language_other = $form_state['values']['language_other'];
  $form_state['object']->language_version = $form_state['values']['language_version'];
  $form_state['object']->os = $form_state['values']['os'];
  $form_state['object']->os_other = $form_state['values']['os_other'];
  $form_state['object']->os_version = $form_state['values']['os_version'];
}

/**
 * Generates fourth form.
 *
 * @ingroup forms
 */
function modellibrary_wizard_step4($form, &$form_state) {
  if ($form_state['form_info']['action'] == 'edit') 
    $node = load_modelversion(arg(1));

  $form['help'] = array(
    '#markup' => '<div class="setoff"><p>Upload the files for your model.  At a minimum, you must upload the code and documentation for this model.  Users may find it very helpful if you also share any sensitivity analysis information, datasets, or other files that can be used with your model or for gaining understanding into its functionality.</p><p>When editing this model after it has been created, any changes to these files will result in a new model version being created.</p></div>',
    '#weight' => -20,
  );

  $form['file_code_fid'] = array(
    '#title' => t('Code'),
    '#type' => 'managed_file',
    '#description' => t(''),
    '#default_value' => $form_state['form_info']['action'] == 'edit' ? $node->field_modelversion_code[LANGUAGE_NONE][0]['fid'] : (isset($form_state['object']->file_code_fid) ? $form_state['object']->file_code_fid : ''),
    '#progress_indicator' => 'bar',
    '#required' => TRUE,
    '#upload_validators' => array(
      'file_validate_extensions' => array('zip gz bz2 nlogo txt'),
    ),
  );

  $form['file_documentation_fid'] = array(
    '#title' => t('Documentation'),
    '#type' => 'managed_file',
    '#description' => t(''),
    '#default_value' => $form_state['form_info']['action'] == 'edit' ? $node->field_modelversion_documentation[LANGUAGE_NONE][0]['fid'] : (isset($form_state['object']->file_documentation_fid) ? $form_state['object']->file_documentation_fid : ''),
    '#progress_indicator' => 'bar',
    '#required' => TRUE,
    '#upload_validators' => array(
      'file_validate_extensions' => array('doc docx odt txt pdf'),
    ),
  );

  $form['file_sensitivity_fid'] = array(
    '#title' => t('Sensitivity'),
    '#type' => 'managed_file',
    '#description' => t(''),
    '#default_value' => $form_state['form_info']['action'] == 'edit' && isset($node->field_modelversion_sensitivity[LANGUAGE_NONE][0]['fid']) ? $node->field_modelversion_sensitivity[LANGUAGE_NONE][0]['fid'] : (isset($form_state['object']->file_sensitivity_fid) ? $form_state['object']->file_sensitivity_fid : ''),
    '#progress_indicator' => 'bar',
    '#upload_validators' => array(
      'file_validate_extensions' => array('pdf doc docx xls xlsx odt ods odp txt zip gz bz2'),
    ),
  );

  $form['file_dataset_fid'] = array(
    '#title' => t('Dataset'),
    '#type' => 'managed_file',
    '#description' => t(''),
    '#default_value' => $form_state['form_info']['action'] == 'edit' && isset($node->field_modelversion_dataset[LANGUAGE_NONE][0]['fid']) ? $node->field_modelversion_dataset[LANGUAGE_NONE][0]['fid'] : (isset($form_state['object']->file_dataset_fid) ? $form_state['object']->file_dataset_fid : ''),
    '#progress_indicator' => 'bar',
    '#upload_validators' => array(
      'file_validate_extensions' => array('pdf doc docx xls xlsx odt ods odp txt zip gz bz2'),
    ),
  );

  $form['file_addfiles_fid'] = array(
    '#title' => t('Additional Files'),
    '#type' => 'managed_file',
    '#description' => t(''),
    '#default_value' => $form_state['form_info']['action'] == 'edit' && isset($node->field_modelversion_addfiles[LANGUAGE_NONE][0]['fid']) ? $node->field_modelversion_addfiles[LANGUAGE_NONE][0]['fid'] : (isset($form_state['object']->file_addfiles_fid) ? $form_state['object']->file_addfiles_fid : ''),
    '#progress_indicator' => 'bar',
    '#upload_validators' => array(
      'file_validate_extensions' => array('pdf doc docx xls xlsx odt ods odp txt zip gz bz2'),
    ),
  );

  return $form;
}

/**
 * Handles submit for fourth form.
 */
function modellibrary_wizard_step4_submit(&$form, &$form_state) {
  $form_state['object']->file_code_fid = $form_state['values']['file_code_fid'];
  $form_state['object']->file_documentation_fid = $form_state['values']['file_documentation_fid'];
  $form_state['object']->file_sensitivity_fid = $form_state['values']['file_sensitivity_fid'];
  $form_state['object']->file_dataset_fid = $form_state['values']['file_dataset_fid'];
  $form_state['object']->file_addfiles_fid = $form_state['values']['file_addfiles_fid'];
}


/**
 * Generates fifth form.
 *
 * @ingroup forms
 */
function modellibrary_wizard_step5($form, &$form_state) {
  if ($form_state['form_info']['action'] == 'edit')
    $node = load_modelversion(arg(1));
  
  $query = "SELECT id, name FROM {modellibrary_license} ORDER BY weight, name";
  $result = db_query($query);

  while ($row = $result->fetchObject()) {
    $license_options[$row->id] = $row->name;
  }

  $form['help'] = array(
    '#markup' => '<div class="setoff"><p>Select a software license that you wish to apply to your model.  Each license establishes a set of permissions and restrictions under which you allow others to use your code.  These licenses range from quite liberal (Creative Commons Attribution 3.0) to relatively restrictive (GPL Version 3).</p></div>',
    '#weight' => -20,
  );

  $form['license'] = array(
    '#type' => 'fieldset',
    '#title' => t('Software License'),
  );

  $form['license']['license'] = array(
    '#type' => 'radios',
    '#title' => t('Software License'),
    '#description' => t('Please select the software you wish to release your model under. Click the link to view each license\'s terms.'),
    '#options' => $license_options,
    '#default_value' => $form_state['form_info']['action'] == 'edit' ? $node->field_modelversion_license[LANGUAGE_NONE][0]['value'] : (isset($form_state['object']->license) ? $form_state['object']->license : NULL),
    '#required' => TRUE,
  );

  $form['runconditions'] = array(
    '#type' => 'text_format',
    '#title' => t('Operating Instructions'),
    '#description' => t('If your model requires any specific pctions or setup procedures in order to run, please provide that information here and be as complete and thorough as possible, to ensure model users can successfully run the model.'),
    '#cols' => 50,
    '#rows' => 5,
    '#weight' => 19,
    '#default_value' => $form_state['form_info']['action'] == 'edit' && isset($node->field_modelversion_runconditions[LANGUAGE_NONE][0]['value']) ? $node->field_modelversion_runconditions[LANGUAGE_NONE][0]['value'] : (isset($form_state['object']->runconditions) ? $form_state['object']->runconditions : ''),
    '#format' => NULL,
  );

  dpm($form_state);

  return $form;
}

/**
 * Handles submit for fifth form.
 */
function modellibrary_wizard_step5_submit(&$form, &$form_state) {
  $form_state['object']->license = $form_state['values']['license'];
  $form_state['object']->runconditions = $form_state['values']['runconditions'];
}

/**
 * Generates sixth form.
 *
 * @ingroup forms
 */
function modellibrary_wizard_step6($form, &$form_state) {
  if ($form_state['form_info']['action'] == 'edit')
    $node = node_load(arg(1));

  $form['help'] = array(
    '#markup' => '<div class="setoff"><h3>Save Model.</h3><p>You may now save your model by clicking "Submit" or return to previous pages by clicking "Previous" to review your data entry and make changes.</p><p>This model will not be automatically visible to the public.  First, the Model Library will scan your model for any incomplete fields you may have overlooked.  If for any reason, the model is not ready to be published, the Model viewing page will display a notice for you, informing you of the reasons the model is not ready to be published.</p><p>Second, if there are no issues with the model itself, you must choose to Publish the model in order to make it publicly viewable and searchable.  You may choose to keep a model disabled, if for example it is associated with a publication under review, and you do not wish to make the model public until the paper has been published.</p><h3>Sharing your model with editors and reviewers.</h3><p>While a model is disabled, it will not appear in the Model Library listings nor in search results, but you can share your model with specific individuals by copying the URL on your model\'s page, and share that web address with others. You may email that information or include it in your article submission material so reviewers can include your model in their review process, if necessary.</p><p>Click "Submit" to save your model now and go to your new model.</p></div>',
    '#weight' => -20,
  );

  return $form;
}

/**
 * Handles submit for sixth form.
 */
function modellibrary_wizard_step6_submit(&$form, &$form_state) {
}


/**
 * Returns form results.
 *
 * @param object $object
 *   object with form values.
 *   
 * @return array
 *   returns renderable array for multistep form result output.
 */
function modellibrary_get_result($object) {
  global $user;

  if (arg(2) == 'add') {
    $action = 'add';
    $model = new stdClass();
    $model->type = 'model';
    node_object_prepare($model);

    $model->language = LANGUAGE_NONE;
    $model->name = $user->name;
    $model->status = 0;
    $model->field_model_featured[$model->language][0]['value'] = 0;
  }
  else {
    $action = 'edit';
    $model = node_load(arg(1));
  }

  $model->title = $object->title;
  $model->body[$model->language][0]['value'] = $object->description['value'];
  $model->body[$model->language][0]['summary'] = $object->summary;
  $model->body[$model->language][0]['format'] = $object->description['format'];

  $model->field_model_publication_text[$model->language][0]['value'] = $object->field_model_publication_text;
  $model->field_model_replicated[$model->language][0]['value'] = $object->field_model_replicated;
  $model->field_model_reference[$model->language][0]['value'] = $object->field_model_reference;

  // Process taxonomy terms
  if ($action == 'add') {
    // new model; add all terms to node
    foreach ($object->taxonomy_vocabulary_6[LANGUAGE_NONE] as $tag) {
      // if tag is new, then add to vocabulary
      if ($tag['tid'] == 'autocreate') {
          $term = new stdClass();
          $term->name = $tag['name'];
          $term->vid = $tag['vid'];
          taxonomy_term_save($term);

          $model->taxonomy_vocabulary_6[$model->language][]['tid'] = $term->tid;
      }
      else {
        $model->taxonomy_vocabulary_6[$model->language][]['tid'] = $tag['tid'];
      }
    }
  }
  // model is being edited, update and adjust node depending on changes in assigned keywords
  else {
    // count number of elements in form and node
    $form_count = count($object->taxonomy_vocabulary_6[LANGUAGE_NONE]);
    $node_count = count($model->taxonomy_vocabulary_6[$model->language]);

    // if form count <= node count, then update the node with the form items and delete the excess
    if ($form_count <= $node_count) {
      foreach ($object->taxonomy_vocabulary_6[LANGUAGE_NONE] as $index => $item) {
        // if tag is new, then add to vocab
        if ($tag['tid'] == 'autocreate') {
            $term = new stdClass();
            $term->name = $tag['name'];
            $term->vid = $tag['vid'];
            taxonomy_term_save($term);

            $model->taxonomy_vocabulary_6[$model->language][]['tid'] = $term->tid;
        }
        // Check whether the form term matches the term in the node
        // it is possible for the same terms to be in a different order,
        // like if the user removed multiple tags and then re-added in different order
        // we aren't going to towrry about this. If the terms don't match, then
        // we will replace the node term with the form term.
        elseif ($model->taxonomy_vocabulary_6[$model->language][$index]['tid'] != $item['tid']) 
          $model->taxonomy_vocabulary_6[$model->language][$index]['tid'] = $item['tid'];
      }

      // remove extra items in node taxonomy field
      if ($form_count < $node_count) {
        $index = $node_count;
        while ($index > $form_count) {
          unset($model->taxonomy_vocabulary_6[$model->language][$index - 1]);
          $index--;
        }
      }
    }
    else {
      // more terms in form than in node, need to add the new additions

      // first, update the terms that overlap between form and node
      $index = 0;
      while ($index < $node_count) {
        // if tag is new, then add to vocab
        if ($object->taxonomy_vocabulary_6[LANGUAGE_NONE][$index]['tid'] == 'autocreate') {
            $term = new stdClass();
            $term->name = $object->taxonomy_vocabulary_6[LANGUAGE_NONE][$index]['name'];
            $term->vid = $object->taxonomy_vocabulary_6[LANGUAGE_NONE][$index]['vid'];
            taxonomy_term_save($term);

            $model->taxonomy_vocabulary_6[$model->language][$index]['tid'] = $term->tid;
        }
        if ($model->taxonomy_vocabulary_6[$model->language][$index]['tid'] != $object->taxonomy_vocabulary_6[LANGUAGE_NONE][$index]['tid']) 
          $model->taxonomy_vocabulary_6[$model->language][$index]['tid'] = $object->taxonomy_vocabulary_6[LANGUAGE_NONE][$index]['tid'];
        $index++; 
      }
      
      // Insert extra terms
      while ($index < $form_count) {
        // if tag is new, then add to vocabulary
        if ($object->taxonomy_vocabulary_6[LANGUAGE_NONE][$index]['tid'] == 'autocreate') {
            $term = new stdClass();
            $term->name = $object->taxonomy_vocabulary_6[LANGUAGE_NONE][$index]['name'];
            $term->vid = $object->taxonomy_vocabulary_6[LANGUAGE_NONE][$index]['vid'];
            taxonomy_term_save($term);

            $model->taxonomy_vocabulary_6[$model->language][]['tid'] = $term->tid;
        }
        else {
          $model->taxonomy_vocabulary_6[$model->language][]['tid'] = $object->taxonomy_vocabulary_6[LANGUAGE_NONE][$index]['tid'];
        }
        $index++;
      }
    }
  }

  if ($action == 'add') {
    if ($model = node_submit($model)) {
      node_save($model);
    }
  }
  else {
    node_save($model);
  }

  // Only process image and video if they are new uploads

  // Save Model Image

  // if 'add' then check if object->fid is !=0
  // if 'edit' then we need to compare the object with the node
    // Several possibilities during edit:
    // 1. form object and node are same: do nothing
    // 2. form object has file and node does not: add file to node
    // 3. form object has file and node has different file: delete node file and save form object file
    // 4. form object has no file and node has file: delete node file
  
  $savenode = FALSE; // flag to indicate whether the node needs to be saved

  if ($object->file_image_fid != 0) {
    if (!isset($model->field_model_image[LANGUAGE_NONE][0])) {
      // add image file to node
        
      $file = file_load($object->file_image_fid);
      $file->status = FILE_STATUS_PERMANENT;
      $directory = 'public://models/' . $model->nid;
      file_prepare_directory($directory, FILE_CREATE_DIRECTORY);
      $file->uri = file_unmanaged_copy($file->uri, $directory, FILE_EXISTS_REPLACE);
      drupal_chmod($file->uri);
      file_save($file);
      file_usage_add($file, 'modellibrary', 'node', $model->nid);

      $model->field_model_image[LANGUAGE_NONE][0] = (array) $file;
      $model->field_model_image[LANGUAGE_NONE][0]['display'] = 1;

      $savenode = TRUE;
    }
    elseif ($object->file_image_fid != $model->field_model_image[LANGUAGE_NONE][0]['fid']) {
      // image file changed. delete and replace
      
      // load node's file object
      $file = file_load($model->field_model_image[LANGUAGE_NONE][0]['fid']);
      // delete file
      file_delete($file);

      // and save replacement file
      $file = file_load($object->file_image_fid);
      $file->status = FILE_STATUS_PERMANENT;
      $directory = 'public://models/' . $model->nid;
      file_prepare_directory($directory, FILE_CREATE_DIRECTORY);
      $file->uri = file_unmanaged_copy($file->uri, $directory, FILE_EXISTS_REPLACE);
      drupal_chmod($file->uri);
      file_save($file);
      file_usage_add($file, 'modellibrary', 'node', $model->nid);

      $model->field_model_image[LANGUAGE_NONE][0] = (array) $file;
      $model->field_model_image[LANGUAGE_NONE][0]['display'] = 1;

      $savenode = TRUE;
    }
  }
  else { // form file is empty
    if (isset($model->field_model_image[LANGUAGE_NONE][0])) {
      // node image needs to be deleted

      // load node's file object
      $file = file_load($model->field_model_image[LANGUAGE_NONE][0]['fid']);
      // remove reference in node
      unset($model->field_model_image[LANGUAGE_NONE][0]);
      // delete file
      file_delete($file);

      $savenode = TRUE;
    }  
  }


  if ($object->file_video_fid != 0) {
    if (!isset($model->field_model_video[LANGUAGE_NONE][0])) {
      // add video file to node
        
      $file = file_load($object->file_video_fid);
      $file->status = FILE_STATUS_PERMANENT;
      $directory = 'public://models/' . $model->nid;
      file_prepare_directory($directory, FILE_CREATE_DIRECTORY);
      $file->uri = file_unmanaged_copy($file->uri, $directory, FILE_EXISTS_REPLACE);
      drupal_chmod($file->uri);
      file_save($file);
      file_usage_add($file, 'modellibrary', 'node', $model->nid);

      $model->field_model_video[LANGUAGE_NONE][0] = (array) $file;
      $model->field_model_video[LANGUAGE_NONE][0]['display'] = 1;

      $savenode = TRUE;
    }
    elseif ($object->file_video_fid != $model->field_model_video[LANGUAGE_NONE][0]['fid']) {
      // video file changed. delete and replace
      
      // load node's file object
      $file = file_load($model->field_model_video[LANGUAGE_NONE][0]['fid']);
      // delete file
      file_delete($file);

      // and save replacement file
      $file = file_load($object->file_video_fid);
      $file->status = FILE_STATUS_PERMANENT;
      $directory = 'public://models/' . $model->nid;
      file_prepare_directory($directory, FILE_CREATE_DIRECTORY);
      $file->uri = file_unmanaged_copy($file->uri, $directory, FILE_EXISTS_REPLACE);
      drupal_chmod($file->uri);
      file_save($file);
      file_usage_add($file, 'modellibrary', 'node', $model->nid);

      $model->field_model_video[LANGUAGE_NONE][0] = (array) $file;
      $model->field_model_video[LANGUAGE_NONE][0]['display'] = 1;

      $savenode = TRUE;
    }
  }
  else { // form file is empty
    if (isset($model->field_model_video[LANGUAGE_NONE][0])) {
      // node video needs to be deleted

      // load node's file object
      $file = file_load($model->field_model_video[LANGUAGE_NONE][0]['fid']);
      // remove reference in node
      unset($model->field_model_video[LANGUAGE_NONE][0]);
      // delete file
      file_delete($file);

      $savenode = TRUE;
    }  
  }

  // If an image or video needs to be saved to the node
  if ($savenode) {
    node_save($model);
  }

  // Save model authors in Field Collection

  // A complicated solution for updating model authors would be to 
  // load the node's field collection and compare the form values with
  // the collection's field values. However, it is probably just as
  // effective to just always save the form to the field collection.
  // Might have to count the number of field instances in collection.
  // If form has different number of items (more or fewer authors)
  // then that will have to be dealt with (create or delete instances.)

  if ($action == 'add') {
    // Documentation available on adding entries to a field collection: http://drupal.org/node/1477186

    foreach ($object->model_author as $index => $author) {
      $values['field_name'] = 'field_model_author';
      $values['field_model_authorfirst'][LANGUAGE_NONE][0]['value'] = $author['firstname'];
      $values['field_model_authormiddle'][LANGUAGE_NONE][0]['value'] = $author['middlename'];
      $values['field_model_authorlast'][LANGUAGE_NONE][0]['value'] = $author['lastname'];

      $field_collection_item = entity_create('field_collection_item', $values);
      $field_collection_item->setHostEntity('node', $model);
      $field_collection_item->save();
    }
  }
  else { // action = edit
    // Documentation on updating field collections: http://drupal.org/node/1842304
    // Also: http://rajanmayekar.com/blog/programmatically-creating-deleting-modifying-field-collection-item-node

    // Count the number of items in field collection
    if (isset($model->field_model_author[LANGUAGE_NONE]))
      $node_count = count($model->field_model_author[LANGUAGE_NONE]);
    else
      $node_count = 0;
    // Count the number of items in form
    $form_count = count($object->model_author);
    if ($form_count < 0)
      $form_count = 0;

    if ($form_count <= $node_count) {
      // step through each form item
      foreach ($object->model_author as $index => $author) {
        // Get the entity id for the corresponding model author in the model node
        $field_collection_id = $model->field_model_author[LANGUAGE_NONE][$index]['value'];
        // and load the model author field collection
        $field_model_author = entity_load('field_collection_item', array($field_collection_id));

        // test each name field, if form is different than stored value, then update field
        if ($field_model_author[$field_collection_id]->field_model_authorfirst[LANGUAGE_NONE][0]['value'] != $author['firstname']) {
          $field_model_author[$field_collection_id]->field_model_authorfirst[LANGUAGE_NONE][0]['value'] = $author['firstname'];
          $save_field = TRUE;
        }

        if ($field_model_author[$field_collection_id]->field_model_authormiddle[LANGUAGE_NONE][0]['value'] != $author['middlename']) {
          $field_model_author[$field_collection_id]->field_model_authormiddle[LANGUAGE_NONE][0]['value'] = $author['middlename'];
          $save_field = TRUE;
        }

        if ($field_model_author[$field_collection_id]->field_model_authorlast[LANGUAGE_NONE][0]['value'] != $author['lastname']) {
          $field_model_author[$field_collection_id]->field_model_authorlast[LANGUAGE_NONE][0]['value'] = $author['lastname'];
          $save_field = TRUE;
        }

        if ($save_field) {
          $field_model_author[$field_collection_id]->save();
        }
      }

      // if form count < node count, we need to delete the surplus items in the node (Authors were deleted)
      if ($form_count < $node_count) {
        $index = $node_count;
        
        while ($index > $form_count) {
          entity_delete('field_collection_item', $model->field_model_author[LANGUAGE_NONE][$index - 1]); 
          unset($model->field_model_author[LANGUAGE_NONE][$index - 1]);
          field_attach_update('node', $model);
          $index--;
        }
      }
    }
    else { 
      // more authors in form than in node, need to add the additions

      $index = 0;

      while ($index < $node_count) {
        // update these node items

        // Get the entity id for the corresponding model author in the model node
        $field_collection_id = $model->field_model_author[LANGUAGE_NONE][$index]['value'];
        // and load the model author field collection
        $field_model_author = entity_load('field_collection_item', array($field_collection_id));

        // test each name field, if form is different than stored value, then update field
        if ($field_model_author[$field_collection_id]->field_model_authorfirst[LANGUAGE_NONE][0]['value'] != $object->model_author[$index]['firstname']) {
          $field_model_author[$field_collection_id]->field_model_authorfirst[LANGUAGE_NONE][0]['value'] = $object->model_author[$index]['firstname'];
          $save_field = TRUE;
        }

        if ($field_model_author[$field_collection_id]->field_model_authormiddle[LANGUAGE_NONE][0]['value'] != $object->model_author[$index]['middlename']) {
          $field_model_author[$field_collection_id]->field_model_authormiddle[LANGUAGE_NONE][0]['value'] = $object->model_author[$index]['middlename'];
          $save_field = TRUE;
        }

        if ($field_model_author[$field_collection_id]->field_model_authorlast[LANGUAGE_NONE][0]['value'] != $object->model_author[$index]['lastname']) {
          $field_model_author[$field_collection_id]->field_model_authorlast[LANGUAGE_NONE][0]['value'] = $object->model_author[$index]['lastname'];
          $save_field = TRUE;
        }

        if ($save_field)
          $field_model_author[$field_collection_id]->save();

        $index++;
      }

      // Add extra authors to node
      while ($index < $form_count) {
        $values['field_name'] = 'field_model_author';
        $values['field_model_authorfirst'][LANGUAGE_NONE][0]['value'] = $object->model_author[$index]['firstname'];
        $values['field_model_authormiddle'][LANGUAGE_NONE][0]['value'] = $object->model_author[$index]['middlename'];
        $values['field_model_authorlast'][LANGUAGE_NONE][0]['value'] = $object->model_author[$index]['lastname'];

        $field_collection_item = entity_create('field_collection_item', $values);
        $field_collection_item->setHostEntity('node', $model);
        $field_collection_item->save();

        $index++;
      }
    }
  }

  /***********************
   *     Model Version   *
   ***********************/

  // check if this is an Add or Edit
  // if this is an edit, then determine if the files have been changed
  // if so, create a new Version node, otherwise update the current one.

  $new_version = FALSE;
  // If we are editing a model, load a temp copy of the current modelversion
  if ($action == 'edit') {
    $version = load_modelversion($model->nid);
  }

  // New model, so create new version node
  if ($action == 'add') {
    $new_version = TRUE;
  }
  else {
    // if we changed any file uploads, then create new version
    if ((($object->file_code_fid != $version->field_modelversion_code[LANGUAGE_NONE][0]['fid'] || $object->file_documentation_fid != $version->field_modelversion_documentation[LANGUAGE_NONE][0]['fid'] || $object->file_sensitivity_fid != $version->field_modelversion_sensitivity[LANGUAGE_NONE][0]['fid'] || $object->file_dataset_fid != $version->field_modelversion_dataset[LANGUAGE_NONE][0]['fid'] || $object->file_addfiles_fid != $version->field_modelversion_addfiles[LANGUAGE_NONE][0]['fid']))) {
      $new_version = TRUE;
    }
  }

  if ($new_version) {
    $version = new stdClass();
    $version->type = 'modelversion';
    node_object_prepare($version);

    $version->language = LANGUAGE_NONE;
    $version->name = $user->name;
    $version->status = 1;
    $version->field_modelversion_number[$version->language][0]['value'] = modellibrary_helper_get_next_versionnum($model->nid);
    $version->field_modelversion_model[$version->language][0]['nid'] = $model->nid;
  }

  if ($action == 'add')
    $version->title = $model->title;

  if ($version = node_submit($version))
    node_save($version);

  if ($new_version) {
    // Code
    $file = file_load($object->file_code_fid);
    $file->status = FILE_STATUS_PERMANENT;
    $directory = 'public://models/' . $model->nid . '/v' . $version->field_modelversion_number[$version->language][0]['value'] . '/code';
    file_prepare_directory($directory, FILE_CREATE_DIRECTORY);
    $file->uri = file_unmanaged_copy($file->uri, $directory, FILE_EXISTS_REPLACE);
    drupal_chmod($file->uri);
    file_save($file);
    file_usage_add($file, 'modellibrary', 'node', $version->nid);
    $version->field_modelversion_code[LANGUAGE_NONE][0] = (array) $file;
    $version->field_modelversion_code[LANGUAGE_NONE][0]['display'] = 1;

    // Documentation
    $file = file_load($object->file_documentation_fid);
    $file->status = FILE_STATUS_PERMANENT;
    $directory = 'public://models/' . $model->nid . '/v' . $version->field_modelversion_number[$version->language][0]['value'] . '/doc';
    file_prepare_directory($directory, FILE_CREATE_DIRECTORY);
    $file->uri = file_unmanaged_copy($file->uri, $directory, FILE_EXISTS_REPLACE);
    drupal_chmod($file->uri);
    file_save($file);
    file_usage_add($file, 'modellibrary', 'node', $version->nid);
    $version->field_modelversion_documentation[LANGUAGE_NONE][0] = (array) $file;
    $version->field_modelversion_documentation[LANGUAGE_NONE][0]['display'] = 1;

    // Sensitivity
    if ($object->file_sensitivity_fid != 0) {
      $file = file_load($object->file_sensitivity_fid);
      $file->status = FILE_STATUS_PERMANENT;
      $directory = 'public://models/' . $model->nid . '/v' . $version->field_modelversion_number[$version->language][0]['value'] . '/sensitivity';
      file_prepare_directory($directory, FILE_CREATE_DIRECTORY);
      $file->uri = file_unmanaged_copy($file->uri, $directory, FILE_EXISTS_REPLACE);
      drupal_chmod($file->uri);
      file_save($file);
      file_usage_add($file, 'modellibrary', 'node', $version->nid);

      $version->field_modelversion_sensitivity[LANGUAGE_NONE][0] = (array) $file;
      $version->field_modelversion_sensitivity[LANGUAGE_NONE][0]['display'] = 1;
    }

    // Dataset
    if ($object->file_dataset_fid != 0) {
      $file = file_load($object->file_dataset_fid);
      $file->status = FILE_STATUS_PERMANENT;
      $directory = 'public://models/' . $model->nid . '/v' . $version->field_modelversion_number[$version->language][0]['value'] . '/dataset';
      file_prepare_directory($directory, FILE_CREATE_DIRECTORY);
      $file->uri = file_unmanaged_copy($file->uri, $directory, FILE_EXISTS_REPLACE);
      drupal_chmod($file->uri);
      file_save($file);
      file_usage_add($file, 'modellibrary', 'node', $version->nid);

      $version->field_modelversion_dataset[LANGUAGE_NONE][0] = (array) $file;
      $version->field_modelversion_dataset[LANGUAGE_NONE][0]['display'] = 1;
    }

    // Additional Files
    if ($object->file_addfiles_fid != 0) {
      $file = file_load($object->file_addfiles_fid);
      $file->status = FILE_STATUS_PERMANENT;
      $directory = 'public://models/' . $model->nid . '/v' . $version->field_modelversion_number[$version->language][0]['value'] . '/other';
      file_prepare_directory($directory, FILE_CREATE_DIRECTORY);
      $file->uri = file_unmanaged_copy($file->uri, $directory, FILE_EXISTS_REPLACE);
      drupal_chmod($file->uri);
      file_save($file);
      file_usage_add($file, 'modellibrary', 'node', $version->nid);

      $version->field_modelversion_addfiles[LANGUAGE_NONE][0] = (array) $file;
      $version->field_modelversion_addfiles[LANGUAGE_NONE][0]['display'] = 1;
    }
  }

  $version->title = $model->title;
  $version->body[LANGUAGE_NONE][0]['value'] = $object->notes['value'];
  $version->body[LANGUAGE_NONE][0]['format'] = $object->notes['format'];
  $version->field_modelversion_platform[LANGUAGE_NONE][0]['value'] = $object->platform;
  $version->field_modelversion_platform_oth[LANGUAGE_NONE][0]['value'] = $object->platform_other;
  $version->field_modelversion_platform_ver[LANGUAGE_NONE][0]['value'] = $object->platform_version;
  $version->field_modelversion_language[LANGUAGE_NONE][0]['value'] = $object->language;
  $version->field_modelversion_language_oth[LANGUAGE_NONE][0]['value'] = $object->language_other;
  $version->field_modelversion_language_ver[LANGUAGE_NONE][0]['value'] = $object->language_version;
  $version->field_modelversion_os[LANGUAGE_NONE][0]['value'] = $object->os;
  $version->field_modelversion_os_oth[LANGUAGE_NONE][0]['value'] = $object->os_other;
  $version->field_modelversion_os_version[LANGUAGE_NONE][0]['value'] = $object->os_version;
  $version->field_modelversion_license[LANGUAGE_NONE][0]['value'] = $object->license;
  $version->field_modelversion_runconditions[LANGUAGE_NONE][0]['value'] = $object->runconditions['value'];
  $version->field_modelversion_runconditions[LANGUAGE_NONE][0]['format'] = $object->runconditions['format'];

  if ($version = node_submit($version))
    node_save($version);

  drupal_goto('model/' . $model->nid);
}

function modellibrary_attach_form($field_name, $entity_type, $entity, &$form, &$form_state, $langcode = NULL) {
  // Set #parents to 'top-level' if it doesn't exist.
  $form += array('#parents' => array());

  // If no language is provided use the default site language.
  $options = array(
    'language' => field_valid_language($langcode),
    'default' => TRUE,
  );
  module_load_include("inc","ctools","includes/fields");
  $form += (array) modellibrary_field_invoke_field($field_name, 'form', $entity_type, $entity, $form, $form_state, $options);
}

/**
 * Replacement for ctools' ctools_field_invoke_field, which is a replacement
 * for core _field_invoke() to invoke on a single field.
 *
 * ctools' function doesn't permit altering the field's default values, a
 * necessary feature to enable multipage forms.
 *
 * Core only allows invoking field hooks via a private function for all fields
 * on an entire entity. However, we very often need to invoke our hooks on
 * a single field as we take things apart and only use little bits.
 *
 * @param $field_name
 *   Either a field instance object or the name of the field.
 * @param $op
 *   Possible operations include:
 *   - form
 *   - validate
 *   - presave
 *   - insert
 *   - update
 *   - delete
 *   - delete revision
 *   - view
 *   - prepare translation
 * @param $entity_type
 *   The type of $entity; e.g. 'node' or 'user'.
 * @param $entity
 *   The fully formed $entity_type entity.
 * @param $a
 *   - The $form in the 'form' operation.
 *   - The value of $view_mode in the 'view' operation.
 *   - Otherwise NULL.
 * @param $b
 *   - The $form_state in the 'submit' operation.
 *   - Otherwise NULL.
 * @param $options
 *   An associative array of additional options, with the following keys:
 *  - 'field_name': The name of the field whose operation should be
 *    invoked. By default, the operation is invoked on all the fields
 *    in the entity's bundle. NOTE: This option is not compatible with
 *    the 'deleted' option; the 'field_id' option should be used
 *    instead.
 *  - 'field_id': The id of the field whose operation should be
 *    invoked. By default, the operation is invoked on all the fields
 *    in the entity's' bundles.
 *  - 'default': A boolean value, specifying which implementation of
 *    the operation should be invoked.
 *    - if FALSE (default), the field types implementation of the operation
 *      will be invoked (hook_field_[op])
 *    - If TRUE, the default field implementation of the field operation
 *      will be invoked (field_default_[op])
 *    Internal use only. Do not explicitely set to TRUE, but use
 *    _field_invoke_default() instead.
 *  - 'deleted': If TRUE, the function will operate on deleted fields
 *    as well as non-deleted fields. If unset or FALSE, only
 *    non-deleted fields are operated on.
 *  - 'language': A language code or an array of language codes keyed by field
 *    name. It will be used to narrow down to a single value the available
 *    languages to act on.
 *
 * @see _field_invoke()
 */
function modellibrary_field_invoke_field($field_name, $op, $entity_type, $entity, &$a = NULL, &$b = NULL, $options = array(), $default_values = array()) {
  if (is_array($field_name)) {
    $instance = $field_name;
    $field_name = $instance['field_name'];
  }
  else {
    list(, , $bundle) = entity_extract_ids($entity_type, $entity);
    $instance = field_info_instance($entity_type, $field_name, $bundle);
  }

  if (empty($instance)) {
    return;
  }

  // Merge default options.
  $default_options = array(
    'default' => FALSE,
    'deleted' => FALSE,
    'language' => NULL,
  );
  $options += $default_options;

  $return = array();

  // Everything from here is unmodified code from _field_invoke() formerly
  // inside a foreach loop over the instances.
  $field = field_info_field($field_name);
  
  $function = $options['default'] ? 'field_default_' . $op : $field['module'] . '_field_' . $op;
  if (function_exists($function)) {
    // Determine the list of languages to iterate on.
    $available_languages = field_available_languages($entity_type, $field);
    $languages = _field_language_suggestion($available_languages, $options['language'], $field_name);

    foreach ($languages as $langcode) {
      $items = isset($entity->{$field_name}[$langcode]) ? $entity->{$field_name}[$langcode] : array();
      $result = $function($entity_type, $entity, $field, $instance, $langcode, $items, $a, $b);

      if (isset($result)) {
        // For hooks with array results, we merge results together.
        // For hooks with scalar results, we collect results in an array.
        if (is_array($result)) {
          $return = array_merge($return, $result);
        }
        else {
          $return[] = $result;
        }
      }

      // Populate $items back in the field values, but avoid replacing missing
      // fields with an empty array (those are not equivalent on update).
      if ($items !== array() || isset($entity->{$field_name}[$langcode])) {
        $entity->{$field_name}[$langcode] = $items;
      }
    }
  }

  return $return;
}
