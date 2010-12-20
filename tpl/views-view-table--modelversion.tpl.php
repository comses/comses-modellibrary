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
        <?php $vnum = 0;
              foreach ($row as $field => $content): 

              if ($fields[$field] == 'field-modelversion-modelnid-value') {
                echo '<td class="views-field views-field-';
                print $fields[$field];
                echo '">';
                print $content;
                echo '</td>';
              }
              elseif ($fields[$field] == 'field-modelversion-number-value') {
                $vnum = $content;
                echo '<td onclick="DoNav(\'/model/'. arg(1) .'/version/'. $vnum .'\');" class="views-field views-field-';
                print $fields[$field];
                echo '">';
                print $content;
                echo '</td>';
              }
              else {
                echo '<td onclick="DoNav(\'/model/'. arg(1) .'/version/'. $vnum .'\');" class="views-field views-field-';
                print $fields[$field];
                echo '">';
                print $content;
                echo '</td>';
              }

         ?>

        <?php endforeach; ?>
      </tr>

    <?php endforeach; ?>
  </tbody>
</table>
