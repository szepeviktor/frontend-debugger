#!/usr/bin/php
<?php

/**
 * Create an HTML tag.
 *
 * @param string $name    Name of the tag.
 * @param string $content HTML content, providing false creates a self-closing tag.
 * @param array  $attrs   HTML attributes, can be given in place of $content creating a self-closing tag.
 * @return string HTML representation of the tag.
 */
function html_element( $name, $content = '', $attrs = array() ) {

        if ( empty( $name ) || ! is_string( $name ) ) {
            return '';
        }

        // attributes given in place of content
        if ( is_array( $content ) ) {
            $attrs = $content;
            $content = false;
        }

        $attr_string = '';
        foreach ( (array)$attrs as $attr_name => $attr_value ) {
            $attribute = ( false === $attr_value ) ? '%s' : '="%s"';
            $attr_string .= sprintf( ' %s' . $attribute, $attr_name, $attr_value );
        }

        $closing = ( false === $content ) ? '/>%s' : '>%s</%1$s>';

        $tag = sprintf( '<%s%s' . $closing,
            $name,
            $attr_string,
            $content
        );

        return $tag;
    }


print html_element( 'tag' ) . PHP_EOL;
print html_element( 'attrsINcontent', array( 'href' => 'http://url.css' ) ) . PHP_EOL;
print html_element( 'br', array() ) . PHP_EOL;
print html_element( 'link', false, array( 'href' => 'http://url.css' ) ) . PHP_EOL;
print html_element( 'style', 'a { font-weight: bold; }', array( 'rel' => 'stylesheet' ) ) . PHP_EOL;
print html_element( 'h1', 'Title', array( 'id' => 'masthead', 'class' => 'alpha beta' ) ) . PHP_EOL;
print html_element( 'div', 'Text of the tag.' ) . PHP_EOL;
print html_element( 'p', 'Boolean attribute.', array( 'bool' => false ) ) . PHP_EOL;
// invalid calls
print html_element( '' ) . '← invalid' . PHP_EOL;
print html_element( false ) . '← invalid' . PHP_EOL;
print html_element( true ) . '← invalid' . PHP_EOL;
