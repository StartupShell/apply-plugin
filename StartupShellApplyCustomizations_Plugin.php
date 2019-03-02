<?php


include_once('StartupShellApplyCustomizations_LifeCycle.php');

class StartupShellApplyCustomizations_Plugin extends StartupShellApplyCustomizations_LifeCycle {

    /**
     * See: http://plugin.michael-simpson.com/?page_id=31
     * @return array of option meta data.
     */
    public function getOptionMetaData() {
        //  http://plugin.michael-simpson.com/?page_id=31
        return array(
            //'_version' => array('Installed Version'), // Leave this one commented-out. Uncomment to test upgrades.
            'ATextInput' => array(__('Enter in some text', 'my-awesome-plugin')),
            'AmAwesome' => array(__('I like this awesome plugin', 'my-awesome-plugin'), 'false', 'true'),
            'CanDoSomething' => array(__('Which user role can do something', 'my-awesome-plugin'),
                                        'Administrator', 'Editor', 'Author', 'Contributor', 'Subscriber', 'Anyone')
        );
    }

//    protected function getOptionValueI18nString($optionValue) {
//        $i18nValue = parent::getOptionValueI18nString($optionValue);
//        return $i18nValue;
//    }

    protected function initOptions() {
        $options = $this->getOptionMetaData();
        if (!empty($options)) {
            foreach ($options as $key => $arr) {
                if (is_array($arr) && count($arr > 1)) {
                    $this->addOption($key, $arr[1]);
                }
            }
        }
    }

    public function getPluginDisplayName() {
        return 'Startup Shell | Apply Customizations';
    }

    protected function getMainPluginFileName() {
        return 'startupshell-apply-customizations.php';
    }

    /**
     * See: http://plugin.michael-simpson.com/?page_id=101
     * Called by install() to create any database tables if needed.
     * Best Practice:
     * (1) Prefix all table names with $wpdb->prefix
     * (2) make table names lower case only
     * @return void
     */
    protected function installDatabaseTables() {
        //        global $wpdb;
        //        $tableName = $this->prefixTableName('mytable');
        //        $wpdb->query("CREATE TABLE IF NOT EXISTS `$tableName` (
        //            `id` INTEGER NOT NULL");
    }

    /**
     * See: http://plugin.michael-simpson.com/?page_id=101
     * Drop plugin-created tables on uninstall.
     * @return void
     */
    protected function unInstallDatabaseTables() {
        //        global $wpdb;
        //        $tableName = $this->prefixTableName('mytable');
        //        $wpdb->query("DROP TABLE IF EXISTS `$tableName`");
    }


    /**
     * Perform actions when upgrading from version X to version Y
     * See: http://plugin.michael-simpson.com/?page_id=35
     * @return void
     */
    public function upgrade() {
    }

    public function addActionsAndFilters() {

        // Add options administration page
        // http://plugin.michael-simpson.com/?page_id=47
        add_action('admin_menu', array(&$this, 'addSettingsSubMenuPage'));

        // Example adding a script & style just for the options administration page
        // http://plugin.michael-simpson.com/?page_id=47
        //        if (strpos($_SERVER['REQUEST_URI'], $this->getSettingsSlug()) !== false) {
        //            wp_enqueue_script('my-script', plugins_url('/js/my-script.js', __FILE__));
        //            wp_enqueue_style('my-style', plugins_url('/css/my-style.css', __FILE__));
        //        }


        // Add Actions & Filters
        // http://plugin.michael-simpson.com/?page_id=37

        // Send new users to a special page
        function redirectOnFirstLogin( $custom_redirect_to, $redirect_to, $requested_redirect_to, $user )
        {
            // URL to redirect to
            $redirect_url = 'https://apply.startupshell.org/onboard';
            // How many times to redirect the user
            $num_redirects = 1;
            // If implementing this on an existing site, this is here so that existing users don't suddenly get the "first login" treatment
            // On a new site, you might remove this setting and the associated check
            // Alternative approach: run a script to assign the "already redirected" property to all existing users
            // Alternative approach: use a date-based check so that all registered users before a certain date are ignored
            // 172800 seconds = 48 hours
            $message_period = 172800;

            /*
                Cookie-based solution: captures users who registered within the last n hours
                The reason to set it as "last n hours" is so that if a user clears their cookies or logs in with a different browser,
                they don't get this same redirect treatment long after they're already a registered user
            */
            /*

            $key_name = 'redirect_on_first_login_' . $user->ID;

            if( strtotime( $user->user_registered ) > ( time() - $message_period )
                && ( !isset( $_COOKIE[$key_name] ) || intval( $_COOKIE[$key_name] ) < $num_redirects )
            )
            {
                if( isset( $_COOKIE[$key_name] ) )
                {
                    $num_redirects = intval( $_COOKIE[$key_name] ) + 1;
                }
                setcookie( $key_name, $num_redirects, time() + $message_period, COOKIEPATH, COOKIE_DOMAIN );
                return $redirect_url;
            }
            */
            /*
                User meta value-based solution, stored in the database
            */
            $key_name = 'redirect_on_first_login';
            // Third parameter ensures that the result is a string
            $current_redirect_value = get_user_meta( $user->ID, $key_name, true );
            if( strtotime( $user->user_registered ) > ( time() - $message_period )
                && ( '' == $current_redirect_value || intval( $current_redirect_value ) < $num_redirects )
            )
            {
                if( '' != $current_redirect_value )
                {
                    $num_redirects = intval( $current_redirect_value ) + 1;
                }
                update_user_meta( $user->ID, $key_name, $num_redirects );
                return $redirect_url;
            }
            else
            {
                return $custom_redirect_to;
            }
        }

        add_filter( 'rul_before_user', 'redirectOnFirstLogin', 10, 4 );

        // Modify the date picker on the onboard form to only display 4 future years
        add_filter( 'gform_date_max_year', function ( $max_year, $form, $field ) {
 
            return $form['id'] == 7 && $field->id == 5 ? date("Y")+4 : $max_year;
        }, 10, 3 );


        // Adding scripts & styles to all pages
        // Examples:
        //        wp_enqueue_script('jquery');
        //        wp_enqueue_style('my-style', plugins_url('/css/my-style.css', __FILE__));
        //        wp_enqueue_script('my-script', plugins_url('/js/my-script.js', __FILE__));


        // Register short codes
        // http://plugin.michael-simpson.com/?page_id=39


        // Register AJAX hooks
        // http://plugin.michael-simpson.com/?page_id=41

    }


}
