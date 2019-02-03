/**
 * Rebuild setup script for Drupal.
 */
const shell = require("shelljs");

// Composer install and drush config import.
shell.exec("lando composer install");
shell.exec("lando drush cim sync -y");
shell.exec("lando drush cr");
shell.exec("lando drush cim sync -y");
shell.exec("lando drush updb -y");
shell.exec("lando drush upwd 1 --password=admin");

shell.exit();
