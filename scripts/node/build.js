/**
 * Build script.
 */
const shell = require("shelljs");

// Run Composer Install.
shell.exec("lando composer install");

// Run Import.
shell.exec("yarn import-db");

// Run refresh.
shell.exec("yarn refresh");

// All good now!
shell.echo(
  "New site is built and should be available at http://extensionsdstateedu.lndo.site"
);
shell.echo("Rebuild for PR review with 'yarn refresh'");

shell.exit();
