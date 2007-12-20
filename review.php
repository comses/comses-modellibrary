<?php

function openabmma_reviewThisModel ()
{
	$pName = arg(1);
	$versionNumber = openabmma_parseVersionNumber (arg(2));

    $query = "SELECT visible FROM openabm_model_version WHERE model_id=%d AND version_num=%d";
    $result = (array) db_fetch_object (db_query ($query, openabmma_getModelId ($pName), $versionNumber));
    $visible = $result ['visible'];
    if ($visible == "1")
        $visible = TRUE;
    else
        $visible = FALSE;

    drupal_add_css (openabmma_get_css_path ());

    $query = "SELECT description, model_language_id, os, framework, reference_text, examples, submittedReview, visible, date_modified, run_conditions, license_id from openabm_model_version WHERE model_id=%d AND version_num=%d";
    $result = (array) db_fetch_object (db_query ($query, openabmma_getModelId ($pName), $versionNumber));
    $description = $result ['description'];
    $model_language_id = $result ['model_language_id'];
    $os = $result ['os'];
    $framework = $result ['framework'];
    $reference_text = $result ['reference_text'];
    $examples = $result ['examples'];
    $submittedReview = $result ['submittedReview'] == "1" ? "Yes" : "No";
    $visible = $result ['visible'] == "1" ? "Yes" : "No";
    $license_id = $result ['license_id'];

    $query = "SELECT name from openabm_model_language WHERE id=%d";
    $result = (array) db_fetch_object (db_query ($query, $model_language_id));
    $model_language_id = $result ['name'];

    $query = "SELECT name from openabm_license WHERE id=%d";
    $result = (array) db_fetch_object (db_query ($query, $license_id));
    $license_id = $result ['name'];

    $memberArray = openabmma_getModelMemberArray ($pName);
    if ($memberArray == null)
        $members = "None";
    else
        $members = implode (' ,', $memberArray);

    drupal_add_css (openabmma_get_css_path ());

    $query = "SELECT owner_uid, name, title, replicatedModel, replicators, reference from openabm_model WHERE name='%s'";
    $result = (array) db_fetch_object (db_query ($query, $pName));
    $owner_uid = $result ['owner_uid'];
    $name = $result ['name'];
    $title = $result ['title'];
    $replicated = $result ['replicatedModel'] == "1" ? "Yes" : "No";
    $replicators = $result ['replicators'];
    $reference_url = $result ['reference'];

    $query = "SELECT owner_uid, name, title, replicatedModel, replicators, reference from openabm_model WHERE name='%s'";
    $result = (array) db_fetch_object (db_query ($query, $pName));
    $title = $result ['title'];
    $replicated = $result ['replicatedModel'] == "1" ? "Yes" : "No";
    $replicators = $result ['replicators'];
    $reference_url = $result ['reference'];

    $keywordList = '';
    $query = "SELECT keyword FROM openabm_model_keywords WHERE model_id=%d";
    $result = db_query ($query, openabmma_getModelId ($pName));
    while ($element = db_fetch_object ($result))
        $keywordList .= $element->keyword . ", ";
    $keywordList = substr ($keywordList, 0, strlen($keywordList)-2);

    $output = "<br/><p><table border='0' cellpadding='0' cellspacing='0' width='100%'>";
    $output .= "<tr class='openabmData'><td width='30%'><b>Model name:</b></td><td><b>" . $pName . "</b></td></tr>";
    $output .= "<tr class='openabmData'><td><b>Version number:</b></td><td><b>" . $versionNumber . "</b></td></tr>";

    $output .= "<tr><td>&nbsp;</td><td>&nbsp;</td></tr>";
    $output .= "<tr><td><u><i>Project Metadata:</i></u></td><td>&nbsp;</td></tr>";
    $output .= "<tr class='openabmData'><td><b>Model title:</b></td><td><i>" . $title . "</i></td></tr>";
    $output .= "<tr class='openabmData'><td><b>Replicated model:</b></td><td><i>" . $replicated . "</i></td></tr>";
    if ($replicated == "Yes")
    {
        $output .= "<tr class='openabmData'><td><b>Replicators:</b></td><td><i>" . $replicators . "</i></td></tr>";
        $output .= "<tr class='openabmData'><td><b>Reference URL:</b></td><td><i>" . $reference_url . "</i></td></tr>";
    }

    $output .= "<tr class='openabmData'><td><b>Model keywords:</b></td><td><i>" . $keywordList . "</i></td></tr>";

    $output .= "<tr><td>&nbsp;</td><td>&nbsp;</td></tr>";
    $output .= "<tr><td><u><i>Version Metadata:</i></u></td><td>&nbsp;</td></tr>";
    $output .= "<tr class='openabmData'><td><b>Version description:</b></td><td><i>" . substr($description,0,100) . "...</i></td></tr>";
    $output .= "<tr class='openabmData'><td><b>Owner:</b></td><td><i>" . openabmma_getModelOwner ($pName) . "</i></td></tr>";
    $output .= "<tr class='openabmData'><td><b>Members:</b></td><td><i>" . $members . "</i></td></tr>";
    $output .= "<tr class='openabmData'><td><b>Visible to public:</b></td><td><i>" . $visible . "</i></td></tr>";
    $output .= "<tr class='openabmData'><td><b>Programming language:</b></td><td><i>" . $model_language_id . "</i></td></tr>";
    $output .= "<tr class='openabmData'><td><b>Operating System:</b></td><td><i>" . $os . "</i></td></tr>";
    $output .= "<tr class='openabmData'><td><b>Framework:</b></td><td><i>" . $framework . "</i></td></tr>";
    $output .= "<tr class='openabmData'><td><b>License:</b></td><td><i>" . $license_id . "</i></td></tr>";
    $output .= "<tr class='openabmData'><td><b>References:</b></td><td><i>" . $reference_text . "</i></td></tr>";
    $output .= "<tr class='openabmData'><td><b>Examples:</b></td><td><i>" . $examples . "</i></td></tr>";
    $output .= "<tr class='openabmData'><td><b>Submitted for review:</b></td><td><i>" . $submittedReview . "</i></td></tr>";

    $files_root = "files/models/" . $pName . "/v" . $versionNumber;
    if (openabmma_getFileCount ($files_root . "/code") == 0)
        $codeStr = "None";
    else
    {
        $filename = openabmma_getFirstFile ($files_root . "/code");
	$codeStr = l ($filename, "download/" . $pName . "/version" . $versionNumber . "/code");
//        $codeStr = "<a href='file://" . realpath ($files_root . "/code/" . $filename) . "'>" . $filename . "</a>";
    }

    $files_root = "files/models/" . $pName . "/v" . $versionNumber;
    if (openabmma_getFileCount ($files_root . "/doc") == 0)
        $docStr = "None";
    else
    {
        $filename = openabmma_getFirstFile ($files_root . "/doc");
	$docStr = l ($filename, "download/" . $pName . "/version" . $versionNumber . "/doc");
//        $docStr = "<a href='file://" . realpath ($files_root . "/doc/" . $filename) . "'>" . $filename . "</a>";
    }

    $files_root = "files/models/" . $pName . "/v" . $versionNumber;
    if (openabmma_getFileCount ($files_root . "/sensitivity") == 0)
        $sensitivityStr = "None";
    else
    {
        $filename = openabmma_getFirstFile ($files_root . "/sensitivity");
	$sensitivityStr = l ($filename, "download/" . $pName . "/version" . $versionNumber . "/sensitivity");
//        $sensitivityStr = "<a href='file://" . realpath ($files_root . "/sensitivity/" . $filename) . "'>" . $filename . "</a>";
    }

    $files_root = "files/models/" . $pName . "/v" . $versionNumber;
    if (openabmma_getFileCount ($files_root . "/dataset") == 0)
        $datasetStr = "None";
    else
    {
        $filename = openabmma_getFirstFile ($files_root . "/dataset");
	$datasetStr = l ($filename, "download/" . $pName . "/version" . $versionNumber . "/dataset");
//        $datasetStr = "<a href='file://" . realpath ($files_root . "/dataset/" . $filename) . "'>" . $filename . "</a>";
    }

    $files_root = "files/models/" . $pName . "/v" . $versionNumber;
    if (openabmma_getFileCount ($files_root . "/other") == 0)
        $additionalStr = "None";
    else
    {
        $filename = openabmma_getFirstFile ($files_root . "/other");
	$additionalStr = l ($filename, "download/" . $pName . "/version" . $versionNumber . "/other");
//        $additionalStr = "<a href='file://" . realpath ($files_root . "/other/" . $filename) . "'>" . $filename . "</a>";
    }

    $output .= "<tr><td>&nbsp;</td><td>&nbsp;</td></tr>";
    $output .= "<tr><td><u><i>Files:</i></u></td><td>&nbsp;</td></tr>";
    $output .= "<tr class='openabmData'><td><b>Code:</b></td><td><i>" . $codeStr . "</i></td></tr>";
    $output .= "<tr class='openabmData'><td><b>Documentation:</b></td><td><i>" . $docStr . "</i></td></tr>";
    $output .= "<tr class='openabmData'><td><b>Sensitivity:</b></td><td><i>" . $sensitivityStr . "</i></td></tr>";
    $output .= "<tr class='openabmData'><td><b>Dataset:</b></td><td><i>" . $datasetStr . "</i></td></tr>";
    $output .= "<tr class='openabmData'><td><b>Additional file:</b></td><td><i>" . $additionalStr . "</i></td></tr>";
    $output .= "</table>";

    return $output;
}

