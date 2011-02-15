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
<table width="100%" id="striped" class="<?php print $class; ?>">
  <?php if (!empty($title)) : ?>
    <caption><?php print $title; ?></caption>
  <?php endif; ?>
  <thead>
    <tr>
      <?php foreach ($header as $field => $label): 
        if ($fields[$field] != 'field-model-uri-value' && $fields[$field] != 'nid' && $fields[$field] != 'field-fullname-value') {
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
      <tr class="<?php print $count; echo ' r1 '; $row_class = implode(' ', $row_classes[$count]); print $row_class; ?>">
        <?php $uri = '';
              global $modelnid;?>
        <?php foreach ($row as $field => $content): 

            if ($fields[$field] != 'field-model-teaser-value') {
              if ($fields[$field] == 'field-model-uri-value') {
                $uri = $content;
              }
              elseif ($fields[$field] == 'field-fullname-value') {
                $fullname = $content;
              }
              elseif ($fields[$field] == 'nid') {
                $modelnid = $content;
              }
              elseif ($fields[$field] == 'field-model-image-fid') {
                echo '<td onclick="DoNav(\'/model/'. $modelnid .'\');" rowspan="2" valign="top" class="views-field views-field-';
                print $fields[$field];
                echo '">';
                print $content;
                echo '</td>';
              }
              elseif ($fields[$field] == 'field-fullname-value') {
                echo '<td onclick="DoNav(\'/model/'. $modelnid .'\');" class="views-field views-field-';
                print $fields[$field];
                echo '">';
                  print $content;
                echo '</td>';
              }
              else {
                echo '<td onclick="DoNav(\'/model/'. $modelnid .'\');" class="views-field views-field-';
                print $fields[$field];
                echo '">';
                print $content;
                echo '</td>';
              }
            }
            else {
              echo '</tr>';
              echo '<tr class="';  print $count; echo ' r2 '; print $row_class; echo '">';
              //echo '<td>&nbsp; </td>';
              echo '<td onclick="DoNav(\'/model/'. $modelnid .'\');" colspan="3" class="views-field views-field-';
              print $fields[$field];
              echo '">';
              print $content;
              echo '</td>';
              //echo '</tr>';
            }
         ?>

        <?php endforeach; ?>
      </tr>

    <?php endforeach; ?>
  </tbody>
</table>
