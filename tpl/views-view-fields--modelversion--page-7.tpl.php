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

  /* Load module include files */
  //module_load_include('inc', 'modellibrary', 'modellibrary.helper.functions');

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
    <td colspan=3>
      <div class="model-authors">
      <?php print $model_view->render_field('field_model_author1first_value', 0) ." ". $model_view->render_field('field_model_author1middle_value', 0) ." ". $model_view->render_field('field_model_author1last_value', 0); ?>
<?php 
      if ($model_view->render_field('field_model_author2last_value', 0) > "") print ', '. $model_view->render_field('field_model_author2first_value', 0) ." ". $model_view->render_field('field_model_author2middle_value', 0) ." ". $model_view->render_field('field_model_author2last_value', 0);
       
      if ($model_view->render_field('field_model_author3last_value', 0) > "") print ', '. $model_view->render_field('field_model_author3first_value', 0) ." ". $model_view->render_field('field_model_author3middle_value', 0) ." ". $model_view->render_field('field_model_author3last_value', 0);
       
      if ($model_view->render_field('field_model_author4last_value', 0) > "") print ', '. $model_view->render_field('field_model_author4first_value', 0) ." ". $model_view->render_field('field_model_author4middle_value', 0) ." ". $model_view->render_field('field_model_author4last_value', 0);
       
?>
      </div>
    </td>
  </tr>
  <tr>
    <td>
      <div class="model-block">
      <div class="model-author1">Submitted By: <?php 
        if ($model_view->render_field('field_profile_lastname_value', 0) > "") 
          print $model_view->render_field('field_profile_firstname_value', 0) ." ". $model_view->render_field('field_profile_middlename_value', 0) ." ". $model_view->render_field('field_profile_lastname_value', 0); 
        else
          print $model_view->render_field('name', 0);
  if (in_array('comses member', array_values($user->roles)) || in_array('administrator', array_values($user->roles))) print ' ('. l($model_view->render_field('name', 0), 'user/'. $model_view->render_field('uid', 0)) .')'; ?>
      </div>
      <div class="model-date">Submitted: <?php print $model_view->render_field('created', 0); ?></div>
      <div class="model-date model-updated-date">Last Updated: <?php print $model_view->render_field('last_updated', 0); ?></div>

      <?php $result = db_query("SELECT code, docs, dataset, sensitivity, other FROM (SELECT SUM(downloads) AS code FROM (SELECT COUNT(dc.fid) AS downloads FROM files files LEFT JOIN download_count dc ON files.fid = dc.fid WHERE dc.fid = files.fid AND (files.fid IN (SELECT DISTINCT n_mv.field_modelversion_code_fid AS code_fid FROM node n LEFT JOIN content_type_modelversion n_mv ON n.vid = n_mv.vid WHERE (n.type in ('modelversion')) AND (n.status = 1) AND (n_mv.field_modelversion_modelnid_value = %d )))) dl) code JOIN (SELECT SUM(downloads) AS docs FROM (SELECT COUNT(dc.fid) AS downloads FROM files files LEFT JOIN download_count dc ON files.fid = dc.fid WHERE dc.fid = files.fid AND (files.fid IN (SELECT DISTINCT n_mv.field_modelversion_documentation_fid AS doc_fid FROM node n LEFT JOIN content_type_modelversion n_mv ON n.vid = n_mv.vid WHERE (n.type in ('modelversion')) AND (n.status = 1) AND (n_mv.field_modelversion_modelnid_value = %d )))) dl) docs JOIN (SELECT SUM(downloads) AS dataset FROM (SELECT COUNT(dc.fid) AS downloads FROM files files LEFT JOIN download_count dc ON files.fid = dc.fid WHERE dc.fid = files.fid AND (files.fid IN (SELECT DISTINCT n_mv.field_modelversion_dataset_fid AS dataset_fid FROM node n LEFT JOIN content_type_modelversion n_mv ON n.vid = n_mv.vid WHERE (n.type in ('modelversion')) AND (n.status = 1) AND (n_mv.field_modelversion_modelnid_value = %d )))) dl) dataset JOIN (SELECT SUM(downloads) AS sensitivity FROM (SELECT COUNT(dc.fid) AS downloads FROM files files LEFT JOIN download_count dc ON files.fid = dc.fid WHERE dc.fid = files.fid AND (files.fid IN (SELECT DISTINCT n_mv.field_modelversion_sensitivity_fid AS sensitivity_fid FROM node n LEFT JOIN content_type_modelversion n_mv ON n.vid = n_mv.vid WHERE (n.type in ('modelversion')) AND (n.status = 1) AND (n_mv.field_modelversion_modelnid_value = %d )))) dl) sensitivity JOIN (SELECT SUM(downloads) AS other FROM (SELECT COUNT(dc.fid) AS downloads FROM files files LEFT JOIN download_count dc ON files.fid = dc.fid WHERE dc.fid = files.fid AND (files.fid IN (SELECT DISTINCT n_mv.field_modelversion_addfiles_fid AS other_fid FROM node n LEFT JOIN content_type_modelversion n_mv ON n.vid = n_mv.vid WHERE (n.type in ('modelversion')) AND (n.status = 1) AND (n_mv.field_modelversion_modelnid_value = %d )))) dl) other", $modelnid, $modelnid, $modelnid, $modelnid, $modelnid);

      $row = db_fetch_object($result); ?>
      <div class="model-downloads"><?php print ($row->code + $row->docs + $row->dataset + $row->sensitivity + $row->other); ?> Downloads</div>
      </div>
    </td>
    <td>

