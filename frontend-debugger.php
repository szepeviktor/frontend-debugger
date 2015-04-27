<?php
/*
Plugin Name: Frontend Debugger
Plugin URI: https://wordpress.org/plugins/frontend-debugger/
Description: Display page source prettified.
Version: 0.7.1
Author: Viktor Szépe
Author URI: http://www.online1.hu/webdesign/
License: GNU General Public License (GPL) version 2
GitHub Plugin URI: https://github.com/szepeviktor/frontend-debugger
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
    private $current_template;
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
        add_action( 'get_header', array( $this, 'set_header' ) );
        add_filter( 'the_content', array( $this, 'print_content_id' ), 0 );
        // @TODO - get_sidebar
        // get_template_part_$ https://github.com/szepeviktor/wordpress-plugin-construction/blob/master/what-the-file/what-the-file.php
        // get_search_form
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
        $this->current_template = $name;

        return $this->get_template_path( 'frontend.php' );
    }

    public function run_template() {

        global $wp;

        // no other way to detect END OF HEADER
        // @TODO WP FileSystem API
        $php = file_get_contents( $this->current_template );
        $header_php = preg_split( '/\b(get_header\s*\(.*\)\s*;)/', $php, 2, PREG_SPLIT_DELIM_CAPTURE );

        // remove opening PHP tag
        $header_php[0] = substr( $header_php[0], 5 );
        // generate header
        ob_start();
        eval( $header_php[0] . $header_php[1] );
        $this->part['header'] = $this->process_html( ob_get_contents() );
        ob_end_clean();

        // no other way to detect END OF FOOTER
        $footer_php = preg_split( '/\b(get_footer\s*\(.*\)\s*;)/', $header_php[2], 2, PREG_SPLIT_DELIM_CAPTURE );

        // generate content
        ob_start();
        eval( $footer_php[0] );
        $this->part['content'] = $this->process_html( ob_get_contents() );
        ob_end_clean();

        // generate footer
        ob_start();
        eval( $footer_php[1] . $footer_php[2] );
        $this->part['footer'] = $this->process_html( ob_get_contents() );
        ob_end_clean();

        $this->part['includes'] = $this->includes;

        $this->part['thumbnails'] = $this->get_thumbnails();

        $this->part['url'] = home_url( add_query_arg( array(), $wp->request ) );
        $this->part['url'] = trailingslashit( $this->part['url'] );
    }

    public function set_header( $name ) {

        $name = (string) $name;
        if ( '' === $name )
            $name = 'header.php';
        $this->includes['header_' . count( $this->includes )] = get_stylesheet_directory() . '/' . $name;

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
