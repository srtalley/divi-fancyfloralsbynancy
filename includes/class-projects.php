<?php
namespace FancyFloralsByNancy\Theme;

class Projects {

    public function __construct() {
        add_action( 'init', array($this, 'add_projects_options'), 10, 1 );
        add_action('pre_get_portfolio_projects', array($this, 'set_portfolio_module_projects_menu_order'), 10, 1 );
        add_filter('et_pb_module_shortcode_attributes', array($this, 'add_pre_get_portfolio_projects'), 10, 3);
        add_filter('et_pb_portfolio_shortcode_output', array($this, 'remove_pre_get_portfolio_projects') );
    }

    /**
     * Set up the menu order column for projects
     */
    public function add_projects_options() {
        // the basic support (menu_order is included in the page-attributes)
        add_post_type_support('project', 'page-attributes');
        add_filter( 'manage_project_posts_columns', array($this, 'add_project_menu_order_column'), 10, 1 );
        add_action( 'manage_project_posts_custom_column', array($this, 'show_project_menu_order_value'), 10, 2 );
        add_filter('manage_edit-project_sortable_columns', array($this, 'sort_project_menu_order'), 10, 1 );
    }

    /**
     * Add a column to the post type admin area. Registers column
     * and sets the title
     */
    public function add_project_menu_order_column($columns) {
        $columns['menu_order'] = "Order"; //column key => title
        return $columns;
    }

    /**
     * Display the menu order value.
     */
    public function show_project_menu_order_value($column_name, $post_id){
        if ($column_name == 'menu_order') {
            echo get_post($post_id)->menu_order;
        }
    }

    /**
     * Make the menu order column sortable
     */
    public function sort_project_menu_order($columns){
        // column key => Query variable
        // menu_order is in Query by default so we can just set it
        $columns['menu_order'] = 'menu_order';
        return $columns;
    }

    /**
     * Run an action hook ("pre_get_portfolio_projects") when the portfolio module gets projects via WP_Query 
     */
    public function add_pre_get_portfolio_projects($props, $atts, $slug) {
        
        // Do nothing if this module isn't a portfolio
        $portfolio_module_slugs = array('et_pb_portfolio', 'et_pb_filterable_portfolio', 'et_pb_fullwidth_portfolio');
        if (!in_array($slug, $portfolio_module_slugs)) {
            return $props;
        }
        
        // Add an action to run during pre_get_posts on portfolio modules only
        add_action('pre_get_posts', array($this, 'do_portfolio_pre_get_posts'));

        return $props;
    }

    /**
     * Actually do the portfolio pre get post options
     */
    public function do_portfolio_pre_get_posts($query) {
            
        do_action('pre_get_portfolio_projects', $query);
    }

    /**
     * Remove the action when no longer needed
     */
    public function remove_pre_get_portfolio_projects($content) {
        
        remove_action('pre_get_posts', array($this, 'do_portfolio_pre_get_posts'));
        
        return $content;
    }

    /**
     * Sort the porfolio by menu order
     */
    function set_portfolio_module_projects_menu_order($query) {
        $query->set('orderby', 'menu_order');
        $query->set('order', 'ASC');
    }
} // end class Projects

$ffbn_projects = new Projects();