<?php $sql = "SELECT mr.model_nid, mra.rid, mra.sid, mra.statusid "
          ."FROM {modelreview} mr INNER JOIN {modelreview_action} mra ON mr.rid = mra.rid AND mr.sid = mra.sid "
          ."WHERE mr.model_nid = %d";
    $result = db_query($sql, $modelnid);
    $row = db_fetch_object($result);

    if ($row->statusid == 6) {
print '      <div class="model-badge"><img src="/files/certified-badge-big.png" /></div>';
    } ?>
    </td>
    <td>
      <?php 
        if ($model_view->render_field('status', 0) == "False" && $model_view->render_field('field_model_enabled_value', 0) == "Enabled" && (in_array('administrator', array_values($user->roles)) || $user->uid == $model_view->render_field('uid', 0))) {
          echo '<a class="model-button" href="'. url('model/'. $modelnid .'/enable') .'">Enable<br />Model</a>';
        }

        if ($model_view->render_field('status', 0) == "True" && (in_array('administrator', array_values($user->roles)) || $user->uid == $model_view->render_field('uid', 0))) {
          echo '<a class="model-button" href="'. url('model/'. $modelnid .'/disable') .'">Disable Model</a>';
        }

        if ($fields['field_modelversion_number_value']->content != modellibrary_helper_get_max_versionnum($modelnid)) {
          print '<a class="model-button" href="'. url('model/'. $modelnid) .'">Latest Version</a>';
        }
      ?>
    </td>
  </tr>
</table>
<div class="hrline" style="margin: 5px 0 0 0;"></div>
<table style="margin: 0;" border="0" width="100%">
  <tr valign="top" style="padding-top: 10px;">
    <td>
      <div class="model-region1">
        <div class="model-body">
          <?php print $model_view->render_field('body', 0); ?>
        </div>
        <?php 
        $view_args = array($modelnid);
        $display_id = 'page_3';
        $tags_view = views_get_view('model');
        $tags_view->set_arguments($view_args);
        $tags_view->set_display($display_id);
        $tags_view->pre_execute();
        $tags_view->execute();

        $i = 1;
        foreach ($tags_view->result as $id => $result) {
          if ($i == 1 && $tags_view->render_field('field_model_tags_value', $id) > '') {
            print '        <div class="model-tags-block">';
            print '          <div class="model-block-title">Keywords:</div>';
            print '          <div class="model-tags">';
            print '<span class="tag"><a class="tag" href="/models/'. $tags_view->render_field('field_model_tags_value', $id) .'/tag">'. $tags_view->render_field('field_model_tags_value', $id) .'</a></span>';
            $i = -1;
          } elseif ($i == -1)
            print '<span class="tag"><a class="tag" href="/models/'. $tags_view->render_field('field_model_tags_value', $id) .'/tag">'. $tags_view->render_field('field_model_tags_value', $id) .'</a></span>';
        }
        if ($i == -1) {
          print '          </div>';
          print '        </div>';
        }

