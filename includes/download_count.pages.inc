<?php

/**
 * @file
 * Overriding page callback file for the download_count module.
 */
function download_count_view_page($arg = NULL) {

  global $user;
  $rows = array();
  $header = array();
  $result = NULL;

  $header[] = array(
    'data' => t('Count'),
    'field' => 'count',
    'sort' => 'desc',
    'class' => 'count',
  );
  $header[] = array(
    'data' => t('File'),
    'field' => 'filename',
    'class' => 'file',
  );
  $header[] = array(
    'data' => t('Model'),
    'field' => 'title',
    'class' => 'page',
  );
  $header[] = array(
    'data' => t('Last Download'),
    'field' => 'last',
    'class' => 'date',
  );

  if ($arg == 'download_count') {
    drupal_set_title(check_plain(variable_get('download_count_view_page_title', t('Download Count'))));
    $limit = (int) variable_get('download_count_view_page_limit', '');
    $file_links = variable_get('download_count_file_links', 1);

    if (!$limit) {
      $result = db_query("SELECT COUNT(dc.dcid) AS count, dc.dcid, f.filename, MAX(dc.timestamp) AS last, dc.nid, f.filepath, f.fid, m.title FROM {download_count} dc JOIN {files} f ON dc.fid = f.fid JOIN {node} n ON dc.nid = n.nid JOIN content_type_modelversion mv ON n.nid = mv.nid JOIN node m ON mv.field_modelversion_modelnid_value = m.nid GROUP BY dc.nid, f.filename" . tablesort_sql($header));
    }
    else {
      $result = db_query("SELECT COUNT(dc.dcid) AS count, dc.dcid, f.filename, MAX(dc.timestamp) AS last, dc.nid, f.filepath, f.fid, m.title FROM {download_count} dc JOIN {files} f ON dc.fid = f.fid JOIN {node} n ON dc.nid = n.nid JOIN content_type_modelversion mv ON n.nid = mv.nid JOIN node m ON mv.field_modelversion_modelnid_value = m.nid GROUP BY dc.nid, f.filename" . tablesort_sql($header) . " LIMIT %d", $limit);
    }
    $output = '<div id="download_count_page">';
  }
  elseif ($arg == 'my_download_count') {
    drupal_set_title(check_plain(variable_get('download_count_mypage_view_page_title', t('My Download Counts'))));
    $limit = (int) variable_get('download_count_mypage_view_page_limit', '');
    $file_links = variable_get('download_count_mypage_file_links', 1);

    if (!$limit) {
      $result = db_query("SELECT COUNT(dc.dcid) AS count, dc.dcid, f.filename, MAX(dc.timestamp) AS last, dc.nid, f.filepath, f.fid, m.title FROM {download_count} dc JOIN {files} f ON dc.fid = f.fid JOIN {node} n ON dc.nid = n.nid JOIN content_type_modelversion mv ON n.nid = mv.nid JOIN node m ON mv.field_modelversion_modelnid_value = m.nid WHERE dc.uid = %d GROUP BY dc.nid, f.filename" . tablesort_sql($header), $user->uid);
    }
    else {
      $result = db_query("SELECT COUNT(dc.dcid) AS count, dc.dcid, f.filename, MAX(dc.timestamp) AS last, dc.nid, f.filepath, f.fid, m.title FROM {download_count} dc JOIN {files} f ON dc.fid = f.fid JOIN {node} n ON dc.nid = n.nid JOIN content_type_modelversion mv ON n.nid = mv.nid JOIN node m ON mv.field_modelversion_modelnid_value = m.nid WHERE dc.uid = %d GROUP BY dc.nid, f.filename" . tablesort_sql($header) . " LIMIT %d", $user->uid, $limit);
    }
    $output = '<div id="my_download_count_page">';
  }

  $total_downloads = 0;
  $files = array();
  $nodes = array();
  $colspan = 0;

  while ($file = db_fetch_object($result)) {
    $row = array();
    $node = node_load($file->nid);
    $model = node_load($node->field_modelversion_modelnid[0]['value']);

    $row[] = $file->count;
    $row[] = ($file_links && (user_access('view uploaded files') || _download_count_is_accessible_by_filefield($file->filepath))) ? l(substr(t('@filename', array('@filename' => $file->filename)), 0, 20), function_exists('_private_upload_create_url') ? _private_upload_create_url($file) : file_create_url($file->filepath)) : $file->filename;
    $row[] = node_access('view', $model) ? l(t('@title', array('@title' => $model->title)), 'model/' . $model->nid) : check_plain($model->title);
    $row[] = t('@time ago', array('@time' => format_interval(time() - $file->last)));
    module_exists('download_count_statistics') && user_access('view download count statistics') ? $row[] = l(t('Details'), 'download_count/' . $file->dcid . '/details') : NULL;
    user_access('export download counts') ? $row[] = l(t('Export'), 'download_count/' . $file->dcid . '/export') : NULL;
    user_access('administer site configuration') ? $row[] = l(t('Reset'), 'download_count/' . $file->dcid . '/reset') : NULL;
    $rows[] = $row;

    $total_downloads += $file->count;
    $files[] = $file->filename;
    $nodes[] = $model->title;
    $colspan = count($rows[0]) - count($header);
  }

  if (empty($rows)) {
    $rows[] = array(array(
        'data' => t('No files have been downloaded.'),
        'colspan' => '4',
      ));
  }

  if ($colspan > 1) {
    $header[] = array(
      'data' => t('Action')
        . (user_access('export download counts') ? l(t(' (Export All)'), 'download_count/all/export') : NULL)
        . (user_access('administer site configuration') ? l(t(' (Reset All)'), 'download_count/all/reset') : NULL),
      'colspan' => $colspan,
    );
  }
  $output .= $arg == 'download_count' ? check_markup(variable_get('download_count_view_page_header', ''), variable_get('download_count_view_page_format', 0), FALSE) : check_markup(variable_get('download_count_mypage_view_page_header', ''), variable_get('download_count_mypage_view_page_format', 0), FALSE);
  $output .= '<span id="download_count_summary_top">' . t('Unique Models: ') . count(array_unique($nodes)) . (', Unique Files: ') . count(array_unique($files)) . ', Total Downloads: ' . $total_downloads . '</span>';
  $output .= theme('table', $header, $rows, array('id' => 'download_count_table'));
  $output .= '<span id="download_count_summary_bottom">' . t('Unique Models: ') . count(array_unique($nodes)) . (', Unique Files: ') . count(array_unique($files)) . ', Total Downloads: ' . $total_downloads . '</span>';
  $output .= $arg == 'download_count' ? check_markup(variable_get('download_count_view_page_footer', ''), variable_get('download_count_view_page_format', 0), FALSE) : check_markup(variable_get('download_count_mypage_view_page_footer', ''), variable_get('download_count_mypage_view_page_format', 0), FALSE);
  $output .= '</div>';

  return $output;
}
