# nais-cs
Coding styles rules and git hook for NAIS Based Applications

v1.2.4
* limit codesniffer to 3.4.* (3.5.0 brought better PSR12 which currently collides with the slevomat config)

v1.2.3 
* CSS files no longer checked by `phpcs`

v1.2.2
* Eslint command is now called from node_modules in pre-commmit hook
* Added extendable Eslint rules

v1.2.1
* Fixed eslint pre commit hook only for .js files

v1.2.0
* Added eslint support

v1.1.2
* Fix error when commit does not contain any *.php files

v1.1.1
* Handle spaces in paths

v1.1.0
* Stop commit on warnings too

v1.0.2
* Setup excludes for js/css checks

v1.0.1
* Allow unused variables in foreach where only the key is used

v1.0.0
* Automatically installs a pre-commit hook on composer install/update