#        $view_args = array($model_view->render_field('field_model_publication_value', 0));
#        $display_id = 'page_1';
#        $biblio_view = views_get_view('biblio');
#        $biblio_view->set_arguments($view_args);
#        $biblio_view->set_display($display_id);
#        $biblio_view->pre_execute();
#        $biblio_view->execute();

        if ($model_view->render_field("field_model_publication_text_value", 0)) {
          print '<div class="model-block model-publication-block">';
          print '  <div class="model-block-title model-publication-title">This model is associated with a publication:</div>';
          print '  <div class="model-publication-text"><p>'. $model_view->render_field("field_model_publication_text_value", 0) .'</p></div>';
          print '</div>';
        }
        
        if ($model_view->render_field("field_model_replicated_value", 0) == "Replicated") {
          print '<div class="model-block model-replication-block">';
          print '  <div class="model-block-title model-replication-title">This is a replication of a previously published model:</div>'; 
          print '  <div class="model-replication-text"><p>'. $model_view->render_field("field_model_reference_value", 0) .'</p></div>';
          print '</div>';
        }
        ?>
        <div class="model-block model-citation-block">
          <div class="model-block-title model-citation-title">Cite This Model:</div>
          <div class="model-citation-text"><p>
            <?php if ($model_view->render_field('field_model_author1last_value', 0) > "") { print $model_view->render_field('field_model_author1last_value', 0) .', '. $model_view->render_field('field_model_author1first_value', 0); if ($model_view->render_field('field_model_author1middle_value', 0) > "") print ' '. $model_view->render_field('field_model_author1middle_value', 0); } else print $model_view->render_field('name', 0); if ($model_view->render_field('field_model_author2last_value', 0) > "") { print ', '. $model_view->render_field('field_model_author2last_value', 0) .', '. $model_view->render_field('field_model_author2first_value', 0); if ($model_view->render_field('field_model_author2middle_value', 0) > "") print ' '. $model_view->render_field('field_model_author2middle_value', 0); } if ($model_view->render_field('field_model_author3last_value', 0) > "") { print ', '. $model_view->render_field('field_model_author3last_value', 0) .', '. $model_view->render_field('field_model_author3first_value', 0); if ($model_view->render_field('field_model_author3middle_value', 0) > "") print ' '. $model_view->render_field('field_model_author3middle_value', 0); } if ($model_view->render_field('field_model_author4last_value', 0) > "") { print ', '. $model_view->render_field('field_model_author4last_value', 0) .', '. $model_view->render_field('field_model_author4first_value', 0); if ($model_view->render_field('field_model_author4middle_value', 0) > "") print ' '. $model_view->render_field('field_model_author4middle_value', 0); } ?> (<?php print $fields['created']->content; ?>). "<?php print $model_view->render_field('title', 0); ?>" (Version <?php print $fields['field_modelversion_number_value']->content; ?>). Retrieved from OpenABM: <?php if ($model_view->render_field('field_model_handle_value', 0) > "") { print $model_view->render_field('field_model_handle_value', 0); } else { global $base_url; global $base_path; print $base_url . $base_path .'model/'. $modelnid .'/version/'. $fields['field_modelversion_number_value']->content; } ?>
          </p></div>
        </div>
      </div>
    </td>
    <td width=175>
      <div class="model-region2">
        <div class="model-image">
          <?php print $model_view->render_field('field_model_image_fid', 0); ?>
        </div>
        <div class="model-video">
          <?php 
          if ($model_view->render_field('field_model_video_fid', 0) != "") {
            print '<a href="/'. $model_view->render_field('field_model_video_fid', 0) .'" rel="shadowbox;width=480;height=320"><img width="175" src="/files/video_thumbnail.png" /></a>';
          }
          ?>&nbsp;
        </div>
      </div>
    </td>
  </tr>
