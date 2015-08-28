<?php
/*
Frontend Debugger pseudo template version 1.3.0
*/

if ( ! function_exists( 'add_filter' ) ) {
    error_log( 'Break-in attempt detected: frontend_debugger_template_direct_access '
        . addslashes( @$_SERVER['REQUEST_URI'] )
    );
    ob_get_level() && ob_end_clean();
    if ( ! headers_sent() ) {
        header( 'Status: 403 Forbidden' );
        header( 'HTTP/1.1 403 Forbidden', true, 403 );
        header( 'Connection: Close' );
    }
    exit;
}

global $wp_scripts;
$fd = Frontend_Debugger::get_instance();
$fd->run_template();

?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8" />
<meta name="generator" content="WordPress Frontend Debugger plugin" />
<title>source: <?php wp_title(); ?></title>
<?php

/* https://cdnjs.cloudflare.com/ajax/libs/normalize/3.0.2/normalize.min.css */
print $fd->html_element( 'link', array(
    'href' => $fd->get_template_uri( 'css/normalize.min.css' ),
    'type' => "text/css",
    'rel'  => "stylesheet"
) );
/* http://google-code-prettify.googlecode.com/svn/trunk/README.html
   https://google-code-prettify.googlecode.com/svn/trunk/styles/desert.css */
print $fd->html_element( 'link', array(
    'href' => $fd->get_template_uri( 'css/desert.css' ),
    'type' => "text/css",
    'rel'  => "stylesheet"
) );
print $fd->html_element( 'link', array(
    'href' => $fd->get_template_uri( 'css/frontend.css' ),
    'type' => "text/css",
    'rel'  => "stylesheet"
) );

/* https://google-code-prettify.googlecode.com/svn/loader/run_prettify.js */
print $fd->html_element( 'script', '', array(
    'src'   => $fd->get_template_uri( 'js/run_prettify.js' ),
    'type'  => "text/javascript"
) );
print $fd->html_element(
    'script',
    "jQueryUrl='" . site_url( $wp_scripts->registered['jquery-core']->src ) . "';",
    array(
        'type'  => "text/javascript"
    )
);
print $fd->html_element( 'script', '', array(
        'src'   => $fd->get_template_uri( 'js/frontend.min.js' ),
        'type'  => "text/javascript"
) );

?>
</head>
<body>

<h1>Header</h1> <h3 id="page-url">view-source:<?php print $fd->part['url']; ?></h3>
<pre id="header-html" class="prettyprint linenums lang-html">
<?php

print $fd->part['header'];

?>
</pre>

<h1>Thumbnails</h1>
<pre id="thumbnail-html" class="prettyprint linenums lang-html">
<?php

print $fd->part['thumbnails'];

?>
</pre>

<h1>The Loop</h1>
<pre id="loop-html" class="prettyprint linenums lang-html">
<?php

print $fd->part['content'];;

?>
</pre>

<h1>Footer</h1>
<pre id="footer-html" class="prettyprint linenums lang-html">
<?php

print $fd->part['footer'];;

?>
</pre>

<h1>Included files</h1>
<pre id="includes" class="prettyprint lang-php">
<?php

var_export( $fd->part['includes'] );

?>
</pre>

<div id="control-panel">
    <a href="#header-html" class="part" title="Header">H</a>
    <a href="#thumbnail-html" class="part" title="Thumbnails">T</a>
    <a href="#loop-html" class="part" title="The Loop">L</a>
    <a href="#footer-html" class="part" title="Footer">F</a>
    <button id="toggle-linenums" title="Toogle line numbers">Line #</button>
    <button id="toggle-wrap" title="Toggle long line wrapping">Wrap</button>
    <button id="toggle-lineends" title="Toggle visible line ends">Line ends</button>
    <button id="button-highlight" title="Highlight elements to be fixed">Highlight</button>
</div>

</body>
</html>