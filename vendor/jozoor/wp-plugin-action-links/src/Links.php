<?php

namespace JO\Module\PluginActionLinks;

/**
 * Add list of links to display on the plugins page,
 * beside the activate and deactivate links.
 *
 * @since  1.0.0
 */
class Links
{

    /**
     * Get plugin basename file
     * plugin_basename(__FILE__)
     *
     * @since 1.0.0
     * @access protected
     * @var string
     */
    protected $plugin_basename;
    /**
     * Get links position, 'after' or 'before',
     * after activate and deactivate links or before it.
     *
     * @since 1.0.0
     * @access protected
     * @var string
     */
    protected $links_position;
    /**
     * Get action links.
     *
     * @since 1.0.0
     * @access protected
     * @var array
     */
    protected $links;

    /**
     * Get plugin basename file and links position
     *
     * @since 1.0.0
     * @access public
     * @param string $plugin_basename
     * @param string $links_position
     */
    public function __construct($plugin_basename, $links_position = 'after')
    {

        // set plugin basename
        $this->plugin_basename = $plugin_basename;

        // set links position
        $this->links_position = $links_position;

    }

    /**
     * Add new links.
     *
     * @since 1.0.0
     * @access public
     * @param  array $links
     * @return array
     */
    public function add($links)
    {

        // if empty or not array
        if (empty($links) || !is_array($links)) {
            throw new \Exception('New links should be an array and not empty!');
        }

        // set new links
        $this->links = $links;

        // now we can display links on the plugin page
        add_filter('plugin_action_links', array($this, 'add_links'), 10, 5);

    }

    /**
     * Add new links to merge with default plugin links
     *
     * @since 1.0.0
     * @access public
     * @param array $actions      default actions links
     * @param string $plugin_file plugin basename file
     */
    public function add_links($actions, $plugin_file)
    {

        // store the plugin
        static $plugin;

        // get the plugin basename file
        $plugin = $this->plugin_basename;

        // now we can add new links
        if ($plugin === $plugin_file) {

            // loop in links and add it
            foreach ($this->links as $link) {

                // add link before default links
                if ($this->links_position === 'before') {
                    $actions = array_merge($link, $actions);
                } else {
                    // add link after default links
                    $actions = array_merge($actions, $link);
                }

            } // end links loop

        }

        return $actions;

    }

}
