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
<table width="100%" class="modellibrary <?php print $class; ?>">
  <?php if (!empty($title)) : ?>
    <caption><?php print $title; ?></caption>
  <?php endif; ?>
  <thead>
    <tr>
      <?php foreach ($header as $field => $label): 
        if (substr($fields[$field], 0, 5) == 'title' || substr($fields[$field], 0, 4) == 'name' || substr($fields[$field], 0, 7) == 'created' || substr($fields[$field], 0, 8) == 'statusid') {
          if (substr($fields[$field], 0, 5) == 'title') {
            print '<th class="modellibrary views-field views-field-'. $fields[$field] .'">';
            print $label;
            print '</th>';
          }
          elseif (substr($fields[$field], 0, 8) == 'statusid') {
            print '<th class="modellibrary views-field views-field-'. $fields[$field] .'">';
            print $label;
            print '</th>';
          }
          else {
            if (substr($fields[$field], 0, 4) == 'name') {
              print '<th class="modellibrary views-field views-field-created">';
              print '<div class="modellibrary views-field views-field-'. $fields[$field] .'">';
              print $label;
              print '</div>';
            }
            else {
              print '<div class="modellibrary views-field views-field-'. $fields[$field] .'">';
              print $label;
              print '</div>';
              print '</th>';
            }
          }
        } 
      ?>
      <?php endforeach; ?>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($rows as $count => $row): ?>
      <tr class="modellibrary <?php print $count; echo ' r1 '; $row_class = implode(' ', $row_classes[$count]); print $row_class; ?>">
        <?php $uri = '';
              global $modelnid;?>
        <?php foreach ($row as $field => $content):

            if ($fields[$field] == 'field-model-uri-value' || $fields[$field] == 'field-fullname-value' || $fields[$field] == 'nid' || $fields[$field] == 'title' || $fields[$field] == 'title active') {
              if ($fields[$field] == 'field-model-uri-value') {
                $uri = $content;
              }
              elseif ($fields[$field] == 'field-fullname-value') {
                $fullname = $content;
              }
              elseif ($fields[$field] == 'nid') {
                $modelnid = $content;
              }
              else {
                print '<td colspan="3" class="modellibrary views-field views-field-'. $fields[$field] .'">';
                print $content;
                print '</td>';
                print '</tr>';
                print '<tr class="modellibrary '. $count .' r2 '. $row_class .'">';
              }
            }
            else {
              if ($fields[$field] == 'field-model-teaser-value') {
                print '<td class="modellibrary views-field views-field-'. $fields[$field] .'">';
                print '<span class="modellibrary views-field views-field-'. $fields[$field] .'">';
                print $content;
                print '</span>';
                print '</td>';
              }
              elseif ($fields[$field] == 'statusid' || $fields[$field] == 'statusid active') {
                print '<td class="modellibrary views-field views-field-'. $fields[$field] .'">';
                if ($content == 60 && (in_array('openabm manager', array_values($user->roles)) || in_array('administrator', array_values($user->roles)))) {
                  print '<img src="sites/all/modules/comses-modelreview/images/certified-badge-small.png" />';
                }
                print '</td>';
              }
              elseif ($fields[$field] == 'name' || $fields[$field] == 'name active') {
                print '<td class="modellibrary views-field views-field-name">';
                print '<div class="modellibrary views-field views-field-'. $fields[$field] .'">';
                print $content;
                print '</div>';
              }
              elseif ($fields[$field] == 'created' || $fields[$field] == 'created active') {
                print '<div class="modellibrary views-field views-field-'. $fields[$field] .'">';
                print $content;
                print '</div>';
                print '</td>';
              }
            }
         ?>

        <?php endforeach; ?>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>
