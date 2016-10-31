<?php

namespace tad\WPBrowser\Adapters;


class WP
{
    public function locate_template($template_names, $load = false, $require_once = true)
    {
        return locate_template($template_names, $load, $require_once);
    }

    public function add_action($tag, $function_to_add, $priority = 10, $accepted_args = 1)
    {
        return add_action($tag, $function_to_add, $priority, $accepted_args);
    }

    public function add_filter($tag, $function_to_add, $priority = 10, $accepted_args = 1)
    {
        return add_filter($tag, $function_to_add, $priority, $accepted_args);
    }

    public function update_option($option, $new_value, $autoload = null)
    {
        return update_option($option, $new_value, $autoload);
    }

    public function flush_rewrite_rules($hard = true)
    {
        return flush_rewrite_rules($hard);
    }

    public function home_url($path = '', $scheme = null)
    {
        return home_url($path, $scheme);
    }

    public function admin_url($path = '', $scheme = 'admin')
    {
        return admin_url($path, $scheme);
    }

    public function set_site_transient($transient, $value, $expiration = 0)
    {
        return set_site_transient($transient, $value, $expiration = 0);
    }

    public function switch_theme($stylesheet)
    {
        return switch_theme($stylesheet);
    }

    public function do_action($tag, $arg = '')
    {
        return do_action($tag, $arg);
    }

    public function apply_filters($tag, $value)
    {
        return do_action($tag, $value);
    }

    public function WP_CONTENT_DIR()
    {
        return defined('WP_CONTENT_DIR') ? WP_CONTENT_DIR : '';
    }
}
