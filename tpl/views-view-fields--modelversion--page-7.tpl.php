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

  global $user;

  $modelnid = arg(1);
  $version = arg(3);

  $view_args = array($modelnid);
  $display_id = 'page_6';
  $model_view = views_get_view('model');
  $model_view->set_arguments($view_args);
  $model_view->set_display($display_id);
  $model_view->pre_execute();
  $model_view->execute();

//watchdog('modellibrary', 'modelversion-page-7 (37): field ver num: '. $fields['field_modelversion_number_value']->content);
?>
<table style="margin: 0;" border="0" width="100%">
  <tr>
    <td>
      <span class="model-author1">By:</span>
      <span class="model-author2"> <?php 
        if ($model_view->render_field('field_fullname_value', 0) > "") 
          print $model_view->render_field('field_fullname_value', 0); 
        else
          print $model_view->render_field('name', 0);
        if (in_array('comses member', array_values($user->roles)) || in_array('administrator', array_values($user->roles))) print ' ('. $model_view->render_field('name', 0) .')'; ?></span>
      <div class="model-updated">Last Update: <?php print $model_view->render_field('last_updated', 0); ?></div>
    </td>
    <td>
      <div class="model-alerts">
      </div>

      <div class="model-buttons">
        <div id='basic-modal'>
          <a href='#' class='basic model-button' style="float: right; margin-top: 5px;">How To Cite</a>
        </div>

        <!-- modal content -->
        <div id="basic-modal-content">
          <h3>How To Cite This Model</h3>
          <p>To share this model with others, use one of the following blocks of text.  Because this model may change over time, it is important, particularly in citation usage, that this link is clearly annotated to be associated with the specific version that exists at this time.</p>
          <p>Sharing Text:</p>
          <p><code><?php print $model_view->render_field('title', 0); ?> - Version <?php print $fields['field_modelversion_number_value']->content; ?> : http://www.openabm.org/model/<?php print $modelnid; ?>/version/<?php print $fields['field_modelversion_number_value']->content; ?></code></p>
          <p><code>http://www.openabm.org/model/<?php print $modelnid; ?>/version/<?php print $fields['field_modelversion_number_value']->content; ?></code></p>
        </div>

        <!-- preload the images -->
        <div style='display:none'>
          <img src='img/basic/x.png' alt='' />
        </div>
        <?php 
          if ($model_view->render_field('status', 0) == "True" && $model_view->render_field('field_model_enabled_value', 0) != "Enabled" && (in_array('administrator', array_values($user->roles)) || $user->uid == $model_view->render_field('uid', 0))) {
            echo '<a class="model-button" style="float: left; margin-left: 10px; margin-top: 5px;" href="http://www.openabm.org/model/'. $modelnid .'/enable">Enable</a>';
          }

          if ($model_view->render_field('field_model_enabled_value', 0) == "Enabled" && (in_array('administrator', array_values($user->roles)) || $user->uid == $model_view->render_field('uid', 0))) {
            echo '<a class="model-button" style="float: left; margin-left: 10px; margin-top: 5px;" href="http://www.openabm.org/model/'. $modelnid .'/disable">Disable</a>';
          }

          if ($fields['field_modelversion_number_value']->content != helper_get_max_versionnum($modelnid)) {
            print '<a class="model-button" style="float: left; margin-left: 10px; margin-top: 5px;" href="http://www.openabm.org/model/'. $modelnid .'">Latest</a>';
          }
        ?>
      </div>
    </td>
  </tr>
  <tr>
    <td colspan=2>
      <div class="model-tags">
    <?php 
      $view_args = array($modelnid);
      $display_id = 'page_3';
      $tags_view = views_get_view('model');
      $tags_view->set_arguments($view_args);
      $tags_view->set_display($display_id);
      $tags_view->pre_execute();
      $tags_view->execute();

      foreach ($tags_view->result as $id => $result) {
        print '<a class="tag" href="/models/'. $tags_view->render_field('field_model_tags_value', $id) .'/tag">'. $tags_view->render_field('field_model_tags_value', $id) .'</a>';
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
        <div class="model-pub">
          <?php

          $view_args = array($model_view->render_field('field_model_publication_value', 0));
          $display_id = 'page_1';
          $biblio_view = views_get_view('biblio');
          $biblio_view->set_arguments($view_args);
          $biblio_view->set_display($display_id);
          $biblio_view->pre_execute();
          $biblio_view->execute();

          if ($model_view->render_field("field_model_publication_text_value", 0))
            print "<p><strong>This model is associated with a publication:</strong></p>";
            //print "<p>". $biblio_view->render_field("citation", 0) ."</p>";
            print "<p>". $model_view->render_field("field_model_publication_text_value", 0) ."</p>";
          ?>
        </div>
        <div class="model-repl">
          <?php if ($model_view->render_field("field_model_replicated_value", 0) == "Replicated") 
            print "<p><strong>This is a replication of a previously published model:</strong></p>"; 
            print "<p>". $model_view->render_field("field_model_reference_value", 0) ."</p>";
          ?>
        </div>

      <?php
//watchdog('modellibrary', 'views-view-fields-modelversion-page-7.tpl.php (125): enabled: '. $model_view->render_field('field_model_enabled_value', 0));
        if ($model_view->render_field('field_model_featured_value', 0) == "Featured" || $model_view->render_field('status', 0) == "False" || $model_view->render_field('field_model_enabled_value', 0) != "Enabled" || $fields['field_modelversion_number_value']->content != helper_get_max_versionnum($modelnid)) {
          echo '<div class="modelstatus">';
          echo '<h2>Model Status</h2>';
          if ($model_view->render_field('field_model_featured_value', 0) == "Featured") {
            print "<p>This is an OpenABM Featured Model.</p>";
          }

          if ($fields['field_modelversion_number_value']->content != helper_get_max_versionnum($modelnid)) {
            print "<h3>You are viewing an old version of this model with out-of-date file downloads.  To view the latest model version, click the \"Latest\" button above.<h3>";
          }

          if ($model_view->render_field('status', 0) == "True" && $model_view->render_field('field_model_enabled_value', 0) != "Enabled") {
            print "<h3>This model is currently disabled.";
            if (in_array('administrator', array_values($user->roles)) || $user->uid == $model_view->render_field('uid', 0)) {
              print "To enable it, click the Enable button.";
            }
            print "</h3>";
          }
          elseif ($model_view->render_field('status', 0) == "False") {
            print "<h3>This model is currently disabled. To enable it, the following issues must be resolved:</h3>";

            print "<ol>";
            $issuecount = 0;

            if ($model_view->render_field('field_model_replicated_value', 0) == "Replicated" && $model_view->render_field('field_model_reference_value', 0) == "") {
              print "<li>Model is flagged as a replication, but no reference is given for the original model.";
              $issuecount++;
            }

            if (($version->field_modelversion_language == 7 || $version->field_modelversion_language == '') && $version->field_modelversion_otherlang == "") {
              print "<li>No language selected or \"Other\" category is selected but not specified.";
              $issuecount++;
            }

            if ($version->field_modelversion_os == '') {
              print "<li>No OS selected.";
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
      </div>
    </td>
    <td width=250>
      <div class="model-region3">
        <div class="model-image">
          <?php print $model_view->render_field('field_model_image_fid', 0); ?>
        </div>
      </div>
      <div class="model-video">
        <?php 
        if ($model_view->render_field('field_model_video_fid', 0) != "") {
          print '<a href="/'. $model_view->render_field('field_model_video_fid', 0) .'" rel="shadowbox;width=480;height=320"><img width="250" height="150" src="/files/video_thumbnail.png" /></a>';
        }
        ?>&nbsp;
      </div>
      <?php $result = db_query("SELECT code, docs, dataset, sensitivity, other FROM (SELECT SUM(downloads) AS code FROM (SELECT COUNT(dc.fid) AS downloads FROM files files LEFT JOIN download_count dc ON files.fid = dc.fid WHERE dc.fid = files.fid AND (files.fid IN (SELECT DISTINCT n_mv.field_modelversion_code_fid AS code_fid FROM node n LEFT JOIN content_type_modelversion n_mv ON n.vid = n_mv.vid WHERE (n.type in ('modelversion')) AND (n.status = 1) AND (n_mv.field_modelversion_modelnid_value = %d )))) dl) code JOIN (SELECT SUM(downloads) AS docs FROM (SELECT COUNT(dc.fid) AS downloads FROM files files LEFT JOIN download_count dc ON files.fid = dc.fid WHERE dc.fid = files.fid AND (files.fid IN (SELECT DISTINCT n_mv.field_modelversion_documentation_fid AS doc_fid FROM node n LEFT JOIN content_type_modelversion n_mv ON n.vid = n_mv.vid WHERE (n.type in ('modelversion')) AND (n.status = 1) AND (n_mv.field_modelversion_modelnid_value = %d )))) dl) docs JOIN (SELECT SUM(downloads) AS dataset FROM (SELECT COUNT(dc.fid) AS downloads FROM files files LEFT JOIN download_count dc ON files.fid = dc.fid WHERE dc.fid = files.fid AND (files.fid IN (SELECT DISTINCT n_mv.field_modelversion_dataset_fid AS dataset_fid FROM node n LEFT JOIN content_type_modelversion n_mv ON n.vid = n_mv.vid WHERE (n.type in ('modelversion')) AND (n.status = 1) AND (n_mv.field_modelversion_modelnid_value = %d )))) dl) dataset JOIN (SELECT SUM(downloads) AS sensitivity FROM (SELECT COUNT(dc.fid) AS downloads FROM files files LEFT JOIN download_count dc ON files.fid = dc.fid WHERE dc.fid = files.fid AND (files.fid IN (SELECT DISTINCT n_mv.field_modelversion_sensitivity_fid AS sensitivity_fid FROM node n LEFT JOIN content_type_modelversion n_mv ON n.vid = n_mv.vid WHERE (n.type in ('modelversion')) AND (n.status = 1) AND (n_mv.field_modelversion_modelnid_value = %d )))) dl) sensitivity JOIN (SELECT SUM(downloads) AS other FROM (SELECT COUNT(dc.fid) AS downloads FROM files files LEFT JOIN download_count dc ON files.fid = dc.fid WHERE dc.fid = files.fid AND (files.fid IN (SELECT DISTINCT n_mv.field_modelversion_addfiles_fid AS other_fid FROM node n LEFT JOIN content_type_modelversion n_mv ON n.vid = n_mv.vid WHERE (n.type in ('modelversion')) AND (n.status = 1) AND (n_mv.field_modelversion_modelnid_value = %d )))) dl) other", $modelnid, $modelnid, $modelnid, $modelnid, $modelnid);

      $row = db_fetch_object($result); ?>

      <div class="model-dl">
        <h2>Download Counts</h2>
        <?php
          print "<div><span class='label model-dl-code-label'>Code:</span>";
          print "<span class='content model-dl-code'>". $row->code ."</span></div>";
        ?>
        <?php
          print "<div><span class='label model-dl-docs-label'>Documentation:</span>";
          print "<span class='content model-dl-docs'>". $row->docs ."</span></div>";
        ?>
        <?php if ($row->dataset > 0) {
          print "<div><span class='label model-dl-dataset-label'>Dataset:</span>";
          print "<span class='content model-dl-dataset'>". $row->dataset ."</span></div>";
        } ?>
        <?php if ($row->sensitivity > 0) {
          print "<div><span class='label model-dl-sensitivity-label'>Sensitivity Analysis:</span>";
          print "<span class='content model-dl-sensitivity'>". $row->sensitivity ."</span></div>";
        } ?>
        <?php if ($row->other > 0) {
          print "<div><span class='label model-dl-other-label'>Other Files:</span>";
          print "<span class='content model-dl-other'>". $row->other ."</span></div>";
        } ?>
      </div>
    </td>
  </tr>
</table>
<div class="hrline" style="margin: 5px 0 0 0;"></div>
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

      <div class='model-files'>
        <h2>Download Files</h2>
        <?php if ($fields['field_modelversion_code_fid']->content > "") {
          print "<div><span class='label'>Code:</span>";
          print "<span class='content'>". $fields['field_modelversion_code_fid']->content ."</span></div>";
        } ?>
        <?php if ($fields['field_modelversion_documentation_fid']->content > "") {
          print "<div><span class='label'>Docs:</span>";
          print "<span class='content'>". $fields['field_modelversion_documentation_fid']->content ."</span></div>";
        } ?>
        <?php if ($fields['field_modelversion_dataset_fid']->content > "") {
          print "<div><span class='label'>Dataset:</span>";
          print "<span class='content'>". $fields['field_modelversion_dataset_fid']->content ."</span></div>";
        } ?>
        <?php if ($fields['field_modelversion_sensitivity_fid']->content > "") {
          print "<div><span class='label'>Sensitivity Analysis:</span>";
          print "<span class='content'>". $fields['field_modelversion_sensitivity_fid']->content ."</span></div>";
        } ?>
        <?php if ($fields['field_modelversion_addfiles_fid']->content > "") {
          print "<div><span class='label'>Other Files:</span>";
          print "<span class='content'>". $fields['field_modelversion_addfiles_fid']->content ."</span></div>";
        } ?>
      </div>
    </td>
    <td width="255px">
      <h2>Version Details</h2>
      <?php
        switch ($fields['field_modelversion_platform_value']->content) {
          case 'Ascape 5':
            $platform_url = 'http://ascape.sourceforge.net/';
            break;

          case 'Breve':
            $platform_url = 'http://www.spiderland.org/';
            break;

          case 'Cormas':
            $platform_url = 'http://cormas.cirad.fr/en/outil/outil.htm';
            break;

          case 'DEVSJAVA':
            $platform_url = 'http://www.acims.arizona.edu/SOFTWARE/software.shtml';
            break;

          case 'Ecolab':
            $platform_url = 'http://ecolab.sourceforge.net/';
            break;

          case 'Mason':
            $platform_url = 'http://www.cs.gmu.edu/~eclab/projects/mason/';
            break;

          case 'MASS':
            $platform_url = 'http://mass.aitia.ai/';
            break;

          case 'MobiDyc':
            $platform_url = 'http://w3.avignon.inra.fr/mobidyc/index.php/English_summary';
            break;

          case 'NetLogo':
            $platform_url = 'http://ccl.northwestern.edu/netlogo/';
            break;

          case 'Repast':
            $platform_url = 'http://repast.sourceforge.net/';
            break;

          case 'Sesam':
            $platform_url = 'http://www.simsesam.de/';
            break;

          case 'StarLogo':
            $platform_url = 'http://education.mit.edu/starlogo/';
            break;

          case 'Swarm':
            $platform_url = 'http://www.swarm.org/';
            break;
        }

        print "<p><strong>Platform:</strong> <a href=\"". $platform_url ."\">". $fields['field_modelversion_platform_value']->content ."</a> ". $fields['field_modelversion_platformver_value']->content ."</p>";

        print "<p><strong>Programming Language:</strong> ";
        if ($fields['field_modelversion_language_value']->content == "Other") {
          print $fields['field_modelversion_otherlang_value']->content ." ". $fields['field_modelversion_langversion_value']->content ."</p>";
        }
        else {
          print $fields['field_modelversion_language_value']->content ." ". $fields['field_modelversion_langversion_value']->content ."</p>";
        }

        print "<p><strong>Operating System:</strong> ". $fields['field_modelversion_os_value']->content ." ". $fields['field_modelversion_osversion_value']->content ."</p>";

        switch ($fields['field_modelversion_license_value']->content) {
          case 'GNU GPL, Version 2':
            $license_url = 'http://www.gnu.org/licenses/gpl-2.0.html';
            break;

          case 'GNU GPL, Version 3':
            $license_url = 'http://www.gnu.org/licenses/gpl-3.0.html';
            break;

          case 'Apache License, Version 2.0':
            $license_url = 'http://www.apache.org/licenses/LICENSE-2.0.html';
            break;

          case 'Creative Commons (cc by)':
            $license_url = 'http://creativecommons.org/licenses/by/3.0/';
            break;

          case 'Creative Commons(cc by-sa)':
            $license_url = 'http://creativecommons.org/licenses/by-sa/3.0/';
            break;

          case 'Creative Commons (cc by-nd)':
            $license_url = 'http://creativecommons.org/licenses/by-nd/3.0';
            break;

          case 'Creative Commons (cc by-nc)':
            $license_url = 'http://creativecommons.org/licenses/by-nc/3.0';
            break;

          case 'Creative Commons (cc by-nc-sa)':
            $license_url = 'http://creativecommons.org/licenses/by-nc-sa/3.0';
            break;

          case 'Creative Commons (cc by-nc-nd)':
            $license_url = 'http://creativecommons.org/licenses/by-nc-nd/3.0';
            break;

          case 'Academic Free License 3.0':
            $license_url = 'http://www.opensource.org/licenses/afl-3.0.php';
            break;
        }

        print "<p><strong>Licensed Under:</strong> <a href=\"". $license_url ."\">". $fields['field_modelversion_license_value']->content ."</a></p>";


      ?>
    </td>
  </tr>
</table>
<?php if (helper_get_max_versionnum($model_view->render_field('nid', 0)) > 1) {
  print '<div class="versions-list">';
  $view_args = array($modelnid);
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

