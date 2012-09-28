<?php

define(MODEL_STEPS, 6);  // Number of steps in _model_add_form()

/* Implementation of Drupal Form API hook_form
 *
 * This form is used for both adding new Models and editing existing Models.
 * This dual functionality is handled through url arguments.  Additionally,
 * this form handles both the Model and Model Version content types.  The goal
 * is to make model versions as unobtrusive as possible.  Currently, when a model
 * is added or updated, this form and its submit code processes both content types
 * and determines automatically whether a model version is to be updated or a
 * new version needs to be created.  Currently, any changes to Version fields
 * other than the uploaded files will only result in the latest version being
 * being updated. If any of the version files are changed, then a new Version
 * is created.  No versioning is done with the Model node, but we might consider
 * merging Model and Version types into a single Model content type, which is
 * versioned as a single unit.
 */
function _model_add_form($form_state) {
  module_load_include('inc', 'content', 'includes/content.node_form');  // Must have this in order to call content_field_form later.

  if (arg(2) == 'edit') {
    $action = 'edit';
    $modelnid = arg(1);
  }
  else {
    $action = 'add';
  }
  $action_text = "";

  if ($action == 'edit') {
    $model = node_load($modelnid);

    // Now we need to look up the Version NID
    $view_args = array($modelnid);
    $display_id = 'page_1'; // Latest Version view
    $version_view = views_get_view('modelversion');
    $version_view->set_arguments($view_args);
    $version_view->set_display($display_id);
    $version_view->pre_execute();
    $version_view->execute();

    $versionnid = $version_view->render_field('nid', 0);
    $version = node_load($versionnid);

    $action_text = "Edit";
  } 
  else {
    $action_text = "Add New";
    $versionnum = helper_get_max_versionnum($modelnid);
  }

  if (!isset($form_state['storage']['step'])) {
    $form_state['storage']['step'] = 1;
    // set the form encoding type
    $form['#attributes']['enctype'] = "multipart/form-data";
  }
  
  // If this is an edit, we need to pull in the current values from the node
  // The values stored in this array will be used to populate form fields unless
  // a value has already been entered into that field previously in this session
  // (this allows the user to step back and forward through the form pages
  // without losing the data they have entered or changed.)
  if ($action == 'edit') {
    $node = node_load($modelnid);
    $edit_value = array();
    $edit_value['title'] = $node->title;
    $edit_value['description'] = $node->body;
    $edit_value['field_model_uri'] = $node->field_model_uri[0]['value'];
    $edit_value['field_model_teaser'] = $node->field_model_teaser[0]['value'];
    $edit_value['field_model_replicated'] = $node->field_model_replicated[0]['value'];
    //watchdog('modellibrary', 'modellibrary.module (532): replicated: '. $default_value['field_model_replicated'], NULL, WACHDOG_INFO);
    $edit_value['field_model_reference'] = $node->field_model_reference[0]['value'];
    $edit_value['field_model_publication_text'] = $node->field_model_publication_text[0]['value'];
    //watchdog('modellibrary', 'modellibrary.module (534): reference: '. $default_value['field_model_reference'], NULL, WACHDOG_INFO);
    //$default_value['field_model_enabled'] = $node->field_model_enabled[0]['value'];
    $edit_value['field_model_image'] = $node->field_model_image;
    $edit_value['field_model_video'] = $node->field_model_video;
    $edit_value['field_model_tags'] = $node->field_model_tags;

    $node = _load_modelversion($modelnid);
    $edit_value['notes'] = $node->body;
    $edit_value['field_modelversion_language'] = $node->field_modelversion_language;
    $edit_value['field_modelversion_otherlang'] = $node->field_modelversion_otherlang;
    $edit_value['field_modelversion_langversion'] = $node->field_modelversion_langversion;
    $edit_value['field_modelversion_os'] = $node->field_modelversion_os;
    $edit_value['field_modelversion_osversion'] = $node->field_modelversion_osversion;
    $edit_value['field_modelversion_platform'] = $node->field_modelversion_platform;
    $edit_value['field_modelversion_platformver'] = $node->field_modelversion_platformver;
    $edit_value['field_modelversion_license'] = $node->field_modelversion_license;
    $edit_value['field_modelversion_review'] = $node->field_modelversion_review;
    $edit_value['runconditions'] = $node->field_modelversion_runconditions[0]['value'];
    $edit_value['field_modelversion_code'] = $node->field_modelversion_code;
    $edit_value['field_modelversion_documentation'] = $node->field_modelversion_documentation;
    $edit_value['field_modelversion_sensitivity'] = $node->field_modelversion_sensitivity;
    $edit_value['field_modelversion_dataset'] = $node->field_modelversion_dataset;
    $edit_value['field_modelversion_addfiles'] = $node->field_modelversion_addfiles;
  }

  //Don't lose our old data when returning to a page with data already typed in.
  //$default_value = '';
  if (isset($form_state['storage']['values'])) {
    $default_value = $form_state['storage']['values'];
  }

  // We need to set the page title since executing the views earlier caused
  // those views to change the page title.  This line has to be after
  // all executed views.
  drupal_set_title($action_text .' Model - Step '. $form_state['storage']['step'] . ' of '. MODEL_STEPS);

  switch ($form_state['storage']['step']) {
    /*case 1: // Page 1
      $form['help'] = array(
        '#value' => '<div class="setoff"><h3>Instructions.</h3></div>',
        '#weight' => -20,
      );

      break; */

    case 1: // page 1
      $form['title'] = array(
        '#type' => 'textfield',
        '#title' => t('Title'),
        '#description' => t('This is the name of the model. [REQUIRED]'),
        '#size' => 60,
        '#maxlength' => 128,
        '#default_value' => isset($default_value['title']) ? $default_value['title'] : $edit_value['title'],
        //'#weight' => -5,
        '#required' => TRUE,
        //'#element_validate' => array('_model_add_element_validate'),
      );

      $form['field_model_teaser'] = array(
        '#type' => 'textarea',
        '#title' => t('Teaser'),
        '#description' => t('You must provide a short summary of the model.  This teaser is displayed in the library model listings and must not exceed 250 characters. [REQUIRED]'),
        '#cols' => 50,
        '#rows' => 3,
        '#default_value' => isset($default_value['field_model_teaser']) ? $default_value['field_model_teaser'] : $edit_value['field_model_teaser'],
        //'#weight' => 0,
        '#required' => TRUE,
      );

      $form['body_filter']['description'] = array(
        '#type' => 'textarea',
        '#title' => t('Description'),
        '#description' => t('This field contains the model\'s full description.  Please be clear about the model\'s purpose and design. [REQUIRED]'),
        '#cols' => 50,
        '#rows' => 10,
        '#default_value' => isset($default_value['description']) ? $default_value['description'] : $edit_value['description'],
        '#required' => TRUE,
      );
      $form['body_filter']['filter'] = filter_form($model->format);
      //$form['body_filter']['#weight'] = -3;

      /* The following block uses CCK's internal functions to retrieve and render
       * the CCK fields.  The advantage to accessing the data in this manner
       * is that CCK takes care of generating the widgets on our form, including
       * file and image uploads, taxonomy tags, and so on. These fields are
       * configured in the Content Type management screens.
       */
      $fields = array('field_model_tags'); // array list of the fields to be accessed
      foreach ($fields as $name) {
        //Create the field by calling the CCK API:
        $field = content_fields($name);
        // This is a non-display container for passing the field information along:
        $form['#field_info'][$name] = $field;
        // If you set the field value here it will be used as default for the field,
        // see line 67 of file content.node_form.inc.:
        $form_state['values'][$name] = isset($default_value[$name]) ? $default_value[$name] : $edit_value[$name];
        // This does the element rendering:
        $element = (array)content_field_form($form, $form_state, $field);
        // Add the fields to the form:
        $form += $element;
      }

      break;

    case 2: // page 2

      $form['field_model_publication_text'] = array(
        '#type' => 'textarea',
        '#title' => t('Model Reference'),
        '#description' => t('If this model has been published, you should provide the bibliographic reference to that publication.'),
        '#cols' => 50,
        '#rows' => 5,
        //'#weight' => 8,
        '#default_value' => isset($default_value['field_model_publication_text']) ? $default_value['field_model_publication_text'] : $edit_value['field_model_publication_text'],
      ); 

      $form['replication'] = array(
        '#type' => 'fieldset',
        '#title' => t('Model Replication Info'),
      );

      $form['replication']['field_model_replicated'] = array(
        '#type' => 'checkbox',
        '#title' => t('Replicated Model?'),
        '#description' => t('If this is checked, the model is a replication of another model, published or otherwise. You must also provide the bibliographic reference to that model in the field below.'),
        '#default_value' => isset($default_value['field_model_replicated']) ? $default_value['field_model_replicated'] : $edit_value['field_model_replicated'],
        //'#element_validate' => array('_model_add_element_validate'),
        //'#weight' => 7,
      );
      $form['replication']['field_model_reference'] = array(
        '#type' => 'textarea',
        '#title' => t('Replication Reference'),
        '#description' => t('If this model is a replication of another model, you must provide the bibliographic reference for the original model implementation.'),
        '#cols' => 50,
        '#rows' => 5,
        //'#weight' => 8,
        '#default_value' => isset($default_value['field_model_reference']) ? $default_value['field_model_reference'] : $edit_value['field_model_reference'],
      ); 

      // Generate the CCK fields
      $fields = array('field_model_image', 'field_model_video');
      foreach ($fields as $name) {
        $field = content_fields($name);
        $form['#field_info'][$name] = $field;
        $form_state['values'][$name] = isset($default_value[$name]) ? $default_value[$name] : $edit_value[$name];
        $element = (array)content_field_form($form, $form_state, $field);
        $form += $element;
      }
      break;

    case 3: // Page 3
      /*$form['help'] = array(
        '#value' => '<div class="setoff"><h3>Instructions for Page 3.</h3></div>',
        '#weight' => -20,
      ); */

      $form['version_number'] = array(
        '#type' => 'value',
        '#value' => $versionnum,
      );

      $form['notes'] = array(
        '#type' => 'textarea',
        '#title' => t('Version Notes'),
        '#description' => t('If you have specific notes about this version of the model, distinct from the general model details, enter that information here.'),
        '#cols' => 50,
        '#rows' => 5,
        '#default_value' => isset($default_value['notes']) ? $default_value['notes'] : $edit_value['notes'],
      );
      //$form['body_filter']['filter'] = filter_form($version->format);
      //$form['body_filter']['#weight'] = -3;

      // Generate the CCK fields
      $fields = array('field_modelversion_language', 'field_modelversion_otherlang', 'field_modelversion_langversion', 'field_modelversion_os', 'field_modelversion_osversion', 'field_modelversion_platform', 'field_modelversion_platformver');
      foreach ($fields as $name) {
        $field = content_fields($name);
        $form['#field_info'][$name] = $field;
        $form_state['values'][$name] = isset($default_value[$name]) ? $default_value[$name] : $edit_value[$name];
        $element = (array)content_field_form($form, $form_state, $field);
        $form += $element;
      }
      break;

    case 4: // page 4
      $form['help'] = array(
        '#value' => '<div class="setoff"><p>Upload the files for your model.  At a minimum, you must upload the code and documentation for this model.  Users may find it very helpful if you also share any sensitivity analysis information, datasets, or other files that can be used with your model or for gaining understanding into its functionality.</p><p>When editing this model after it has been created, any changes to these files will result in a new model version being created.</p></div>',
        '#weight' => -20,
      );

      // CCK Fields
      $fields = array('field_modelversion_code', 'field_modelversion_documentation', 'field_modelversion_sensitivity', 'field_modelversion_dataset', 'field_modelversion_addfiles');
      foreach ($fields as $name) {
        $field = content_fields($name);
        $form['#field_info'][$name] = $field;
        $form_state['values'][$name] = isset($default_value[$name]) ? $default_value[$name] : $edit_value[$name];
        $element = (array)content_field_form($form, $form_state, $field);
        $form += $element; 
      }
      break;

    case 5: // page 5
      $form['help'] = array(
        '#value' => '<div class="setoff"><p>Select a software license that you wish to apply to your model.  Each license establishes a set of permissions and restrictions under which you allow others to use your code.  These licenses range from quite liberal (Creative Commons Attribution 3.0) to relatively restrictive.   (This help information is incomplete and requires additional work.)</p></div>',
        '#weight' => -20,
      );

      // CCK Fields
      $fields = array('field_modelversion_license');
      foreach ($fields as $name) {
        $field = content_fields($name);
        $form['#field_info'][$name] = $field;
        $form_state['values'][$name] = isset($default_value[$name]) ? $default_value[$name] : $edit_value[$name];
        $element = (array)content_field_form($form, $form_state, $field);
        $form += $element; 
      }

      $form['runconditions'] = array(
        '#type' => 'textarea',
        '#title' => t('Operating Instructions'),
        '#description' => t('If your model requires any specific steps or setup procedures in order to run, please provide that information here and be as complete and thorough as possible, to ensure model users can successfully run the model.'),
        '#cols' => 50,
        '#rows' => 5,
        '#weight' => 19,
        '#default_value' => isset($default_value['runconditions']) ? $default_value['runconditions'] : $edit_value['runconditions'],
      );

      break;

    case 6: // page 6
      $form['help'] = array(
        '#value' => '<div class="setoff"><h3>Save Model.</h3><p>You may now save your model by clicking "Submit" or return to previous pages by clicking "Previous" to review your data entry and make changes.</p><p>This model will not be automatically visible to the public.  First, the Model Library will scan your model for any incomplete fields you may have overlooked.  If for any reason, the model is not ready to be published, the Model viewing page will display a notice for you, informing you of the reasons the model is not ready to be enabled.</p><p>Second, if there are no issues with the model itself, you must choose to enable the model in order to make it publicly viewable and searchable.  You may choose to keep a model disabled, if for example it is associated with a publication under review, and you do not wish to make the model public until the paper has been published.</p><h3>Sharing your model with editors and reviewers.</h3><p>While a model is disabled, it will not appear in the Model Library listings nor in search results, but you can share your model with specific individuals by clicking on the "Share" button on your model\'s page, which will give you the web address that directly links to your model.  You may email that information or include it in your article submission material so reviewers can include your model in their review process, if necessary.</p><p>Click "Submit" to save your model now and go to your new model.</p></div>',
        '#weight' => -20,
      );

      break;
  }

  // Back button
  if ($form_state['storage']['step'] > 1) {
    $form['back'] = array( //corresponds to 'edit-back' in form_submit
      '#value' => '<< Previous',
      '#type' => 'submit',
      '#weight' => 20
    );
  }

  // Cancel button
  $form['cancel'] = array(
    '#type' => 'button',
    '#value' => 'Cancel',
    '#weight' => 22,
    '#executes_submit_callback' => TRUE,
    '#submit' => array('_model_add_cancel'),
  );
  $form['#after_build'][] = '_model_add_form_after_build'; // Needed to disable form validation, so our cancel can be performed

  //'Next' appears at every step except for the final page as 'Submit'
  if ($form_state['storage']['step'] < MODEL_STEPS) {
    $form['next'] = array(//corresponds to 'edit-next' in form_submit
      '#type' => 'submit',
      '#value' => 'Next >>',
      '#weight' => 21
    );
  }
  else {
    $form['next'] = array(//corresponds to 'edit-next' in form_submit
      '#type' => 'submit',
      '#value' => 'Submit',
      '#weight' => 21
    );
  }

  return $form;
}

