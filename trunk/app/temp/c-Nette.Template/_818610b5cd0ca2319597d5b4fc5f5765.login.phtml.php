<?php //netteCache[01]000256a:2:{s:4:"time";s:21:"0.81112200 1268496998";s:9:"callbacks";a:1:{i:0;a:3:{i:0;a:2:{i:0;s:5:"Cache";i:1;s:9:"checkFile";}i:1;s:100:"/home/papi/files/workspace/working_copy/intersob-new/document_root/../app/templates/Auth/login.phtml";i:2;i:1268494714;}}}?><?php
// file …/templates/Auth/login.phtml
//

$_cb = LatteMacros::initRuntime($template, true, 'e00f15d29f'); unset($_extends);


//
// block content
//
if (!function_exists($_cb->blocks['content'][] = '_cbb28ef29716b_content')) { function _cbb28ef29716b_content() { extract(func_get_arg(0))
;$control->getWidget("loginForm")->render() ;
}}

//
// end of blocks
//

if ($_cb->extends) { ob_start(); }

if (SnippetHelper::$outputAllowed) {
$_cb->extends = "../@layout.phtml" ?>

<?php
}

if ($_cb->extends) { ob_end_clean(); LatteMacros::includeTemplate($_cb->extends, get_defined_vars(), $template)->render(); }
