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
<table width="100%" class="modellibrary<?php print $class; ?>">
  <?php if (!empty($title)) : ?>
    <caption><?php print $title; ?></caption>
  <?php endif; ?>
  <thead>
    <tr>
      <?php foreach ($header as $field => $label): 
        if (substr($fields[$field], 0, 5) == 'title' || substr($fields[$field], 0, 7) == 'created') {
          if (substr($fields[$field], 0, 5) == 'title') {
            print '<th class="modellibrary views-field views-field-'. $fields[$field] .'">';
              print $label;
            print '</th>';
          }
          elseif (substr($fields[$field], 0, 7) == 'created') {
            print '<th class="modellibrary views-field views-field-'. $fields[$field] .'">';
              print $label;
            print '</th>';
          }
        } 
      ?>
      <?php endforeach; ?>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($rows as $count => $row): ?>
      <tr class="modellibrary r1 <?php print $count; $row_class = implode(' ', $row_classes[$count]); print $row_class; ?>">
        <?php $uri = '';
              global $modelnid;?>
        <?php foreach ($row as $field => $content):

            if ($fields[$field] == 'field-model-uri-value' || $fields[$field] == 'field-fullname-value' || $fields[$field] == 'nid' || substr($fields[$field], 0, 5) == 'title') {
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
                print $content;
                print '</td>';
              }
              elseif (substr($fields[$field], 0, 7) == 'created') {
                print '<td class="modellibrary views-field views-field-'. $fields[$field] .'">';
                print $content;
                print '</td>';
              }
            }
         ?>

        <?php endforeach; ?>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>