function _model_add_cancel() {
  if (arg(1) == 'add') {
    drupal_goto('models/browse');
  } else {
    drupal_goto('model/'. arg(1));
  }
}


/**
 * Implementation of hook_validate().
 *
 */
function _model_add_form_validate($form, &$form_state) {
  if (arg(2) == 'edit') {
    $action = 'edit';
    $modelnid = arg(1);
  }
  else {
    $action = 'add';
  }

  if($form_state['clicked_button']['#value'] != 'Cancel') { // we don't want to process any validation, if the user clicked Cancel
    if ($form_state['storage']['step'] == 1) { 
      if ($action == 'add' && helper_check_if_cck_fieldvalue_exists('field_model_uri', $form_state['values']['field_model_uri'])) {
        form_set_error('', t('That URI is already in use.  Please select a different value.'));
      }
    }

    if ($form_state['storage']['step'] == 2 && $form_state['values']['field_model_replicated'][0]['value'] == 1 ) {
      form_set_error('', t('You have flagged this model as a replication.  You must also enter the bibliographic reference to the original model implementation.'));
    }
  }
}

/**
 * Called after form has been submitted.  Used here to disable form validation when the
 * Cancel button has been clicked. (Otherwise cancelling during a model Add, the cancel
 * will fail if required fields have not been filled in.)
 */