<?php
  if (in_array('administrator', array_values($user->roles)) || in_array('openabm manager', array_values($user->roles))) { // also if Certified
    // Generate Model Review status info. Determine the current Review Status Code
    // Lookup the Review
    $sql = "SELECT mr.model_nid, mra.rid, mra.sid, mra.statusid "
          ."FROM {modelreview} mr INNER JOIN {modelreview_action} mra ON mr.rid = mra.rid AND mr.sid = mra.sid "
          ."WHERE mr.model_nid = %d";
    $result = db_query($sql, $modelnid);
    $row = db_fetch_object($result);
    
    switch ($row->statusid) {
      case 1:
      case 2:
      case 3:
      case 4:
      case 5:

        $message = '<div class="model-certification-text"><p>This model is currently under review. To view the review status page, click "Review Status" below.</p></div>';
        $message .= '<div class="model-block"><a class="model-submit-button" href="'. $base_url . $base_path .'model/'. $modelnid .'/review/status">Review Status</a></div>';
        break;

      case 6:
        $message = '<div class="model-certification-text"><p>This model has been Certified that it meets the CoMSES Guidelines for Modeling Best-Practices. Certification involves a review process by which a model is examined to ensure it has been coded and documented according to the community\'s best-practices. Click the "More Info" button below for more information on the Model Certification process.</p></div>';
        //$message .= '<div class="model-block"><a class="model-submit-button" href="'. $base_url . $base_path .'model/'. $modelnid .'/review/status">Review Status</a></div>';
        break;

      case 7:
        break;

      default:
        $message = '<div class="model-certification-text"><p>This model can be reviewed for CoMSES Certification. Certification involves a review process by which a model is examined to ensure it has been coded and documented according to the community\'s best-practices. Click the "More Info" button below for more information on the Model Certification process.</p></div>';
        $message .= '<div class="model-block"><a class="model-submit-button" href="'. $base_url . $base_path .'model/'. $modelnid .'/review/info">More Info</a></div>';

    }

    if ($message > "") {
      print '  <tr>';
      print '    <td colspan=2>';
      print '      <div style="margin: 0;" class="hrline"/>';
      print '    </td>';
      print '  </tr>';
      print '  <tr>';
      print '    <td>';
      print '      <div class="model-region1">';
      print '      <div class="model-section-title">Model Certification</div>';
      print $message;
      print '      </div>';
      print '    </td>';
      print '  </tr>';
    }
  }
  if ($model_view->render_field('status', 0) == "False" || $model_view->render_field('field_model_enabled_value', 0) != "Enabled" || $fields['field_modelversion_number_value']->content != modellibrary_helper_get_max_versionnum($modelnid)) {
print '  <tr>
    <td colspan=2>
      <div style="margin: 0;" class="hrline"/>
    </td>
  </tr>
  <tr>
    <td>
      <div class="modelstatus">
        <h2>Model Status</h2>';

        // if not latest version
        if ($fields['field_modelversion_number_value']->content != modellibrary_helper_get_max_versionnum($modelnid)) {
          print "<h3>You are viewing an old version of this model with out-of-date file downloads.  To view the latest model version, click the \"Latest\" button above.<h3>";
        } // if not latest version

        // if not published and model can be enabled
        if ($model_view->render_field('status', 0) == "False" && $model_view->render_field('field_model_enabled_value', 0) == "Enabled") {
          print "<h3>This model is currently disabled.";
          if (in_array('administrator', array_values($user->roles)) || $user->uid == $model_view->render_field('uid', 0)) {
            print "To enable it, click the Enable button.";
          }
          print "</h3>";
        } // if not published and model can be enabled
        elseif ($model_view->render_field('field_model_enabled_value', 0) != "Enabled") {
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
        } // elseif model is not ready to be enabled due to errors
print '      </div>
    </td>
  </tr>';
      }
      ?>
