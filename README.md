Browser Extensions module
==============

Browser extensions module for MunkiReport. Reports on users' installed extensions for Firefox and Google Chrome. 

### Config Options

Module contains two config options to filter reported extensions. By default the default installed Firefox/Google Chrome extensions are not reported. Filtering can be done by extension name or ID using the `BROWSER_EXTENSION_ID_IGNORELIST` and `BROWSER_EXTENSION_NAME_IGNORELIST` in the .env file.

#### Note: Does NOT report on Safari extensions.


Table Schema
-----

Database:
* name - varchar(255) - name of extension
* extension_id - varchar(255) - extension ID
* version - varchar(255) - extension version
* description - text - extension's description
* browser - varchar(255) - Firefox or Google Chrome
* date_installed - bigint - date extension was updated/installed
* developer - varchar(255) - name of extension developer, Firefox only
* enabled - boolean - If extension is enabled, Firefox only
* user - varchar(255) - user profile that contains extension