function openabmma_reviewModels ()
{
    drupal_add_css (openabmma_get_css_path ());

	$output  = "<p>&nbsp;</p><table border='0' cellpadding='1' cellspacing='0' width='100%'>";
	$output .= "<tr class='openabmData'><td class='openabmCol'>&nbsp;</td><td class='openabmCol'><b>Model</b></td><td class='openabmCol'><b>Owner</b></td><td class='openabmCol'><b>Description</b></td><td><b>Date submitted</b></td></tr>";

	$query = "SELECT model_id, version_num, date_modified, description FROM openabm_model_version WHERE submittedReview = 1 ORDER BY model_id, version_num ASC";
	$result = db_query ($query);

	$count = 0;
	while ($version = db_fetch_object ($result))
	{
		$count++;
		if (strlen ($version->description) > 50)
			$desc = substr ($version->description, 0, 49) . "...";
		else	$desc = $version->description;

		$pName = openabmma_getModelName ($version->model_id);
		$pTitle = openabmma_getModelTitle ($version->model_id);
		$pOwner = openabmma_getModelOwner ($pName);
		$versionNumber = $version->version_num;
		$output .= "\n<tr class='openabmData'><td class='openabmCol'>" . $count . "</td><td class='openabmCol'>" . l ($pTitle . " [" . $pName . "] v" . $versionNumber, "review/" . $pName . "/version" . $versionNumber) . "</td><td class='openabmCol'>" . $pOwner . "</td><td class='openabmCol'>" . $desc . "</td><td>" . $version->date_modified . "</td></tr>";
	}

	$output .= "</table>";
	return $output;
}


