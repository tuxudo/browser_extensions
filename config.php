
<?php

return [
/*
|===============================================
| Browser Extensions - extension ID ignore list
|===============================================
|
| List of extension IDs to be ignored when processing browser extensions
| The list is processed using regex
|
*/
'browser_extension_id_ignorelist' => env('BROWSER_EXTENSION_ID_IGNORELIST', [
    'nmmhkkegccagdldgiimedpiccmgmieda', // Chrome Web Store Payments
    'pkedcjkdefgpdelpbcmbmeomcjbeemfm', //	Chrome Media Router
    '@search.mozilla.org',
    'default-theme@mozilla.org',
    'firefox-compact-light@mozilla.org',
    'firefox-compact-dark@mozilla.org',
    'webcompat-reporter@mozilla.org',
    'webcompat@mozilla.org',
    'doh-rollout@mozilla.org',
    'formautofill@mozilla.org',
    'screenshots@mozilla.org',
    'reset-search-defaults@mozilla.com',
    ]),
    
/*
|===============================================
| Browser Extensions - extension name ignore list
|===============================================
|
| List of extension names to be ignored when processing browser extensions
| The list is processed using regex.
|
*/

'browser_extension_name_ignorelist' => env('BROWSER_EXTENSION_NAME_IGNORELIST', [
    '',
    ]),
];