function _model_add_form_after_build($form, &$form_state) {
  if($form_state['clicked_button']['#value'] == 'Cancel') {
    _model_add_form_disable_validation($form);
  }
  return $form;
}

/**
 * Recursively remove #required validation flags on fields in current form.
 */
function _model_add_form_disable_validation(&$element) {
  unset($element['#needs_validation']);
  foreach(element_children($element) as $key) {
    _model_add_form_disable_validation($element[$key]);
  }
}

/**
 * Implementation of hook_submit().
 *
 */
function _model_add_form_submit($form, &$form_state) {
  //multistep processing. If this is the first page, we need to create the 
  // storage area for previous pages of the form
  if (!$form_state['storage']['values']) {
    $form_state['storage']['values'] = array();
  }

  //increment the button, overwrite the storage values with the new values, and return
  if ($form_state['clicked_button']['#id'] == 'edit-back') {
    $form_state['storage']['step']--;
    $form_state['storage']['values'] = $form_state['storage']['values'] + $form_state['values'];
    return;
  } 
  elseif ($form_state['clicked_button']['#id'] == 'edit-next' && $form_state['storage']['step'] < MODEL_STEPS) {
    $form_state['storage']['step']++;
    $form_state['storage']['values'] = $form_state['storage']['values'] + $form_state['values'];
    return;
  }

  //Once this is the last step, save the data
  global $user;  // allows us to look up the logged-in user data

  // this is required to run drupal_execute later when saving the nodes.
  module_load_include('inc', 'node', 'node.pages'); 
  
  if (arg(2) == 'edit') {
    $action = 'edit';
    $modelnid = arg(1);
  }
  else {
    $action = 'add';
    $uri = $form_state['storage']['values']['field_model_uri'];
  }

  $model = new stdClass();

  if ($action == 'edit') {
    $model = node_load($modelnid); // if we are editing, load the model
  }
  else {
    $model->type = 'model'; // otherwise prep the new node
  }

  // Setup form_state array for the drupal_execute function.
  // This might be a little cumbersome, but I am not sure
  // drupal_execute can process the field values
  // we stored in $form_state[storage]
  $new_state = array();
  $node_updated = FALSE; // flag to indicate whether any fields have been changed in an update

  // drupal_execute() will use the values in $new_state for generating the new node
  $new_state['values']['title'] = $form_state['storage']['values']['title'];
  $modeltitle = $new_state['values']['title']; // will need this later for the Version node
  $new_state['values']['body'] = $form_state['storage']['values']['description'];

  $new_state['values']['type'] = 'model';
  $new_state['values']['revision'] = 0;
  $new_state['values']['revision_moderation'] = 0;
  $new_state['values']['promote'] = 0;
  $new_state['values']['sticky'] = 0;
  $new_state['values']['format'] = 1;
  $new_state['values']['language'] = 'en';
  if ($action == 'add') {
    $new_state['values']['name'] =  $user->name;
    $new_state['values']['status'] = 0;
    $new_state['values']['field_model_enabled'][0]['value'] = 1;
  }

  $new_state['values']['field_model_teaser'][0]['value'] = $form_state['storage']['values']['field_model_teaser'];
  $new_state['values']['field_model_public'][0]['value'] = $form_state['storage']['values']['field_model_public'];
  $new_state['values']['field_model_featured'][0]['value'] = $form_state['storage']['values']['field_model_featured'];
  $new_state['values']['field_model_publication_text'][0]['value'] = $form_state['storage']['values']['field_model_publication_text'];
  $new_state['values']['field_model_replicated'][0]['value'] = $form_state['storage']['values']['field_model_replicated'];
  $new_state['values']['field_model_reference'][0]['value'] = $form_state['storage']['values']['field_model_reference'];

  $new_state['values']['field_model_tags'] = $form_state['storage']['values']['field_model_tags'];

  // The imagefield has to be loaded into the temp node variable in order for drupal_execute() to process it.  
  $model->field_model_image = $form_state['storage']['values']['field_model_image'];
  // Like the imagefield, this field has to be loaded into the temp node variable in order for drupal_execute() to process it.
  $model->field_model_video = $form_state['storage']['values']['field_model_video'];
  $new_state['values']['csm_create_msg_en'] = '';
  $new_state['values']['csm_update_msg_en'] = '';
  $new_state['values']['op'] = t('Save');  // This seems to be a required value

  // There is an alternate method for saving nodes using node_save().  However,
  // node_save() doesn't handle the Image and Video CCK fields properly (or at least
  // I was never able to figure out how to get it to work properly.) node_save() is
  // more commonly used and discussed in the Drupal community, however there are
  // pros and cons with each method.  node_save() doesn't process any validation or
  // preprocess settings configured for a content type.  drupal_execute does, but it
  // also is heavier than node_save() for processing overhead (probably because of the
  // preprocessing, which to my mind is really the intended behavior in Drupal.)
  drupal_execute('model_node_form', $new_state, (object) $model); 
  $errors = form_get_errors();  if (count($errors)) {
    // Deal with any errors generated by drupal_execute()
    drupal_set_message("Error saving model version: \n". $errors);
  }

  if ($action == 'add')
    $modelnid = $new_state['nid']; // drupal_execute stores the nid of the new node here in the form array

  //Now we need to process the version data and add a new version if necessary
  $version = new stdClass();

  if ($action == 'edit') {
    $version = _load_modelversion($modelnid);  
  } 

  // Setup form_state array for the drupal_execute function.
  $new_state = array();  
 
  $new_state['values']['title'] = $form_state['storage']['values']['title'] ." - Version ". helper_get_max_versionnum($modelnid);
  $new_state['values']['body'] = $form_state['storage']['values']['notes'];
  $new_state['values']['type'] = 'modelversion';  // drupal_execute() will use these values for generating the new node
  $new_state['values']['status'] = 1;
  $new_state['values']['promote'] = 0;
  $new_state['values']['sticky'] = 0;
  $new_state['values']['format'] = 1;
  $new_state['values']['language'] = 'en';
  $new_state['values']['name'] =  $user->name;
  $new_state['values']['field_modelversion_modelnid'][0]['value'] = $modelnid;
  //if ($action == 'new') 
  //  $new_state['values']['field_modelversion_modeluri'][0]['value'] = $uri;
  $new_state['values']['field_modelversion_language'] = $form_state['storage']['values']['field_modelversion_language'];
  $new_state['values']['field_modelversion_otherlang'] = $form_state['storage']['values']['field_modelversion_otherlang'];
  $new_state['values']['field_modelversion_langversion'] = $form_state['storage']['values']['field_modelversion_langversion'];
  $new_state['values']['field_modelversion_os'] = $form_state['storage']['values']['field_modelversion_os'];
  $new_state['values']['field_modelversion_osversion'] = $form_state['storage']['values']['field_modelversion_osversion'];
  $new_state['values']['field_modelversion_platform'] = $form_state['storage']['values']['field_modelversion_platform'];
  $new_state['values']['field_modelversion_platformver'] = $form_state['storage']['values']['field_modelversion_platformver'];
  $new_state['values']['field_modelversion_license'] = $form_state['storage']['values']['field_modelversion_license'];
  $new_state['values']['field_modelversion_review'] = $form_state['storage']['values']['field_modelversion_review'];
  $new_state['values']['field_modelversion_runconditions'][0]['value'] = $form_state['storage']['values']['runconditions'];
  $new_state['values']['csm_create_msg_en'] = '';
  $new_state['values']['csm_update_msg_en'] = '';
  $new_state['values']['op'] = t('Save');  // This seems to be a required value

//watchdog('modellibrary', 'modellibrary.module (995): runconditions: '. $form_state['storage']['values']['runconditions']);

  $new_version = FALSE;  // flag to indicate a new version needs to be created.

  // If we are in Edit mode, then we need to check to see which fields have
  // been changed.  For most fields, if any changes have been made, we want to update
  // the current version.  However, if any of the file upload fields have been changed
  // we are going to create a new version.
  if ($action == 'edit') {  
    if ($version->field_modelversion_code[0]['fid'] != $form_state['storage']['values']['field_modelversion_code'][0]['fid']) {
      $new_version = TRUE;
    } 
    elseif ($version->field_modelversion_documentation[0]['fid'] != $form_state['storage']['values']['field_modelversion_documentation'][0]['fid']) {
      $new_version = TRUE;
    } 
    elseif ($version->field_modelversion_sensitivity[0]['fid'] != $form_state['storage']['values']['field_modelversion_sensitivity'][0]['fid']) {
      $new_version = TRUE;
    } 
    elseif ($version->field_modelversion_dataset[0]['fid'] != $form_state['storage']['values']['field_modelversion_dataset'][0]['fid']) {
      $new_version = TRUE;
    } 
    elseif ($version->field_modelversion_addfiles[0]['fid'] != $form_state['storage']['values']['field_modelversion_addfiles'][0]['fid']) {
      $new_version = TRUE;
    }
  } 
  else {
    $new_version = TRUE;    
  }

  if ($new_version) {
    $version = new stdClass();
    $version->type = 'modelversion';
    $new_state['values']['title'] = $form_state['storage']['values']['title'] ." - Version ". helper_get_next_versionnum($model->nid);
    $new_state['values']['field_modelversion_number'][0]['value'] = helper_get_next_versionnum($model->nid);
    $new_state['values']['field_modelversion_uri'][0]['value'] = $form_state['storage']['values']['field_model_uri'];
  }

  $version->field_modelversion_code = $form_state['storage']['values']['field_modelversion_code']; 
  $version->field_modelversion_documentation = $form_state['storage']['values']['field_modelversion_documentation']; 
  $version->field_modelversion_sensitivity = $form_state['storage']['values']['field_modelversion_sensitivity']; 
  $version->field_modelversion_dataset = $form_state['storage']['values']['field_modelversion_dataset'];  
  $version->field_modelversion_addfiles = $form_state['storage']['values']['field_modelversion_addfiles']; 

  //clear the storage and rebuild so the form doesn't rebuild and #redirect works
  //$form_state['rebuild'] = '';
  //unset($form_state['storage']);

  drupal_execute('modelversion_node_form', $new_state, (object) $version); 
  $errors = form_get_errors();

  if (count($errors)) {
    // Deal with any errors generated by drupal_execute()
    drupal_set_message("Error saving model version: \n". $errors);
  }


  drupal_goto('model/'. $modelnid);
}