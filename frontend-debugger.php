<?php
/*
Plugin Name: Frontend Debugger no-eval
Plugin URI: https://wordpress.org/plugins/frontend-debugger/
Description: Display page source prettified.
Version: 0.3-no-eval
Author: Viktor Szépe
Author URI: http://www.online1.hu/webdesign/
License: GNU General Public License (GPL) version 2
*/

if ( ! function_exists( 'add_filter' ) ) {
    header( 'Status: 403 Forbidden' );
    header( 'HTTP/1.1 403 Forbidden' );
    exit();
}

class Frontend_Debugger {

    private static $singletone;
    private $debugger_template_path;
    private $debugger_template_uri;
    private $header_separator = '<!-- frontend_debugger_HEADER -->';
    private $footer_separator = '<!-- frontend_debugger_FOOTER -->';
    private $was_loop_start = false;
    /**
     * Parts of HTML output.
     */
    private $parts = array();
    /**
     * Template files.
     */
    private $includes = array();

    public function __construct() {

        if ( is_admin() )
            return;

        $this->debugger_template_path = plugin_dir_path( __FILE__ ) . 'template/';
        $this->debugger_template_uri = plugin_dir_url( __FILE__ ) . 'template/';

        add_filter( 'template_include', array( $this, 'load_template' ) );
        add_action( 'wp_loaded', array( $this, 'ob_pre' ) );
        add_action( 'shutdown', array( $this, 'ob_post' ), 0 );
        add_action( 'get_header', array( $this, 'set_header' ) );
        // no way to detect END OF HEADER
        add_action( 'loop_start', array( $this, 'set_loop_start' ) );
        add_filter( 'the_content', array( $this, 'print_content_id' ), 0 );
        add_action( 'get_footer', array( $this, 'set_footer' ) );
    }

    public static function get_instance() {

        if ( ! isset( self::$singletone ) )
            self::$singletone = new Frontend_Debugger();

        return self::$singletone;
    }

    public function load_template( $name ) {

        if ( ! is_super_admin() && ! isset( $_GET['view-source'] ) )
            return $name;

        $this->includes['post_' . count( $this->includes )] = $name;
        $header = preg_split( '/\bget_header\s*\(.*\)\s*;/', $html, 1 );

        return $name;
    }

    public function run_template() {

        // needed for the template to work
        return '';
    }

    public function set_header( $name ) {

        $name = (string) $name;
        if ( '' === $name )
            $name = 'header.php';
        $this->includes['header_' . count( $this->includes )] = get_stylesheet_directory() . '/' . $name;

    }

    // no way to detect END OF HEADER
    public function set_loop_start() {

        if ( $this->was_loop_start )
            return;

        print $this->header_separator;
        $this->was_loop_start = true;
    }

    public function print_content_id( $content ) {

        print "<!-- frontend_debugger_post_ID:" . get_the_ID() . " -->";

        return $content;
    }

    public function set_footer( $name ) {

        $name = (string) $name;
        if ( '' === $name )
            $name = 'footer.php';
        $this->includes['footer_' . count( $this->includes )] = get_stylesheet_directory() . '/' . $name;

        print $this->footer_separator;
    }

    private function get_thumbnails() {

        wp_reset_query();

        $thumbnails = '';
        if ( have_posts() ) :
            while ( have_posts() ) {
                the_post();
                if ( has_post_thumbnail() ) :
                    $thumbnails .= sprintf( '%s<br/>%s',
                        $this->process_html( get_the_post_thumbnail( null, 'thumbnail' ) ),
                        get_the_post_thumbnail( null, 'thumbnail' )
                    );
                endif;
            }
        endif;

        return $thumbnails;
    }

    public function ob_pre() {

        ob_start();
    }

    public function ob_post() {

        $html = ob_get_contents();
        ob_end_clean();

        // no way to detect END OF HEADER
        $header = explode( $this->header_separator, $html );
        if ( count( $header ) !== 2 )
            die( 'Header separator is missing!' );
        $this->part['header'] = $this->process_html( $header[0] );
        $content = explode( $this->footer_separator, $header[1] );
        if ( count( $content ) !== 2 )
            die( 'Footer separator is missing!' );
        $this->part['content'] = $this->process_html( $content[0] );
        $this->part['footer'] = $this->process_html( $content[1] );

        $this->part['thumbnails'] = $this->get_thumbnails();

        $this->part['includes'] = $this->includes;

        require_once( $this->get_template_path( 'frontend.php' ) );
    }

    private function process_html( $html ) {

        // ^M -> "CR" U+240D
        return str_replace( "\r", "&#x240d;", htmlspecialchars( $html ) );
    }

    public function get_parts() {

        return $this->part;
    }

    public function get_template_path( $file ) {

        return $this->debugger_template_path . $file;
    }

    public function get_template_uri( $file ) {

        return $this->debugger_template_uri . $file;
    }

    /**
     * Create an HTML tag.
     *
     * @param string $name    Name of the tag.
     * @param string $content HTML content, providing false creates a self-closing tag.
     * @param array  $attrs   HTML attributes, can be given in place of $content creating a self-closing tag.
     * @return string HTML representation of the tag.
     */
    public function html_element( $name, $content = '', $attrs = array() ) {

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
            // Boolean attributes
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

}

Frontend_Debugger::get_instance();
