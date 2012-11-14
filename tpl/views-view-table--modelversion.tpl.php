<?php
// ;Id$

/**
 * @file views-view-table.tpl.php
 * Template to display a view as a table.
 *
 * - $title : The title of this group of rows.  May be empty.
 * - $header: An array of header labels keyed by field id.
 * - $fields: An array of CSS IDs to use for each field id.
 * - $class: A class or classes to apply to the table, based on settings.
 * - $row_classes: An array of classes to apply to each row, indexed by row
 *   number. This matches the index in $rows.
 * - $rows: An array of row items. Each row is an array of content.
 *   $rows are keyed by row number, fields within rows are keyed by field ID.
 * @ingroup views_templates
 */
?>

<?php // Lookup Model Review info, check if this model is certified and which version was certified
  $sql = "SELECT mr.model_nid, mra.rid, mra.sid, mra.statusid, ctmv.field_modelversion_number_value AS version_num "
       . "FROM {modelreview} mr INNER JOIN {modelreview_action} mra ON mr.rid = mra.rid AND mra.statusid = 6 "
       . "LEFT JOIN {content_type_modelversion} ctmv ON ctmv.nid = mra.modelversion_nid WHERE mr.model_nid = %d";
  $queryresult = db_query($sql, arg(1));
  $datarow = db_fetch_object($queryresult);

  //drupal_set_message('is result null: '. is_null($row->model_nid));
  //drupal_set_message('version num: '. $row->version_num);
?>

<table width="100%" id="striped" class="model-vers <?php print $class; ?>">
  <?php if (!empty($title)) : ?>
    <caption><?php print $title; ?></caption>
  <?php endif; ?>
  <thead>
    <tr>
      <?php foreach ($header as $field => $label): 
        if ($fields[$field] != 'field-modelversion-modelnid-value') {
          echo '<th class="views-field views-field-'; print $fields[$field]; echo '">';
            print $label;
          echo '</th>';
        }
      ?>
      <?php endforeach; ?>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($rows as $count => $row): ?>
      <tr class="<?php print $count; echo ' '; $row_class = implode(' ', $row_classes[$count]); print $row_class; ?>">
        <?php
        foreach ($row as $field => $content):
          if ($fields[$field] == 'field-modelversion-modelnid-value') {
            $modelnid = $content;
          }
          elseif ($fields[$field] == 'field-modelversion-number-value') {
            $vnum = $content;
          }
        endforeach;

        foreach ($row as $field => $content):
          if ($fields[$field] == 'field-modelversion-modelnid-value') {
          }
          elseif ($fields[$field] == 'field-modelversion-number-value') {
            print '<td width="100px" onclick="DoNav(\'/model/'. $modelnid .'/version/'. $vnum .'/view\');" class="views-field views-field-'. $fields[$field] .'">';
            print $content;
            if ($datarow->version_num == $content) print ' <img src="/files/images/certified-badge-small.png" />';
            print '</td>';
          }
          elseif ($fields[$field] == 'created') {
            print '<td width="100px" onclick="DoNav(\'/model/'. $modelnid .'/version/'. $vnum .'/view\');" class="views-field views-field-'. $fields[$field] .'">';
            print $content;
            print '</td>';
          }
          elseif ($fields[$field] == 'body') {
            print '<td onclick="DoNav(\'/model/'. $modelnid .'/version/'. $vnum .'/view\');" class="views-field views-field-'. $fields[$field] .'">';
            print $content;
            print '</td>';
          }
        endforeach; ?>
      </tr>

    <?php endforeach; ?>
  </tbody>
</table>
