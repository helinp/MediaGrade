<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * The user name of the git hub user who owns the repo
 */
$config['github_user'] = 'helinp';

/**
 * The repo on GitHub we will be updating from
 */
$config['github_repo'] = 'MediaGrade';

/**
 * The branch to update from
 */
$config['github_branch'] = 'develop';

/**
 * The current commit the files are on.
 *
 * NOTE: You should only need to set this initially it will be
 * automatically set by the library after subsequent updates.
 */
 // 9b5aaf0a723e14807eb90e3cc87eb82b5753cf46
$config['current_commit'] = 'eff467b80d4c7436d82b90dbefda92c4b907452d';

/**
 * A list of files or folders to never perform an update on.
 * Not specifying a relative path from the webroot will apply
 * the ignore to any files with a matching segment.
 *
 * I.E. Specifying 'admin' as an ignore will ignore
 * 'application/controllers/admin.php'
 * 'application/views/admin/test.php'
 * and any other path with the term 'admin' in it.
 */
$config['ignored_files'] = array('database.php', 'github_updater.php','uploads', 'Update.php');

/**
 * Flag to indicate if the downloaded and extracted update files
 * should be removed
 */
$config['clean_update_files'] = FALSE;


/**
 * Folder to copy Github zip to
 */
$config['download_folder'] = 'updates/';