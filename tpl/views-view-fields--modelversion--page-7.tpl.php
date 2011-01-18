<?php
// ;Id$

/**
 * @file views-view-fields.tpl.php
 * Default simple view template to all the fields as a row.
 *
 * - $view: The view in use.
 * - $fields: an array of $field objects. Each one contains:
 *   - $field->content: The output of the field.
 *   - $field->raw: The raw data for the field, if it exists. This is NOT output safe.
 *   - $field->class: The safe class id to use.
 *   - $field->handler: The Views field handler object controlling this field. Do not use
 *     var_export to dump this object, as it can't handle the recursion.
 *   - $field->inline: Whether or not the field should be inline.
 *   - $field->inline_html: either div or span based on the above flag.
 *   - $field->separator: an optional separator that may appear before a field.
 * - $row: The raw result object from the query, with all data it fetched.
 *
 * @ingroup views_templates
 */

  $view_args = array(arg(1));
  $display_id = 'page_6';
  $model_view = views_get_view('model');
  $model_view->set_arguments($view_args);
  $model_view->set_display($display_id);
  $model_view->pre_execute();
  $model_view->execute();

  $uri = arg(1);
  $version = arg(3);
?>

<script>
  ZeroClipboard.setMoviePath(drupal_get_path('theme', 'openabm') .'/includes/zeroclipboard/ZeroClipboard.swf');
  var clip = new ZeroClipboard.Client();
  clip.setText('');
  clip.addEventListener('mouseDown', function(){
    clip.setText('http://dev.comses.asu.edu/');
  });
  clip.glue('button-share');
</script>

<table style="margin: 0;" border="0" width="100%">
  <tr>
    <td>
      <span class="model-author1">By:</span>
      <span class="model-author2"> <?php print $model_view->render_field('field_fullname_value', 0) .' ('. $model_view->render_field('name', 0) .')'; ?></span>
      <div class="model-updated">Last Update:<?php print $model_view->render_field('last_updated', 0); ?></div>
    </td>
    <td>
      <div class="model-alerts">
      </div>
      <div class="model-buttons">
        <a id="button-share" class="button" style="float: right; margin-top: 5px;" href="#">Share</a>
        <?php 
          if ($model_view->render_field('status', 0) == "True" && $model_view->render_field('field_model_enabled_value', 0) == "Disabled") {
            echo '<a class="button" style="float: left; margin-left: 10px; margin-top: 5px;" href="http://dev.comses.asu.edu/model/'. $model_view->render_field('field_model_uri_value', 0) .'/enable">Enable</a>';
          }
        ?>
      </div>
    </td>
  </tr>
  <tr>
    <td colspan=2>
      <div class="model-tags">
    <?php 
      $view_args = array(arg(1));
      $display_id = 'page_3';
      $tags_view = views_get_view('model');
      $tags_view->set_arguments($view_args);
      $tags_view->set_display($display_id);
      $tags_view->pre_execute();
      $tags_view->execute();
      
      foreach ($tags_view->result as $id => $result) {
        print '<a class="tag" href="#">'. $tags_view->render_field('field_model_tags_value', $id) .'</a>';
      }
    ?>
      </div>
    </td>
  </tr>
  <tr>
    <td colspan=2>
      <div style="margin: 0;" class="hrline"/>
    </td>
  </tr>
  <tr valign="top" style="padding-top: 10px;">
    <td>
      <div class="model-region1">
        <div class="model-body" style="margin-top: 10px">
          <?php print $model_view->render_field('body', 0); ?>
        </div>
      </div>
      <div class="model-region2">
        <div class="model-repl">
          <?php if ($model_view->render_field("field_model_replicated_value", 0) == "Replicated") 
            print "<p><strong>This is a replication of a previously published model:</strong></p>"; 
            print "<p>". $model_view->render_field("field_model_reference_value", 0) ."</p>";
          ?>
        </div>
      </div>
    </td>
    <td>
      <div class="model-region3">
        <div class="model-image">
          <?php print $model_view->render_field('field_model_image_fid', 0); ?>
        </div>
      </div>
    </td>
  </tr>
  <tr>
    <td>
      <?php
        if ($model_view->render_field('field_model_featured_value', 0) == "Featured" || $model_view->render_field('status', 0) == "False" || $model_view->render_field('field_model_enabled_value', 0) == "Disabled") {
          echo '<div class="modelstatus">';
          echo '<h2>Model Status</h2>';
          if ($model_view->render_field('field_model_featured_value', 0) == "Featured") {
            print "<p>This is an OpenABM Featured Model.</p>";
          }


//watchdog('modellibrary', 'views-view-fields-modelversion-page-7.tpl.php (124): status: '. $model_view->render_field('status', 0));
//watchdog('modellibrary', 'views-view-fields-modelversion-page-7.tpl.php (125): enabled: '. $model_view->render_field('field_model_enabled_value', 0));

          if ($model_view->render_field('status', 0) == "True" && $model_view->render_field('field_model_enabled_value', 0) == "Disabled") {
            print "<h3>This model is currently disabled. To enable it, click the Enable button.</h3>";
          }
          elseif ($model_view->render_field('status', 0) == "False") {
            print "<h3>This model is currently disabled. To enable it, the following issues must be resolved:</h3>";
            
            print "<ol>";
            $issuecount = 0;

            if ($model_view->render_field('field_model_teaser_value', 0) == "") {
              print "<li>Teaser text is missing.";
              $issuecount++;
            }

            if ($model_view->render_field('field_model_replicated_value', 0) == "Replicated" && $model_view->render_field('field_model_reference_value', 0) == "") {
              print "<li>Model is flagged as a replication, but no reference is given for the original model.";
              $issuecount++;
            }
           
            if ($model_view->render_field('body', 0) == "") {
              print "<li>Model description is missing.";
              $issuecount++;
            }

            if ($fields['field_modelversion_code_fid']->content == NULL) {
              print "<li>Code file is missing.";
              $issuecount++;
            }

            if ($fields['field_modelversion_documentation_fid']->content == NULL) {
              print "<li>Documentation file is missing.";
              $issuecount++;
            }

            if ($version->field_modelversion_language == 7 && $version->field_modelversion_otherlang == "") {
              print "<li>Other language category is selected but not specified.";
              $issuecount++;
            }

            if ($issuecount == 0) {
              print '<li>No issues prevent this model from being active. Click the "Enable" button when you are ready to publish it.';
            }
            print "</ol>";
          }
          echo '</div>';
        }
      ?>
    </td>
    <td>
        <div class="model-video">
          <?php 
          if ($model_view->render_field('field_model_video_fid', 0) != "") {
            print '<a href="http://dev.comses.asu.edu/'. $model_view->render_field('field_model_video_fid', 0) .'" rel="shadowbox;width=480;height=320"><img width="250" height="150" src="/files/video_thumbnail.png" /></a>';
          }
          ?>
        </div>
    </td>
  </tr>
