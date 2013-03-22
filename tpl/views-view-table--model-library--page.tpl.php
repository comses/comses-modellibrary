<?php

/**
 * @file
 * Template to display a view as a table.
 *
 * - $title : The title of this group of rows.  May be empty.
 * - $header: An array of header labels keyed by field id.
 * - $header_classes: An array of header classes keyed by field id.
 * - $fields: An array of CSS IDs to use for each field id.
 * - $classes: A class or classes to apply to the table, based on settings.
 * - $row_classes: An array of classes to apply to each row, indexed by row
 *   number. This matches the index in $rows.
 * - $rows: An array of row items. Each row is an array of content.
 *   $rows are keyed by row number, fields within rows are keyed by field ID.
 * - $field_classes: An array of classes to apply to each field, indexed by
 *   field id, then row number. This matches the index in $rows.
 * @ingroup views_templates
 */

$view = views_get_current_view(); 
$view_pager = $view->query->pager; 
$from = ($view_pager->current_page * $view_pager->options['items_per_page']) + 1;
$to = $from + count($view->result) - 1;
$total = $view->total_rows;
if ($total <= $to ) {
  // no need to show where we are if everything fits on the first page
  $out = "Showing " . $total . " models.";
} else {
  $out = "Showing " . $from . " - " . $to . " of " . $total . " models.";
}
print '<div class="modellibrary modellibrary-results-count">'. $out .'</div>';
?>
<table <?php if ($classes) { print 'class="'. $classes . '" '; } ?><?php print $attributes; ?>>
  <?php if (!empty($header)) : ?>
    <thead>
      <tr>
        <?php foreach ($header as $field => $label):

        if (substr($fields[$field], 0, 5) == 'title' || substr($fields[$field], 0, 23) == 'field-profile2-lastname' || substr($fields[$field], 0, 7) == 'created' || substr($fields[$field], 0, 8) == 'statusid') {
          if (substr($fields[$field], 0, 5) == 'title') {
            print '<th class="modellibrary views-field views-field-'. $fields[$field] .'">';
            print $label;
            print '</th>';
          }
          #elseif (substr($fields[$field], 0, 8) == 'statusid') {
          #  print '<th class="modellibrary views-field views-field-'. $fields[$field] .'">';
          #  print $label;
          #  print '</th>';
          #}
          else {
            if (substr($fields[$field], 0, 23) == 'field-profile2-lastname') {
              print '<th class="modellibrary views-field views-field-'. $fields[$field] .'">';
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
        } ?>
        <?php endforeach; ?>
      </tr>
    </thead>
  <?php endif; ?>
  <tbody>
    <?php foreach ($rows as $count => $row): ?>
      <tr class="modellibrary <?php print $count; echo ' r1 '; $row_class = implode(' ', $row_classes[$count]); print $row_class; ?>">
      <?php foreach ($row as $field => $content):
        if ($fields[$field] == 'nid' || substr($fields[$field], 0, 5) == 'title') {
          if ($fields[$field] == 'nid') {
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
          if ($fields[$field] == 'body') {
            print '<td class="modellibrary views-field views-field-'. $fields[$field] .'">';
            print '<span class="modellibrary views-field views-field-'. $fields[$field] .'">';
            print $content;
            print '</span>';
            print '</td>';
          }
          #elseif ($fields[$field] == 'statusid' || $fields[$field] == 'statusid active') {
          #  print '<td class="modellibrary views-field views-field-'. $fields[$field] .'">';
          #  if ($content == 60 && (in_array('openabm manager', array_values($user->roles)) || in_array('administrator', array_values($user->roles)))) {
          #    print '<img src="sites/all/modules/comses-modelreview/images/certified-badge-small.png" />';
          #  }
          #  print '</td>';
          #}
          elseif (substr($fields[$field], 0, 23) == 'field-profile2-lastname') {
            print '<td class="modellibrary views-field views-field-name">';
            print '<div class="modellibrary views-field views-field-'. $fields[$field] .'">';
            print $content;
            print '</div>';
          }
          elseif (substr($fields[$field], 0, 7) == 'created') {
            print '<div class="modellibrary views-field views-field-'. $fields[$field] .'">';
            print $content;
            print '</div>';
            print '</td>';
          }
        } ?>
        <?php endforeach; ?>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>
