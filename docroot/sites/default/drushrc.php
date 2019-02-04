<?php
/**
 * @file yoursite.aliases.drushrc.php
 * Site aliases for [your site domain]
 * Place this file at ~/.drush/  (~/ means your home path)
 *
 * Usage:
 *   To copy the development database to your local site:
 *   $ drush sql-sync @yoursite.dev @yoursite.local
 *   To copy your local database to the development site:
 *   $ drush sql-sync @yoursite.local @yoursite.dev --structure-tables-key=common --no-ordered-dump --sanitize=0 --no-cache
 *   To copy the production database to your local site:
 *   $ drush sql-sync @yoursite.prod @yoursite.local
 *   To copy all files in development site to your local site:
 *   $ drush rsync @yoursite.dev:%files @yoursite.local:%files
 *   Clear the cache in production:
 *   $ drush @yoursite.prod clear-cache all
 *
 * You can copy the site alias configuration of an existing site into a file
 * with the following commands:
 *   $ cd /path/to/settings.php/of/the/site/
 *   $ drush site-alias @self --full --with-optional >> ~/.drush/mysite.aliases.drushrc.php
 * Then edit that file to wrap the code in <?php ?> tags.
 */

/**
 * Self alias
 * Set the root and site_path values to point to your local site
 */
$aliases['self'] = array (
  'root' => '/docroot',
  'uri' => 'http://vrt-test.lndo.site',
  'path-aliases' =>
    array (
      '%drush' => '/usr/bin',
      '%site' => 'sites/default/',
    ),
);


/**
 * Local alias
 * Set the root and site_path values to point to your local site
 */
//$aliases['local'] = array(
//  'root' => '/path/to/drupal/root',
//  'uri'  => 'yoursite.localhost',
//  'path-aliases' => array(
//    '%dump-dir' => '/tmp',
//  ),
//);

$options['shell-aliases'] = [];

// Supply backup.sql.gz and optionally files.tar.gz for a local restore point
// for development.

$options['shell-aliases']['fresh'] = '!( ' . implode(" ) && \n ( ", [
    "echo '\nInstalling PHP packages...'",
    "cd ..; composer install; cd docroot",

    "echo '\nRemoving old files and Extracting reference files, if any...'",
    "bash -c '[ -f ../reference/files.tar.gz ] && \n rm -rf sites/default/files/* && \n tar -xzf ../reference/files.tar.gz -C sites/default/files || exit 0'",

    "echo '\nDropping the database...'",
    "drush sql-drop -y",

    "echo '\nImporting reference database...'",
    "gunzip < ../reference/db.sql.gz | sed '/INSERT INTO `cache.*` VALUES/d' | drush sql-cli",

    "echo '\nSetting admin password...'",
    "drush user-password 1 --password=admin",
  ]) . " )";

$options['shell-aliases']['local'] = "!( " . implode(" ) && \n ( ", [
    "echo ''",
    "echo 'Rebuilding cache...'",
    "drush cache-rebuild",

    "echo ''",
    "echo 'Importing configuration...'",
    "drush config-import sync -y",

    "echo ''",
    "echo 'Updating database...'",
    "drush updatedb -y --entity-updates",

    "echo ''",
    "echo 'Importing configuration...'",
    "drush config-import sync -y",

    "echo ''",
    "echo 'Rebuilding cache...'",
    "drush cache-rebuild",
  ]) . " )";

$options['shell-aliases']['prod'] = "!( " . implode(" ) && \n ( ", [
    "echo ''",
    "echo Deploying Prod",

    "echo ''",
    "echo 'Rebuilding cache...'",
    "drush cache-rebuild",

    "echo ''",
    "echo 'Importing configuration...'",
    "drush config-import sync -y",

    "echo ''",
    "echo 'Updating database...'",
    "drush updatedb -y --entity-updates",

    "echo ''",
    "echo 'Importing configuration...'",
    "drush config-import sync -y",

    "echo ''",
    "echo 'Rebuilding cache...'",
    "drush cache-rebuild",
  ]) . " )";