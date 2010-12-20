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
?>

<table margin="0" border="1" width="100%">
  <tr>
    <td>
      <div class="model-author">
        <h3>Author: <?php print $fields["field_fullname_value"]->content; ?></h3>
      </div>
      <div class="model-updated">
        Last Update: <?php print $fields["last_updated"]->content; ?>
      </div>
    </td>
    <td>
      <div class="model-alerts">
        <p>Alerts are displayed here as flags.</p>
      </div>
      <div class="model-buttons">
        <a class="beautytips" style="float: right" title="Click this button to share" href="#">Share</a>
        <a class="postnav" style="float: left" href="#">Publish</a>
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
  <tr valign="top">
    <td>
      <div class="model-region1">
        <div class="model-body">
          <?php print $fields["body"]->content; ?>
        </div>
      </div>
    </td>
    <td>
      <div class="model-region2">
        <div class="model-image">
          <?php print $fields["field_model_image_fid"]->content; ?>
        </div>
      </div>
    </td>
  </tr>
  <tr>
    <td>
      <div>
        <h2>Model Status</h2>
        <?php 
          if ($fields["field_model_featured_value"]->content == "Featured") {
            print "<p>This is an OpenABM Featured Model.</p>";
          }

          if ($fields["status"]->content == FALSE || $fields["field_model_enabled_value"]->content == "Disabled") {
            print "<p>This model is currently disabled. To enable it, the following issues must be resolved:</p>";
            
            $view_args = array(arg(1));
            $display_id = 'page_3';
            $version_view = views_get_view('modelversion');
            $version_view->set_arguments($view_args);
            $version_view->set_display($display_id);
            $version_view->pre_execute();
            $version_view->execute();

            if (count($version_view->result) == 0) {
              print "<p>No versions have been published.</p>";
            }
            else {
              print '<p>No issues prevent this model from being active. Click the "Enable" button when you are ready to publish it.</p>';
            }
          }

        ?>
      </div>
    </td>
    <td>
        <div class="model-video">
          <?php 
          if ($fields["field_model_video_fid"]->content != "") {
            print '<a href="http://dev.comses.asu.edu/'. $fields["field_model_video_fid"]->content .'" rel="shadowbox;width=480;height=320"><img width="250" height="150" src="/files/video_thumbnail.png" /></a>';
          }
          ?>
        </div>
    </td>
  </tr>
  <tr valign="top">
    <td>
      <div class="region3">
        <div class="model-repl">
          <?php if ($fields["field_model_replicated_value"]->content == "Replicated") 
            print "<h2>Replication</h2><p><strong>This model is a replication of another published model:</strong></p>"; 
            print "<p>". $fields["field_model_reference_value"]->content ."</p>";
          ?>
        </div>
      </div>
    </td>
    <td>
      <div class="region4">
      </div>
    </td>
  </tr>
  <?php
    $view_args = array(arg(1));
    $display_id = 'page_1';
    $version_view = views_get_view('modelversion');
    $version_view->set_arguments($view_args);
    $version_view->set_display($display_id);
    $version_view->pre_execute();
    $version_view->execute();
  ?>
  <tr>
    <td>
      <h2>Current Version:</h2>
      <p><?php print $fields["body"]->content; ?></p>
    </td>
    <td>
      <p><strong>Special Instructions on Running This Model:</strong>
      <?php
        if ($fields["field_modelversion_runconditions"]->content == "")
          print " None.";
        else
          print $fields["field_modelversion_runconditions"]->content;
      ?></p>
    </td>
  </tr>
  <tr>
    <td>
      <h2>Download Files</h2>
      <div>
      <?php  
        print $version_view->render_field('field_modelversion_code_fid', 0);
        print $version_view->render_field('field_modelversion_documentation_fid', 0);
        print $version_view->render_field('field_modelversion_dataset_fid', 0);
        print $version_view->render_field('field_modelversion_sensitivity_fid', 0);
        print $version_view->render_field('field_modelversion_addfiles_fid', 0);
      ?>
      </div>
    </td>
    <td>
      <h2>Version Details</h2>
      <?php
        print "<p><strong>Platform:</strong> ". $version_view->render_field('field_modelversion_platform_value', 0) ." ". $version_view->render_field('field_modelversion_platformver_value', 0) ."</p>";

        print "<p><strong>Programming Language:</strong> ";
        if ($version_view->render_field('field_modelversion_language_value', 0) == "Other") {
          print $version_view->render_field('field_modelversion_otherlang_value', 0) ." ". $version_view->render_field('field_modelversion_langversion_value', 0) ."</p>";
        }
        else {
          print $version_view->render_field('field_modelversion_language_value', 0) ." ". $version_view->render_field('field_modelversion_langversion_value', 0) ."</p>";
        }

        print "<p><strong>Operating System:</strong> ". $version_view->render_field('field_modelversion_os_value', 0) ." ". $version_view->render_field('field_modelversion_osversion_value', 0) ."</p>";

        print "<p><strong>Licensed Under:</strong> ". $version_view->render_field('field_modelversion_license_value', 0) ."</p>";


      ?>
    </td>
  </tr>
</table>

<?php 
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
  drupal_set_title(check_plain($fields["title"]->content));
?>
