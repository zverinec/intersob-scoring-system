<?php //netteCache[01]000253a:2:{s:4:"time";s:21:"0.04715300 1268499568";s:9:"callbacks";a:1:{i:0;a:3:{i:0;a:2:{i:0;s:5:"Cache";i:1;s:9:"checkFile";}i:1;s:98:"/home/papi/files/workspace/working_copy/intersob-new/document_root/../app/templates//@layout.phtml";i:2;i:1268495131;}}}?><?php
// file …/templates//@layout.phtml
//

$_cb = LatteMacros::initRuntime($template, NULL, '1777729865'); unset($_extends);


//
// block content
//
if (!function_exists($_cb->blocks['content'][] = '_cbb86908b3d1c_content')) { function _cbb86908b3d1c_content() { extract(func_get_arg(0))
;
}}

//
// end of blocks
//

if ($_cb->extends) { ob_start(); }

if (SnippetHelper::$outputAllowed) {
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
    <title>INTERdisciplinární SOutěž Brno</title>
    <link rel="stylesheet" media="all" href="<?php echo $baseUri ?>css/main.css" type="text/css">
<?php if ($presenter->refresh): ?>
    <script type="text/javascript">
    	window.setInterval("window.location.reload()",10000);
    </script>
<?php endif ?>
</head>

<body>
<div id="content">
	<ul id="menu">
		<li><a href="<?php echo TemplateHelpers::escapeHtml($presenter->link('Default:')) ?>" title="Výsledky">Výsledky</a>
		<li><a href="<?php echo TemplateHelpers::escapeHtml($presenter->link('Default:tasks')) ?>" title="Stanoviště">Stanoviště</a>
		<li><a href="<?php echo TemplateHelpers::escapeHtml($presenter->link('Default:detail')) ?>" title="Podrobné výsledky">Podrobné výsledky</a>
		<li><a href="<?php echo TemplateHelpers::escapeHtml($presenter->link('Admin:tasks')) ?>" title="Editovat výsledky">Editovat výsledky</a>
<?php if ($logged): ?>
		<li><a href="<?php echo TemplateHelpers::escapeHtml($presenter->link('Admin:logout')) ?>" title="Úkoly">Odhlásit</a>
<?php endif ?>

	</ul>
	<?php if (!$_cb->extends) { call_user_func(reset($_cb->blocks['content']), get_defined_vars()); } ?>

</div>
</body>
</html><?php
}

if ($_cb->extends) { ob_end_clean(); LatteMacros::includeTemplate($_cb->extends, get_defined_vars(), $template)->render(); }
