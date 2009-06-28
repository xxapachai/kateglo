<?php
/**
 * Default settings
 */
// constants
define(APP_VERSION, 'v0.01.20090628'); // application version. See README.txt
define(APP_NAME, 'Kateglo (Beta) - kamus, tesaurus, dan glosarium bahasa Indonesia'); // application name
define(APP_SHORT, 'Kateglo (Beta)'); // application name
define(LF, "\n"); // line break
define(KTG_TIMEOUT, 15); // timeout, used for curl

// cleanup get
foreach ($_GET as $key => $val) $_GET[$key] = trim($val);
?>