</table>
<table style="margin: 0;" border="0" width="100%">
  <tr>
    <td>
      <h2>Model Version: 
        <?php print $fields['field_modelversion_number_value']->content; 
          if ($fields['field_modelversion_number_value']->content == helper_get_max_versionnum($model_view->render_field('nid', 0))) {
            print '  [Latest]';
          }
        ?>
      </h2>
      <p><?php print $fields["body"]->content; ?></p>
      <p><strong>Instructions on Running This Model:</strong>
      <?php
        if ($fields["field_modelversion_runconditions_value"]->content == "")
          print " None.";
        else
          print $fields["field_modelversion_runconditions_value"]->content;
      ?></p>
    </td>
    <td width="255px">
    </td>
  </tr>
  <tr>
    <td>
      <h2>Download Files</h2>
      <div>
      <?php  
        print $fields['field_modelversion_code_fid']->content;
        print $fields['field_modelversion_documentation_fid']->content;
        print $fields['field_modelversion_dataset_fid']->content;
        print $fields['field_modelversion_sensitivity_fid']->content;
        print $fields['field_modelversion_addfiles_fid']->content;
      ?>
      </div>
    </td>
    <td>
      <h2>Version Details</h2>
      <?php
        print "<p><strong>Platform:</strong> ". $fields['field_modelversion_platform_value']->content ." ". $fields['field_modelversion_platformver_value']->content ."</p>";

        print "<p><strong>Programming Language:</strong> ";
        if ($fields['field_modelversion_language_value']->content == "Other") {
          print $fields['field_modelversion_otherlang_value']->content ." ". $fields['field_modelversion_langversion_value']->content ."</p>";
        }
        else {
          print $fields['field_modelversion_language_value']->content ." ". $fields['field_modelversion_langversion_value']->content ."</p>";
        }

        print "<p><strong>Operating System:</strong> ". $fields['field_modelversion_os_value']->content ." ". $fields['field_modelversion_osversion_value']->content ."</p>";

        print "<p><strong>Licensed Under:</strong> ". $fields['field_modelversion_license_value']->content ."</p>";


      ?>
    </td>
  </tr>
</table>
<?php if (helper_get_max_versionnum($model_view->render_field('nid', 0)) > 1) {
  print '<div class="versions-list">';
  $view_args = array(arg(1));
  $display_id = 'page_3';
  $version_view = views_get_view('modelversion');
  $version_view->set_arguments($view_args);
  $version_view->set_display($display_id);
  $version_view->pre_execute();
  $version_view->execute();
  
  if (count($version_view->result) > 0) {
    print '<h2>All Versions</h2>';
  }

  if (!empty($version_view)) {
    print $version_view->execute_display($display_id , $view_args);
  }
  print '</div>';
  }
  drupal_set_title(check_plain($model_view->render_field('title', 0)));
?>

