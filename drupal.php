<?php
define('MODEL_DIRECTORY', 'model-archive/');

function openabmma_block ($op='list', $delta=0)
{
	global $user;
    // listing of blocks, such as on the admin/block page
    if ($op == "list") {
        $block[0]["info"] = t("OpenABM Resource Links");
        return $block;
    }
     else if ($op == 'view') {
        $block_content = l("Browse models", "models/browse") . "<br/>" . l("Search models", "models/search") . "<br/>";

        if ($user->uid != 0) {
            $block_content .= "<br/>" . l("My models", "models/") . "<br/>" . l("Add a new model", "models/add") . "<br/>";
        }

        if (user_access ('review models')) {
            $block_content .= "<hr/>" . l ("Review Models", "review") . "<br/>";
        }
        if (user_access ('administer models')) {
            $block_content .= "<br/>" . l ("Model licenses", "config/licenses");
            $block_content .= "<br/>" . l ("Member roles", "config/roles");
            $block_content .= "<br/>" . l ("Programming languages", "config/planguages");
            $block_content .= "<br/>" . l ("Frameworks", "config/frameworks");
        }

        $block['subject'] = 'Model Archive';
        $block['content'] = $block_content;
        return $block;
    }
}
 
function openabmma_menu($may_cache) {

    $items = array();
    if ($may_cache) {
        // admin settings for model archive.  Right now I can only think of
        // group membership in the review committee as a configurable option.
        $items[] = array(
            'path' => 'admin/settings/openabmma',
            'title' => t('OpenABM Model Archive Settings'),
            'description' => t('Change model archive settings.'),
            'callback' => 'drupal_get_form',
            'callback arguments' => array('openabmma_settings_form'),
            'type' => MENU_CALLBACK,
            'access' => user_access('administer site configuration')
            );

            $items[] = array(
            'path' => 'models/add',
            'title' => t('Add a new model'),
            'description' => t('Add a new model to the model archive.'),
            'access' => user_access ('modify models'),
            'callback' => 'drupal_get_form',
            'type' => MENU_CALLBACK,
            'callback arguments' => array ('openabmma_addModel')
            );

        $items[] = array(
            'path' => 'models/search',
            'title' => t('Search Models'),
            'description' => "",
            'access' => user_access ('view models'),
            'callback' => 'openabmma_searchProjects',
            'type' => MENU_CALLBACK
            );

        $items[] = array(
            'path' => 'models',
            'title' => t('My models'),
            'description' => "",
            'access' => user_access ('modify models'),
            'callback' => 'openabmma_showProjects',
            'type' => MENU_CALLBACK
            );

        $items[] = array(
            'path' => 'review',
            'title' => t('Review models'),
            'description' => "",
            'access' => user_access ('review models'),
            'callback' => 'openabmma_reviewModels',
            'type' => MENU_CALLBACK
            );

        $items[] = array(
            'path' => 'config/roles',
            'title' => t('User roles'),
            'description' => "",
            'access' => user_access ('administer models'),
            'callback' => 'openabmma_showRoles',
            'type' => MENU_CALLBACK
            );

        $items[] = array(
            'path' => 'config/licenses',
            'title' => t('Manage licenses'),
            'description' => "",
            'access' => user_access ('administer models'),
            'callback' => 'openabmma_showLicenses',
            'type' => MENU_CALLBACK
            );

        $items[] = array(
            'path' => 'config/planguages',
            'title' => t('Programming languages listed'),
            'description' => "",
            'access' => user_access ('administer models'),
            'callback' => 'openabmma_showPLanguages',
            'type' => MENU_CALLBACK
            );

        $items[] = array(
            'path' => 'config/frameworks',
            'title' => t('Manage frameworks'),
            'description' => "",
            'access' => user_access ('administer models'),
            'callback' => 'openabmma_showFrameworks',
            'type' => MENU_CALLBACK
            );

        $items[] = array(
            'path' => 'models/browse',
            'title' => t('Browse models'),
            'description' => "",
            'access' => user_access ('view models'),
            'callback' => 'openabmma_browseModels',
            'type' => MENU_CALLBACK
            );
    }
    else {
	$items[] = array(
            'path' => 'download/' . arg(1) . '/' . arg(2) . '/' . arg(3),
            'title' => t('Download file'),
            'description' => null,
            'access' => user_access ('review models') || user_access ('view models'),
            'callback' => 'openabmma_downloadFile',
            'type' => MENU_CALLBACK
            );

	$items[] = array(
            'path' => 'review/' . arg(1) . '/' . arg(2),
            'title' => t('Review model'),
            'description' => null,
            'access' => user_access ('review models'),
            'callback' => 'openabmma_reviewThisModel',
            'type' => MENU_CALLBACK
            );

	$items[] = array(
            'path' => 'models/edit/' . arg(2),
            'title' => t('Edit model metadata'),
            'description' => null,
            'access' => user_access ('modify models'),
            'callback' => 'drupal_get_form',
            'type' => MENU_CALLBACK,
            'callback arguments' => array ('openabmma_addModel')
            );

        $items[] = array(
            'path' => MODEL_DIRECTORY . arg(1) . "/" . arg(2) . "/metadata",
            'title' => t('Model version metadata'),
            'description' => "",
            'access' => user_access ('view models'),
            'callback' => 'openabmma_versionMetadata',
            'type' => MENU_CALLBACK
            );

        $items[] = array(
            'path' => MODEL_DIRECTORY . arg(1) . "/add/version",
            'title' => t('Step 1 : Adding a version to your model'),
            'description' => "",
            'access' => user_access ('modify models'),
            'callback' => 'openabmma_addVersion',
            'type' => MENU_CALLBACK
            );

        $items[] = array(
            'path' => MODEL_DIRECTORY . arg(1) . "/delete/" . arg(3),
            'title' => t('Delete a version'),
            'description' => "",
            'access' => user_access ('modify models'),
            'callback' => 'openabmma_deleteVersion',
            'type' => MENU_CALLBACK
            );

        $items[] = array(
            'path' => MODEL_DIRECTORY . arg(1) . "/add/" . arg(3) . "/step01",
            'title' => t('Step 1 : Adding a version'),
            'description' => "",
            'access' => user_access ('modify models'),
            'callback' => 'drupal_get_form',
            'callback arguments' => array ('openabmma_addVersion01'),
            'type' => MENU_CALLBACK
            );

        $items[] = array(
            'path' => MODEL_DIRECTORY . arg(1) . "/add/" . arg(3) . "/step02",
            'title' => t('Step 2 : Adding a version'),
            'description' => "",
            'access' => user_access ('modify models'),
            'callback' => 'drupal_get_form',
            'callback arguments' => array ('openabmma_addVersion02'),
            'type' => MENU_CALLBACK
            );

        $items[] = array(
            'path' => MODEL_DIRECTORY . arg(1) . "/add/" . arg(3) . "/step03",
            'title' => t('Step 3 : Adding a version'),
            'description' => "",
            'access' => user_access ('modify models'),
            'callback' => 'drupal_get_form',
            'callback arguments' => array ('openabmma_addVersion03'),
            'type' => MENU_CALLBACK
            );

        $items[] = array(
            'path' => MODEL_DIRECTORY . arg(1) . "/add/" . arg(3) . "/step04",
            'title' => t('Step 4 : Adding a version'),
            'description' => "",
            'access' => user_access ('modify models'),
            'callback' => 'drupal_get_form',
            'callback arguments' => array ('openabmma_addVersion04'),
            'type' => MENU_CALLBACK
            );


        $items[] = array(
            'path' => MODEL_DIRECTORY . arg(1) . "/edit/" . arg(3) . "/step01",
            'title' => t('Step 1 : Editing a version'),
            'description' => "",
            'access' => user_access ('modify models'),
            'callback' => 'drupal_get_form',
            'callback arguments' => array ('openabmma_addVersion01'),
            'type' => MENU_CALLBACK
            );

        $items[] = array(
            'path' => MODEL_DIRECTORY . arg(1) . "/edit/" . arg(3) . "/step02",
            'title' => t('Step 2 : Editing a version'),
            'description' => "",
            'access' => user_access ('modify models'),
            'callback' => 'drupal_get_form',
            'callback arguments' => array ('openabmma_addVersion02'),
            'type' => MENU_CALLBACK
            );

        $items[] = array(
            'path' => MODEL_DIRECTORY . arg(1) . "/edit/" . arg(3) . "/step03",
            'title' => t('Step 3 : Editing a version'),
            'description' => "",
            'access' => user_access ('modify models'),
            'callback' => 'drupal_get_form',
            'callback arguments' => array ('openabmma_addVersion03'),
            'type' => MENU_CALLBACK
            );

        $items[] = array(
            'path' => MODEL_DIRECTORY . arg(1) . "/edit/" . arg(3) . "/step04",
            'title' => t('Step 4 : Editing a version'),
            'description' => "",
            'access' => user_access ('modify models'),
            'callback' => 'drupal_get_form',
            'callback arguments' => array ('openabmma_addVersion04'),
            'type' => MENU_CALLBACK
            );
/*
        $items[] = array(
            'path' => MODEL_DIRECTORY . arg(1) . "/edit/" . arg(3) . "/files_basic",
            'title' => t('Step 4 : Editing a version'),
            'description' => null,
            'access' => user_access ('modify models'),
            'callback' => 'drupal_get_form',
            'callback arguments' => array ('openabmma_basicFiles'),
            'type' => MENU_CALLBACK
            );

        $items[] = array(
            'path' => MODEL_DIRECTORY . arg(1) . "/edit/" . arg(3) . "/files_opt",
            'title' => t('Step 5 : Editing a version'),
            'description' => null,
            'access' => user_access ('modify models'),
            'callback' => 'drupal_get_form',
            'callback arguments' => array ('openabmma_optFiles'),
            'type' => MENU_CALLBACK
            );
*/
        $items[] = array(
            'path' => MODEL_DIRECTORY . arg(1) . "/edit/" . arg(3) . "/files_basic/delete/" . arg(6),
            'title' => null,
            'description' => null,
            'access' => user_access ('modify models'),
            'callback' => 'drupal_get_form',
            'callback arguments' => array ('openabmma_basicFiles_delete'),
            'type' => MENU_CALLBACK
            );

        $items[] = array(
            'path' => MODEL_DIRECTORY . arg(1) . "/add/" . arg(3) . "/files_basic/delete/" . arg(6),
            'title' => null,
            'description' => null,
            'access' => user_access ('modify models'),
            'callback' => 'drupal_get_form',
            'callback arguments' => array ('openabmma_basicFiles_delete'),
            'type' => MENU_CALLBACK
            );

        $items[] = array(
            'path' => MODEL_DIRECTORY . arg(1) . "/add/" . arg(3) . "/files_opt/delete/" . arg(6),
            'title' => null,
            'description' => null,
            'access' => user_access ('modify models'),
            'callback' => 'drupal_get_form',
            'callback arguments' => array ('openabmma_optFiles_delete'),
            'type' => MENU_CALLBACK
            );

        $items[] = array(
            'path' => MODEL_DIRECTORY . arg(1) . "/edit/" . arg(3) . "/files_opt/delete/" . arg(6),
            'title' => null,
            'description' => null,
            'access' => user_access ('modify models'),
            'callback' => 'drupal_get_form',
            'callback arguments' => array ('openabmma_optFiles_delete'),
            'type' => MENU_CALLBACK
            );

        $items[] = array(
            'path' => MODEL_DIRECTORY . arg(1) . "/" . arg(2) . "/" . arg(3) . "/reviewnote",
            'title' => t('Step 4 : Review step'),
            'description' => "",
            'access' => user_access ('modify models'),
            'callback' => 'drupal_get_form',
            'callback arguments' => array(openabmma_askIfReview),
            'type' => MENU_CALLBACK
            );

        $items[] = array(
            'path' => MODEL_DIRECTORY . arg(1) . '/add/' . arg(3) . "/complete",
            'title' => t('Add new version complete'),
            'description' => "",
            'access' => user_access ('modify models'),
            'callback' => 'openabmma_addVersionComplete',
            'type' => MENU_CALLBACK
            );

        $items[] = array(
            'path' => MODEL_DIRECTORY . arg(1) . '/edit/' . arg(3) . "/complete",
            'title' => t('Add new version complete'),
            'description' => "",
            'access' => user_access ('modify models'),
            'callback' => 'openabmma_addVersionComplete',
            'type' => MENU_CALLBACK
            );

        $items[] = array(
            'path' => 'config/roles/delete/' . arg(3),
            'title' => t('Delete role'),
            'description' => "",
            'access' => user_access ('administer models'),
            'callback' => 'openabmma_deleteRole',
            'callback arguments' => array(arg(3)),
            'type' => MENU_CALLBACK
            );

        $items[] = array(
            'path' => 'models/search/' . arg (2),
            'title' => t('Search models'),
            'description' => "",
            'access' => user_access ('modify models'),
            'callback' => 'openabmma_searchProjects',
            'type' => MENU_CALLBACK
            );

        $items[] = array(
            'path' => 'config/licenses/delete/' . arg(3),
            'title' => t('Delete license'),
            'description' => "",
            'access' => user_access ('administer models'),
            'callback' => 'openabmma_deleteLicense',
            'callback arguments' => array(arg(3)),
            'type' => MENU_CALLBACK
            );

        $items[] = array(
            'path' => 'config/planguages/delete/' . arg(3),
            'title' => t('Delete language'),
            'description' => "",
            'access' => user_access ('administer models'),
            'callback' => 'openabmma_deletePLanguages',
            'callback arguments' => array(arg(3)),
            'type' => MENU_CALLBACK
            );

        $items[] = array(
            'path' => 'config/frameworks/delete/' . arg(3),
            'title' => t('Delete framework'),
            'description' => "",
            'access' => user_access ('administer models'),
            'callback' => 'openabmma_deleteFramework',
            'callback arguments' => array(arg(3)),
            'type' => MENU_CALLBACK
            );

        $items[] = array(
            'path' => MODEL_DIRECTORY . arg(1) . '/members/delete',
            'title' => t('Delete members'),
            'description' => "",
            'access' => user_access ('modify models'),
            'callback' => 'openabmma_deleteMember',
            'callback arguments' => array(arg(1), arg(4)),
            'type' => MENU_CALLBACK
            );

        $items[] = array(
            'path' => MODEL_DIRECTORY . arg(1) . '/members',
            'title' => t('Model members'),
            'description' => "",
            'access' => user_access ('modify models'),
            'callback' => 'openabmma_manageMembers',
            'callback arguments' => array(arg(1)),
            'type' => MENU_CALLBACK
            );

        $items[] = array(
            'path' => MODEL_DIRECTORY . arg(1) . '/viewMetadata',
            'title' => arg(1),
            'description' => "",
            'access' => user_access ('view models'),
            'callback' => 'openabmma_showMetaData',
            'callback arguments' => array(arg(1)),
            'type' => MENU_CALLBACK
            );

        $items[] = array(
            'path' => MODEL_DIRECTORY . arg(1),
            'title' => arg(1),
            'description' => "",
            'access' => user_access ('view models'),
            'callback' => 'openabmma_openProject',
            'callback arguments' => array(arg(1)),
            'type' => MENU_CALLBACK
            );
    }

    return $items;
}

function openabmma_perm () {
	return array ('view models', 'modify models', 'review models', 'administer models');
}

function openabmma_getUserId ($name)
{
	if ($name == '')
		return -1;

	$result = (array) db_fetch_object (db_query ("SELECT uid FROM users WHERE name='%s'", $name));
	$id = $result['uid'];

	if ($id == "")
		$id = -1;

	return $id;
}

function openabmma_getUserName ($uid)
{
	if ($uid == '')
		return "";

	$result = (array) db_fetch_object (db_query ("SELECT name FROM users WHERE uid=%d", $uid));
	$name = $result['name'];

	return $name;
}

function openabmma_settings_form() {
    $form['openabmma_settings'] = array(
        '#type' => 'checkboxes',
        '#title' => t('Testing'),
        '#options' => node_get_types('names'),
        '#default_value' => 'story',
        '#description' => t('Some description'),
        );
    $form['array_filter'] = array('#type' => 'hidden');
    return system_settings_form($form);
}