</table>
<div class="hrline" style="margin: 5px 0 0 0;"></div>
<table style="margin: 0;" border="0" width="100%">
  <tr>
    <td width=50%>
      <div class="model-region3">
      <div class="model-section-title">
        Model Version: <?php print $fields['field_modelversion_number_value']->content; if ($fields['field_modelversion_number_value']->content == modellibrary_helper_get_max_versionnum($model_view->render_field('nid', 0))) print '  [Latest]'; ?>
      </div>

      <?php if ($fields["body"]->content > '') print '      <div class="model-block"><span class="model-block-title">Version Notes:</span><span class="model-block-text"><p>'. $fields["body"]->content .'</p></span></div>'; ?>

      <div class="model-block model-version-block">
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

        print '<div class="model-version-item"><span class="model-block-title">Platform:</span><span class="model-block-text"> <a href="'. $platform_url .'">'. $fields['field_modelversion_platform_value']->content .'</a> '. $fields['field_modelversion_platformver_value']->content .'</span></div>';

        print '<div class="model-version-item"><span class="model-block-title">Programming Language:</span><span class="model-block-text"> ';
        if ($fields['field_modelversion_language_value']->content == "Other") {
          print $fields['field_modelversion_otherlang_value']->content ." ". $fields['field_modelversion_langversion_value']->content ."</span></div>";
        }
        else {
          print $fields['field_modelversion_language_value']->content ." ". $fields['field_modelversion_langversion_value']->content ."</span></div>";
        }

        print '<div class="model-version-item"><span class="model-block-title">Operating System:</span><span class="model-block-text"> '. $fields['field_modelversion_os_value']->content .' '. $fields['field_modelversion_osversion_value']->content .'</span></div>';

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

        print '<div class="model-version-item"><span class="model-block-title">Licensed Under:</span> <span class="model-block-text"><a href="'. $license_url .'">'. $fields['field_modelversion_license_value']->content .'</a></span></div>';
        print '</div>'; 

        print '<div class="model-block"><span class="model-block-title">Instructions on Running This Model:</span>';
        if ($fields["field_modelversion_runconditions_value"]->content == "")
          print '<span class="model-block-text"> None.</span>';
        else
          print '<div model-block-text>'. $fields['field_modelversion_runconditions_value']->content .'</div>';
      ?></p>
      </div>
    </td>
    <td width=50%>
      <div class="model-region4">
      <div class="model-section-title">Model Files</div>
      <div class='model-files'>
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
      </div>
    </td>
  </tr>
</table>
<?php if (modellibrary_helper_get_max_versionnum($model_view->render_field('nid', 0)) > 1) {
  print '<div class="hrline" style="margin: 5px 0 0 0;"></div>';
  print '<div class="model-region5 versions-list">';
  $view_args = array($modelnid);
  $display_id = 'page_3';
  $version_view = views_get_view('modelversion');
  $version_view->set_arguments($view_args);
  $version_view->set_display($display_id);
  $version_view->pre_execute();
  $version_view->execute();
  
  if (count($version_view->result) > 0) {
    print '<div class="model-section-title">Available Model Versions</div>';
  }

  if (!empty($version_view)) {
    print $version_view->execute_display($display_id , $view_args);
  }
  print '</div>';
  }
  drupal_set_title(check_plain($model_view->render_field('title', 0)));
?>

