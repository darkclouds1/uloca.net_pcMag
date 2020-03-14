<?php

namespace WPStaging\Backend\Modules\Jobs;

// No Direct Access
if( !defined( "WPINC" ) ) {
    die;
}

use WPStaging\Utils\Logger;
use WPStaging\WPStaging;
use WPStaging\Utils\Helper;
use WPStaging\Utils\Strings;
use WPStaging\Backend\Modules\Jobs\JobExecutable;

/**
 * Class Data
 * @package WPStaging\Backend\Modules\Jobs
 */
class DataExternal extends JobExecutable {

    /**
     * @var \wpdb
     */
    private $db;

    /**
     * @var string
     */
    private $prefix;

    /**
     *
     * @var string
     */
    private $homeUrl;

    /**
     * Tables e.g wpstg3_options
     * @var array
     */
    private $tables;

    /**
     * Initialize
     */
    public function initialize() {
        $this->db           = $this->getStagingDB();
        $this->productionDb = WPStaging::getInstance()->get( "wpdb" );
        $this->prefix       = $this->options->prefix;
        $this->db->prefix   = $this->options->databasePrefix;

        $this->getTables();

        $helper        = new Helper();
        $this->homeUrl = $helper->get_home_url();

        // Fix current step
        if( 0 == $this->options->currentStep ) {
            $this->options->currentStep = 0;
        }
    }

    /**
     * Get database object to interact with
     */
    private function getStagingDB() {
        return new \wpdb( $this->options->databaseUser, str_replace( "\\\\", "\\", $this->options->databasePassword ), $this->options->databaseDatabase, $this->options->databaseServer );
    }

    /**
     * Get a list of tables to copy
     */
    private function getTables() {
        $strings      = new Strings();
        $this->tables = array();
        foreach ( $this->options->tables as $table ) {
            $this->tables[] = $this->options->prefix . $strings->str_replace_first( $this->productionDb->prefix, null, $table );
        }
    }

    /**
     * Calculate Total Steps in This Job and Assign It to $this->options->totalSteps
     * @return void
     */
    protected function calculateTotalSteps() {
        $this->options->totalSteps = 20;
    }

    /**
     * Start Module
     * @return object
     */
    public function start() {
        // Execute steps
        $this->run();

        // Save option, progress
        $this->saveOptions();

        return ( object ) $this->response;
    }

    /**
     * Execute the Current Step
     * Returns false when over threshold limits are hit or when the job is done, true otherwise
     * @return bool
     */
    protected function execute() {
        // Fatal error. Let this happen never and break here immediately
        if( $this->isRoot() ) {
            return false;
        }

        // Over limits threshold
        if( $this->isOverThreshold() ) {
            // Prepare response and save current progress
            $this->prepareResponse( false, false );
            $this->saveOptions();
            return false;
        }

        // No more steps, finished
        if( $this->isFinished() ) {
            $this->prepareResponse( true, false );
            return false;
        }

        // Execute step
        $stepMethodName = "step" . $this->options->currentStep;
        if( !$this->{$stepMethodName}() ) {
            $this->prepareResponse( false, false );
            return false;
        }

        // Prepare Response
        $this->prepareResponse();

        // Not finished
        return true;
    }

    /**
     * Checks Whether There is Any Job to Execute or Not
     * @return bool
     */
    protected function isFinished() {
        return
            !$this->isRunning() ||
            $this->options->currentStep > $this->options->totalSteps ||
            !method_exists($this, "step" . $this->options->currentStep)
        ;
    }

    /**
     * Check if current operation is done on the root folder or on the live DB
     * @return boolean
     */
    protected function isRoot() {

        // Prefix is the same as the one of live site
//      $wpdb = WPStaging::getInstance()->get( "wpdb" );
//      if( $wpdb->prefix === $this->prefix ) {
//         return true;
//      }
        // CloneName is empty
        $name = ( array ) $this->options->cloneDirectoryName;
        if( empty( $name ) ) {
            return true;
        }

        // Live Path === Staging path
        if( $this->homeUrl . $this->options->cloneDirectoryName === $this->homeUrl ) {
            return true;
        }

        return false;
    }

    /**
     * Check if table exists
     * @param string $table
     * @return boolean
     */
    protected function isTable( $table ) {
        if( $this->db->get_var( "SHOW TABLES LIKE '{$table}'" ) != $table ) {
            $this->log( "Table {$table} does not exist", Logger::TYPE_ERROR );
            return false;
        }
        return true;
    }

    /**
     * Copy wp-config.php from the staging site if it is located outside of root one level up or
     * copy default wp-config.php if production site uses bedrock or any other boilerplate solution that stores wp default config data elsewhere.
     * @return boolean
     */
    protected function step0() {
        $this->log( "Preparing Data Step0: Copy wp-config.php file", Logger::TYPE_INFO );

        $dir = trailingslashit( dirname( ABSPATH ) );

        $source = $dir . 'wp-config.php';

        $destination = $this->options->destinationDir . 'wp-config.php';

        // Check if there is already a valid wp-config.php in root of staging site
        if( $this->isValidWpConfig( $destination ) ) {
            return true;
        }

        // Check if there is a valid wp-config.php outside root of wp production site
        if( $this->isValidWpConfig( $source ) ) {
            // Copy it to staging site
            if( $this->copy( $source, $destination ) ) {
                return true;
            }
        }

        // No valid wp-config.php found so let's copy wp stagings default wp-config.php to staging site
        $source = WPSTG_PLUGIN_DIR . "Backend/helpers/wp-config.php";
        if( $this->copy( $source, $destination ) ) {
            // add missing db credentials to wp-config.php
            if( !$this->alterWpConfig( $destination ) ) {
                $this->log( "Preparing Data Step0: Can not alter db credentials in wp-config.php", Logger::TYPE_INFO );
                return false;
            }
        }

        $this->log( "Preparing Data Step0: Successful", Logger::TYPE_INFO );
        return true;
    }

    /**
     * Copy files with symlink support
     * @param type $source
     * @param type $destination
     * @return boolean
     */
    protected function copy( $source, $destination ) {
        // Copy symbolic link
        if( is_link( $source ) ) {
            $this->log( "Preparing Data: Symbolic link found...", Logger::TYPE_INFO );
            if( !@copy( readlink( $source ), $destination ) ) {
                $errors = error_get_last();
                $this->log( "Preparing Data: Failed to copy {$source} Error: {$errors['message']} {$source} -> {$destination}", Logger::TYPE_ERROR );
                return false;
            }
        }

        // Copy file
        if( !@copy( $source, $destination ) ) {
            $errors = error_get_last();
            $this->log( "Preparing Data Step0: Failed to copy {$source}! Error: {$errors['message']} {$source} -> {$destination}", Logger::TYPE_ERROR );
            return false;
        }

        return true;
    }

    /**
     * Make sure wp-config.php contains correct db credentials
     * @param type $source
     * @return boolean
     */
    protected function alterWpConfig( $source ) {
        $content = file_get_contents( $source );

        if( false === ($content = file_get_contents( $source )) ) {
            return false;
        }

        $search = "// ** MySQL settings ** //";

        $replace = "// ** MySQL settings ** //\r\n
define( 'DB_NAME', '" . DB_NAME . "' );\r\n
/** MySQL database username */\r\n
define( 'DB_USER', '" . DB_USER . "' );\r\n
/** MySQL database password */\r\n
define( 'DB_PASSWORD', '" . DB_PASSWORD . "' );\r\n
/** MySQL hostname */\r\n
define( 'DB_HOST', '" . DB_HOST . "' );\r\n
/** Database Charset to use in creating database tables. */\r\n
define( 'DB_CHARSET', '" . DB_CHARSET . "' );\r\n
/** The Database Collate type. Don't change this if in doubt. */\r\n
define( 'DB_COLLATE', '" . DB_COLLATE . "' );\r\n";

        $content = str_replace( $search, $replace, $content );

        if( false === @wpstg_put_contents( $source, $content ) ) {
            $this->log( "Preparing Data: Can't save wp-config.php", Logger::TYPE_ERROR );
            return false;
        }

        return true;
    }

    /**
     * Check if wp-config.php contains important constants
     * @param type $source
     * @return boolean
     */
    protected function isValidWpConfig( $source ) {

        if( !is_file( $source ) && !is_link( $source ) ) {
            return false;
        }

        $content = file_get_contents( $source );

        if( false === ($content = file_get_contents( $source )) ) {
            return false;
        }

        // Get DB_NAME from wp-config.php
        preg_match( "/define\s*\(\s*['\"]DB_NAME['\"]\s*,\s*(.*)\s*\);/", $content, $matches );

        if( empty( $matches[1] ) ) {
            return false;
        }

        // Get DB_USER from wp-config.php
        preg_match( "/define\s*\(\s*['\"]DB_USER['\"]\s*,\s*(.*)\s*\);/", $content, $matches );

        if( empty( $matches[1] ) ) {
            return false;
        }

        // Get DB_PASSWORD from wp-config.php
        preg_match( "/define\s*\(\s*['\"]DB_PASSWORD['\"]\s*,\s*(.*)\s*\);/", $content, $matches );

        if( empty( $matches[1] ) ) {
            return false;
        }

        // Get DB_HOST from wp-config.php
        preg_match( "/define\s*\(\s*['\"]DB_HOST['\"]\s*,\s*(.*)\s*\);/", $content, $matches );

        if( empty( $matches[1] ) ) {
            return false;
        }
        return true;
    }

    /**
     * Replace "siteurl" and "home"
     * @return bool
     */
    protected function step1() {
        $this->log( "Preparing Data Step1: Updating siteurl and homeurl in {$this->prefix}options {$this->db->last_error}", Logger::TYPE_INFO );

        // Skip - Table does not exist
        if( false === $this->isTable( $this->prefix . 'options' ) ) {
            return true;
        }
        // Skip - Table is not selected or updated
        if( !in_array( $this->prefix . 'options', $this->tables ) ) {
            $this->log( "Preparing Data Step1: Skipping" );
            return true;
        }

        $this->log( "Preparing Data Step1: Updating siteurl and homeurl to " . $this->getStagingSiteUrl() );
        // Replace URLs
        $result = $this->db->query(
                $this->db->prepare(
                        "UPDATE {$this->prefix}options SET option_value = %s WHERE option_name = 'siteurl' or option_name='home'", $this->getStagingSiteUrl()
                )
        );



        // All good
        if( $result ) {
            return true;
        }

        $this->log( "Preparing Data Step1: Skip updating siteurl and homeurl in {$this->prefix}options. Probably already did! {$this->db->last_error}", Logger::TYPE_WARNING );
        return true;
    }

    /**
     * Update "wpstg_is_staging_site"
     * @return bool
     */
    protected function step2() {

        $this->log( "Preparing Data Step2: Updating row wpstg_is_staging_site in {$this->prefix}options {$this->db->last_error}" );

        // Skip - Table does not exist
        if( false === $this->isTable( $this->prefix . 'options' ) ) {
            $this->log( "Preparing Data Step2: Skipping" );
            return true;
        }
        // Skip - Table is not selected or updated
        if( !in_array( $this->prefix . 'options', $this->tables ) ) {
            $this->log( "Preparing Data Step2: Skipping" );
            return true;
        }

        $delete = $this->db->query(
                $this->db->prepare(
                        "DELETE FROM `{$this->prefix}options` WHERE `option_name` = %s;", 'wpstg_is_staging_site'
                )
        );


        // No errors but no option name such as wpstg_is_staging_site
        $insert = $this->db->query(
                $this->db->prepare(
                        "INSERT INTO {$this->prefix}options (option_name,option_value) VALUES ('wpstg_is_staging_site',%s)", "true"
                )
        );

        // All good
        if( $insert ) {
            $this->log( "Preparing Data Step2: Successful", Logger::TYPE_INFO );
            return true;
        }

        $this->log( "Preparing Data Step2: Failed to update wpstg_is_staging_site in {$this->prefix}options {$this->db->last_error}", Logger::TYPE_ERROR );
        return false;
    }

    /**
     * Update rewrite_rules
     * @return bool
     */
    protected function step3() {

        $this->log( "Preparing Data Step3: Updating rewrite_rules in {$this->prefix}options {$this->db->last_error}" );

        // Keep Permalinks
        if( isset( $this->settings->keepPermalinks ) && $this->settings->keepPermalinks === "1" ) {
            $this->log( "Preparing Data Step3: Skipping" );
            return true;
        }

        // Skip - Table does not exist
        if( false === $this->isTable( $this->prefix . 'options' ) ) {
            $this->log( "Preparing Data Step3: Skipping" );
            return true;
        }

        // Skip - Table is not selected or updated
        if( !in_array( $this->prefix . 'options', $this->tables ) ) {
            $this->log( "Preparing Data Step3: Skipping" );
            return true;
        }

        $result = $this->db->query(
                $this->db->prepare(
                        "UPDATE {$this->prefix}options SET option_value = %s WHERE option_name = 'rewrite_rules'", ' '
                )
        );

        // All good
        if( $result ) {
            return true;
        }

        //$this->log( "Preparing Data Step3: Failed to update rewrite_rules in {$this->prefix}options {$this->db->last_error}", Logger::TYPE_ERROR );
        return true;
    }

    /**
     * Update Table Prefix in wp_usermeta and wp_options
     * @return bool
     */
    protected function step4() {
        $this->log( "Preparing Data Step4: Updating db prefix in {$this->prefix}usermeta. " );

        // Skip - Table does not exist
        if( false === $this->isTable( $this->prefix . 'usermeta' ) ) {
            return true;
        }

        // Skip - Table is not selected or updated
        if( !in_array( $this->prefix . 'usermeta', $this->tables ) ) {
            $this->log( "Preparing Data Step4: Skipping" );
            return true;
        }



        $update = $this->db->query(
                $this->db->prepare(
                        "UPDATE {$this->prefix}usermeta SET meta_key = replace(meta_key, %s, %s) WHERE meta_key LIKE %s", $this->productionDb->prefix, $this->prefix, $this->productionDb->prefix . "_%"
                )
        );

        if( false === $update ) {
            $this->log( "Preparing Data Step4: Failed to update {$this->prefix}usermeta meta_key database table prefixes; {$this->db->last_error}", Logger::TYPE_ERROR );
            $this->returnException( "Preparing Data Step4: Failed to update {$this->prefix}usermeta meta_key database table prefixes; {$this->db->last_error}" );
            return false;
        }



        return true;
    }

    /**
     * Update Table prefix in wp-config.php
     * @return bool
     */
    protected function step5() {
        $path = $this->options->destinationDir . "wp-config.php";

        $this->log( "Preparing Data Step5: Updating table_prefix in {$path} to " . $this->prefix );
        if( false === ($content = file_get_contents( $path )) ) {
            $this->log( "Preparing Data Step5: Failed to update table_prefix in {$path}. Can't read contents", Logger::TYPE_ERROR );
            return false;
        }

        // Replace table prefix
        $pattern     = '/\$table_prefix\s*=\s*(.*).*/';
        $replacement = '$table_prefix = \'' . $this->prefix . '\'; // Changed by WP Staging';
        $content     = preg_replace( $pattern, $replacement, $content );

        if( null === $content ) {
            $this->log( "Preparing Data Step5: Failed to update table_prefix in {$path}. Can't read contents", Logger::TYPE_ERROR );
            return false;
        }

        // Replace URLs
        $content = str_replace( $this->homeUrl, $this->getStagingSiteUrl(), $content );

        if( false === @wpstg_put_contents( $path, $content ) ) {
            $this->log( "Preparing Data Step5: Failed to update $table_prefix in {$path} to " . $this->prefix . ". Can't save contents", Logger::TYPE_ERROR );
            return false;
        }

        return true;
    }

    /**
     * Reset index.php to original file
     * This is needed if live site is located in subfolder
     * Check first if main wordpress is used in subfolder and index.php in parent directory
     * @see: https://codex.wordpress.org/Giving_WordPress_Its_Own_Directory
     * @return bool
     */
    protected function step6() {

        if( !$this->isSubDir() ) {
            $this->debugLog( "Preparing Data Step6: WP installation is not in a subdirectory! All good, skipping this step" );
            return true;
        }

        $path = $this->options->destinationDir . "index.php";

        if( false === ($content = file_get_contents( $path )) ) {
            $this->log( "Preparing Data Step6: Failed to reset {$path} for sub directory; can't read contents", Logger::TYPE_ERROR );
            return false;
        }


        if( !preg_match( "/(require(.*)wp-blog-header.php' \);)/", $content, $matches ) ) {
            $this->log(
                    "Preparing Data Step6: Failed to reset index.php for sub directory; wp-blog-header.php is missing", Logger::TYPE_ERROR
            );
            return false;
        }
        $this->log( "Preparing Data: WP installation is in a subdirectory. Progressing..." );

        $pattern = "/require(.*) dirname(.*) __FILE__ (.*) \. '(.*)wp-blog-header.php'(.*);.*/";

        $replace = "require( dirname( __FILE__ ) . '/wp-blog-header.php' ); // " . $matches[0];
        //$replace.= " // Changed by WP-Staging";



        if( null === ($content = preg_replace( array($pattern), $replace, $content )) ) {
            $this->log( "Preparing Data: Failed to reset index.php for sub directory; replacement failed", Logger::TYPE_ERROR );
            return false;
        }

        if( false === @wpstg_put_contents( $path, $content ) ) {
            $this->log( "Preparing Data: Failed to reset index.php for sub directory; can't save contents", Logger::TYPE_ERROR );
            return false;
        }
        $this->Log( "Preparing Data Step6: Finished successfully" );
        return true;
    }

    /**
     * Update wpstg_rmpermalinks_executed
     * @return bool
     */
    protected function step7() {

        $this->log( "Preparing Data Step7: Updating wpstg_rmpermalinks_executed in {$this->prefix}options {$this->db->last_error}" );

        // Skip - Table does not exist
        if( false === $this->isTable( $this->prefix . 'options' ) ) {
            $this->log( "Preparing Data Step7: Skipping Table {$this->prefix}'options' does not exist" );
            return true;
        }

        // Skip - Table is not selected or updated
        if( !in_array( $this->prefix . 'options', $this->tables ) ) {
            $this->log( "Preparing Data Step7: Skipping" );
            return true;
        }

        $result = $this->db->query(
                $this->db->prepare(
                        "UPDATE {$this->prefix}options SET option_value = %s WHERE option_name = 'wpstg_rmpermalinks_executed'", ' '
                )
        );


        $this->Log( "Preparing Data Step7: Finished successfully" );
        return true;
    }

    /**
     * Update permalink_structure
     * @return bool
     */
    protected function step8() {

        $this->log( "Preparing Data Step8: Updating permalink_structure in {$this->prefix}options {$this->db->last_error}" );

        // Keep Permalinks
        if( isset( $this->settings->keepPermalinks ) && $this->settings->keepPermalinks === "1" ) {
            $this->log( "Preparing Data Step8: Skipping" );
            return true;
        }

        // Skip - Table does not exist
        if( false === $this->isTable( $this->prefix . 'options' ) ) {
            $this->log( "Preparing Data Step8: Skipping" );
            return true;
        }

        // Skip - Table is not selected or updated
        if( !in_array( $this->prefix . 'options', $this->tables ) ) {
            $this->log( "Preparing Data Step8: Skipping" );
            return true;
        }

        $result = $this->db->query(
                $this->db->prepare(
                        "UPDATE {$this->prefix}options SET option_value = %s WHERE option_name = 'permalink_structure'", ' '
                )
        );

        // All good
        if( $result ) {
            $this->Log( "Preparing Data Step8: Finished successfully" );
            return true;
        }

        $this->log( "Failed to update permalink_structure in {$this->prefix}options {$this->db->last_error}", Logger::TYPE_ERROR );
        return true;
    }

    /**
     * Update blog_public option to not allow staging site to be indexed by search engines
     * @return bool
     */
    protected function step9() {

        $this->log( "Preparing Data Step9: Set staging site to noindex" );

        if( false === $this->isTable( $this->prefix . 'options' ) ) {
            return true;
        }

        // Skip - Table is not selected or updated
        if( !in_array( $this->prefix . 'options', $this->tables ) ) {
            $this->log( "Preparing Data Step9: Skipping" );
            return true;
        }

        $result = $this->db->query(
                $this->db->prepare(
                        "UPDATE {$this->prefix}options SET option_value = %s WHERE option_name = 'blog_public'", '0'
                )
        );

        // All good
        if( $result ) {
            $this->Log( "Preparing Data Step9: Finished successfully" );
            return true;
        }

        $this->log( "Can not update staging site to noindex. Possible already done!", Logger::TYPE_WARNING );
        return true;
    }

    /**
     * Update WP_HOME in wp-config.php
     * @return bool
     */
    protected function step10() {
        $path = $this->options->destinationDir . "wp-config.php";

        $this->log( "Preparing Data Step10: Updating WP_HOME in wp-config.php to " . $this->getStagingSiteUrl() );

        if( false === ($content = file_get_contents( $path )) ) {
            $this->log( "Preparing Data Step10: Failed to update WP_HOME in wp-config.php. Can't read wp-config.php", Logger::TYPE_ERROR );
            return false;
        }


        // Get WP_HOME from wp-config.php
        preg_match( "/define\s*\(\s*['\"]WP_HOME['\"]\s*,\s*(.*)\s*\);/", $content, $matches );

        if( !empty( $matches[1] ) ) {
            $matches[1];

            $pattern = "/define\s*\(\s*['\"]WP_HOME['\"]\s*,\s*(.*)\s*\);.*/";

            $replace = "define('WP_HOME','" . $this->getStagingSiteUrl() . "'); // " . $matches[1];
            //$replace.= " // Changed by WP-Staging";

            if( null === ($content = preg_replace( array($pattern), $replace, $content )) ) {
                $this->log( "Preparing Data: Failed to update WP_HOME", Logger::TYPE_ERROR );
                return false;
            }
        } else {
            $this->log( "Preparing Data Step10: WP_HOME not defined in wp-config.php. Skipping this step." );
        }

        if( false === @wpstg_put_contents( $path, $content ) ) {
            $this->log( "Preparing Data Step10: Failed to update WP_HOME. Can't save contents", Logger::TYPE_ERROR );
            return false;
        }
        $this->Log( "Preparing Data Step10: Finished successfully" );
        return true;
    }

    /**
     * Update WP_SITEURL in wp-config.php
     * @return bool
     */
    protected function step11() {
        $path = $this->options->destinationDir . "wp-config.php";

        $this->log( "Preparing Data Step11: Updating WP_SITEURL in wp-config.php to " . $this->getStagingSiteUrl() );

        if( false === ($content = file_get_contents( $path )) ) {
            $this->log( "Preparing Data Step11: Failed to update WP_SITEURL in wp-config.php. Can't read wp-config.php", Logger::TYPE_ERROR );
            return false;
        }


        // Get WP_SITEURL from wp-config.php
        preg_match( "/define\s*\(\s*['\"]WP_SITEURL['\"]\s*,\s*(.*)\s*\);/", $content, $matches );

        if( !empty( $matches[1] ) ) {
            $matches[1];

            $pattern = "/define\s*\(\s*['\"]WP_SITEURL['\"]\s*,\s*(.*)\s*\);.*/";

            $replace = "define('WP_SITEURL','" . $this->getStagingSiteUrl() . "'); // " . $matches[1];
            //$replace.= " // Changed by WP-Staging";

            if( null === ($content = preg_replace( array($pattern), $replace, $content )) ) {
                $this->log( "Preparing Data Step11: Failed to update WP_SITEURL", Logger::TYPE_ERROR );
                return false;
            }
        } else {
            $this->log( "Preparing Data Step11: WP_SITEURL not defined in wp-config.php. Skipping this step." );
        }


        if( false === @wpstg_put_contents( $path, $content ) ) {
            $this->log( "Preparing Data Step11: Failed to update WP_SITEURL. Can't save contents", Logger::TYPE_ERROR );
            return false;
        }
        $this->Log( "Preparing Data Step11: Finished successfully" );
        return true;
    }

    /**
     * Set true WP_DEBUG & WP_DEBUG_DISPLAY in wp-config.php
     * @return bool
     */
//   protected function step12() {
//      $path = ABSPATH . $this->options->cloneDirectoryName . "/wp-config.php";
//
//      $this->log( "Preparing Data Step12: Set WP_DEBUG to true in wp-config.php." );
//
//      if( false === ($content = file_get_contents( $path )) ) {
//         $this->log( "Preparing Data Step12: Failed to update WP_DEBUG in wp-config.php. Can't read wp-config.php", Logger::TYPE_ERROR );
//         return false;
//      }
//
//
//      // Get WP_DEBUG from wp-config.php
//      preg_match( "/define\s*\(\s*'WP_DEBUG'\s*,\s*(.*)\s*\);/", $content, $matches );
//
//      // Found it!
//      if( !empty( $matches[1] ) ) {
//         $matches[1];
//
//         $pattern = "/define\s*\(\s*'WP_DEBUG'\s*,\s*(.*)\s*\);/";
//
//         $replace = "define('WP_DEBUG',true); // " . $matches[1];
//         $replace.= " // Changed by WP-Staging";
//
//         if( null === ($content = preg_replace( array($pattern), $replace, $content )) ) {
//            $this->log( "Preparing Data Step12: Failed to update WP_DEBUG", Logger::TYPE_ERROR );
//            return false;
//         }
//      } else {
//         $this->log( "Preparing Data Step12: WP_DEBUG not defined in wp-config.php. Skip it." );
//      }
//
//
//
//      // Get WP_DEBUG_DISPLAY
//      preg_match( "/define\s*\(\s*'WP_DEBUG_DISPLAY'\s*,\s*(.*)\s*\);/", $content, $matches );
//
//      // Found it!
//      if( !empty( $matches[1] ) ) {
//         $matches[1];
//
//         $pattern = "/define\s*\(\s*'WP_DEBUG_DISPLAY'\s*,\s*(.*)\s*\);/";
//
//         $replace = "define('WP_DEBUG_DISPLAY',true); // " . $matches[1];
//         $replace.= " // Changed by WP-Staging";
//
//         if( null === ($content = preg_replace( array($pattern), $replace, $content )) ) {
//            $this->log( "Preparing Data Step12: Failed to update WP_DEBUG", Logger::TYPE_ERROR );
//            return false;
//         }
//      } else {
//         $this->log( "Preparing Data Step12: WP_DEBUG not defined in wp-config.php. Skip it." );
//      }
//
//
//      if( false === @wpstg_put_contents( $path, $content ) ) {
//         $this->log( "Preparing Data Step12: Failed to update WP_DEBUG. Can't save content in wp-config.php", Logger::TYPE_ERROR );
//         return false;
//      }
//      $this->Log( "Preparing Data: Finished Step 12 successfully" );
//      return true;
//   }

    /**
     * Update Table Prefix in wp_options
     * @return bool
     */
    protected function step12() {
        $this->log( "Preparing Data Step12: Updating db prefix in {$this->prefix}options." );

        // Skip - Table does not exist
        if( false === $this->isTable( $this->prefix . 'options' ) ) {
            return true;
        }

        // Skip, prefixes are identical. No change needed
        if( $this->productionDb->prefix === $this->prefix ) {
            $this->log( "Preparing Data Step12: Skipped" );
            return true;
        }

        // Skip - Table is not selected or updated
        if( !in_array( $this->prefix . 'options', $this->tables ) ) {
            $this->log( "Preparing Data Step12: Skipping" );
            return true;
        }

        $notice = !empty( $this->db->last_error ) ? 'Last error: ' . $this->db->last_error : '';

        //$this->log( "Updating option_name in {$this->prefix}options. {$notice}" );
        // Filter the rows below. Do not update them!
        $filters = array(
            'wp_mail_smtp',
            'wp_mail_smtp_version',
            'wp_mail_smtp_debug',
        );

        $filters = apply_filters( 'wpstg_data_excl_rows', $filters );

        $where = "";
        foreach ( $filters as $filter ) {
            $where .= " AND option_name <> '" . $filter . "'";
        }

        $updateOptions = $this->db->query(
                $this->db->prepare(
                        "UPDATE IGNORE {$this->prefix}options SET option_name= replace(option_name, %s, %s) WHERE option_name LIKE %s" . $where, $this->productionDb->prefix, $this->prefix, $this->productionDb->prefix . "_%"
                )
        );

        if( false === $updateOptions ) {
            $this->log( "Preparing Data Step12: Failed to update option_name in {$this->prefix}options. Error: {$this->db->last_error}", Logger::TYPE_ERROR );
            $this->returnException( " Preparing Data Step12: Failed to update db option_names in {$this->prefix}options. Error: {$this->db->last_error}" );
            return false;
        }
        $this->Log( "Preparing Data Step12: Finished successfully" );
        return true;
    }

    /**
     * Change upload_path in wp_options (if it is defined)
     * @return bool
     */
    protected function step13() {
        $this->log( "Preparing Data Step13: Updating upload_path {$this->prefix}options." );

        // Skip - Table does not exist
        if( false === $this->isTable( $this->prefix . 'options' ) ) {
            return true;
        }

        $newUploadPath = $this->getNewUploadPath();

        if( false === $newUploadPath ) {
            $this->log( "Preparing Data Step13: Skipped" );
            return true;
        }

        // Skip - Table is not selected or updated
        if( !in_array( $this->prefix . 'options', $this->tables ) ) {
            $this->log( "Preparing Data Step13: Skipped" );
            return true;
        }

        $error = isset( $this->db->last_error ) ? 'Last error: ' . $this->db->last_error : '';

        $this->log( "Updating upload_path in {$this->prefix}options. {$error}" );

        $updateOptions = $this->db->query(
                $this->db->prepare(
                        "UPDATE {$this->prefix}options SET option_value = %s WHERE option_name = 'upload_path'", $newUploadPath
                )
        );

        if( false === $updateOptions ) {
            $this->log( "Preparing Data Step13: Failed to update upload_path in {$this->prefix}options. {$error}", Logger::TYPE_ERROR );
            return true;
        }
        $this->Log( "Preparing Data Step 13: Finished successfully" );
        return true;
    }

    /**
     * Update WP_CACHE in wp-config.php
     * @return bool
     */
    protected function step14() {
        $path = $this->options->destinationDir . "wp-config.php";

        $this->log( "Preparing Data Step14: Set WP_CACHE in wp-config.php to false" );

        if( false === ($content = file_get_contents( $path )) ) {
            $this->log( "Preparing Data Step14: Failed to update WP_CACHE in wp-config.php. Can't read wp-config.php", Logger::TYPE_ERROR );
            return false;
        }


        // Get WP_CACHE from wp-config.php
        preg_match( "/define\s*\(\s*['\"]WP_CACHE['\"]\s*,\s*(.*)\s*\);/", $content, $matches );

        if( !empty( $matches[1] ) ) {

            $pattern = "/define\s*\(\s*['\"]WP_CACHE['\"]\s*,\s*(.*)\s*\);.*/";

            $replace = "define('WP_CACHE',false); // " . $matches[1];
            //$replace.= " // Changed by WP-Staging";

            if( null === ($content = preg_replace( array($pattern), $replace, $content )) ) {
                $this->log( "Preparing Data: Failed to change WP_CACHE", Logger::TYPE_ERROR );
                return false;
            }
        } else {
            $this->log( "Preparing Data Step14: WP_CACHE not defined in wp-config.php. Skipping this step." );
        }

        if( false === @wpstg_put_contents( $path, $content ) ) {
            $this->log( "Preparing Data Step14: Failed to update WP_CACHE. Can't save contents", Logger::TYPE_ERROR );
            return false;
        }
        $this->Log( "Preparing Data Step14: Finished successfully" );
        return true;
    }

    /**
     * Update database credentials in wp-config.php
     * @return bool
     */
    protected function step15() {
        $path = $this->options->destinationDir . "wp-config.php";

        $this->log( "Preparing Data Step15: Change database credentials in wp-config.php" );

        if( false === ($content = file_get_contents( $path )) ) {
            $this->log( "Preparing Data Step15: Failed to update database credentials in wp-config.php. Can't read wp-config.php", Logger::TYPE_ERROR );
            return false;
        }


        // Get DB_NAME from wp-config.php
        preg_match( "/define\s*\(\s*['\"]DB_NAME['\"]\s*,\s*(.*)\s*\);/", $content, $matches );

        if( !empty( $matches[1] ) ) {
            $matches[1];

            $pattern = "/define\s*\(\s*['\"]DB_NAME['\"]\s*,\s*(.*)\s*\);.*/";

            $replace = "define('DB_NAME','{$this->options->databaseDatabase}'); // " . $matches[1];
            //$replace.= " // Changed by WP-Staging";

            if( null === ($content = preg_replace( array($pattern), $replace, $content )) ) {
                $this->log( "Preparing Data: Failed to change DB_NAME", Logger::TYPE_ERROR );
                return false;
            }
        } else {
            $this->log( "Preparing Data Step15: DB_NAME not defined in wp-config.php. Skipped this step." );
        }
        // Get DB_USER from wp-config.php
        preg_match( "/define\s*\(\s*['\"]DB_USER['\"]\s*,\s*(.*)\s*\);/", $content, $matches );

        if( !empty( $matches[1] ) ) {
            $matches[1];

            $pattern = "/define\s*\(\s*['\"]DB_USER['\"]\s*,\s*(.*)\s*\);.*/";

            $replace = "define('DB_USER','{$this->options->databaseUser}'); // " . $matches[1];
            //$replace.= " // Changed by WP-Staging";

            if( null === ($content = preg_replace( array($pattern), $replace, $content )) ) {
                $this->log( "Preparing Data: Failed to change DB_USER", Logger::TYPE_ERROR );
                return false;
            }
        } else {
            $this->log( "Preparing Data Step15: DB_USER not defined in wp-config.php. Skipped this step." );
        }
        // Get DB_PASSWORD from wp-config.php
        preg_match( "/define\s*\(\s*['\"]DB_PASSWORD['\"]\s*,\s*(.*)\s*\);/", $content, $matches );

        if( !empty( $matches[1] ) ) {
            $matches[1];

            $pattern = "/define\s*\(\s*['\"]DB_PASSWORD['\"]\s*,\s*(.*)\s*\);.*/";

            $replace = "define('DB_PASSWORD','{$this->options->databasePassword}'); // Changed by WP Staging";
            //$replace.= " // Changed by WP-Staging";

            if( null === ($content = preg_replace( array($pattern), $replace, $content )) ) {
                $this->log( "Preparing Data: Failed to change DB_PASSWORD", Logger::TYPE_ERROR );
                return false;
            }
        } else {
            $this->log( "Preparing Data Step15: DB_PASSWORD not defined in wp-config.php. Skipped this step." );
        }
        // Get DB_HOST from wp-config.php
        preg_match( "/define\s*\(\s*['\"]DB_HOST['\"]\s*,\s*(.*)\s*\);/", $content, $matches );

        if( !empty( $matches[1] ) ) {
            $matches[1];

            $pattern = "/define\s*\(\s*['\"]DB_HOST['\"]\s*,\s*(.*)\s*\);.*/";

            $replace = "define('DB_HOST','{$this->options->databaseServer}'); // " . $matches[1];
            //$replace.= " // Changed by WP-Staging";

            if( null === ($content = preg_replace( array($pattern), $replace, $content )) ) {
                $this->log( "Preparing Data: Failed to change DB_HOST", Logger::TYPE_ERROR );
                return false;
            }
        } else {
            $this->log( "Preparing Data Step15: DB_HOST not defined in wp-config.php. Skipped this step." );
        }


        if( false === @wpstg_put_contents( $path, $content ) ) {
            $this->log( "Preparing Data Step15: Failed to update database credentials in wp-config.php. Can't save contents", Logger::TYPE_ERROR );
            return false;
        }
        $this->Log( "Preparing Data: Finished Step 15 successfully" );
        return true;
    }

    /**
     * Remove UPLOADS constant in wp-config.php to reset default image folder
     * @return bool
     */
    protected function step16() {
        $path = $this->options->destinationDir . "wp-config.php";

        $this->log( "Preparing Data Step16: Remove UPLOADS in wp-config.php" );

        if( false === ($content = file_get_contents( $path )) ) {
            $this->log( "Preparing Data Step16: Failed to get UPLOADS in wp-config.php. Can't read wp-config.php", Logger::TYPE_ERROR );
            return false;
        }


        // Get UPLOADS from wp-config.php
        preg_match( "/define\s*\(\s*['\"]UPLOADS['\"]\s*,\s*(.*)\s*\);/", $content, $matches );

        if( !empty( $matches[0] ) ) {

            $pattern = "/define\s*\(\s*'UPLOADS'\s*,\s*(.*)\s*\);/";

            $replace = "";

            if( null === ($content = preg_replace( array($pattern), $replace, $content )) ) {
                $this->log( "Preparing Data: Failed to change UPLOADS", Logger::TYPE_ERROR );
                return false;
            }
        } else {
            $this->log( "Preparing Data Step16: UPLOADS not defined in wp-config.php. Skipping this step." );
        }

        if( false === @wpstg_put_contents( $path, $content ) ) {
            $this->log( "Preparing Data Step16: Failed to update UPLOADS. Can't save contents", Logger::TYPE_ERROR );
            return false;
        }
        $this->Log( "Preparing Data Step 16: Finished successfully" );
        return true;
    }

    /**
     * Add UPLOADS constant in wp-config.php or change it to correct destination.
     * This is important when a custom uploads folder is used
     * @return bool
     */
    protected function step17() {
        $path    = $this->options->destinationDir . "wp-config.php";
        $this->log( "Preparing Data Step17: Update UPLOADS constant in wp-config.php" );
        if( false === ($content = file_get_contents( $path )) ) {
            $this->log( "Preparing Data Step17: Failed to get UPLOADS in wp-config.php. Can't read wp-config.php", Logger::TYPE_ERROR );
            return false;
        }
        // Get UPLOADS from wp-config.php if there is already one
        preg_match( "/define\s*\(\s*['\"]UPLOADS['\"]\s*,\s*(.*)\s*\);/", $content, $matches );
        // TODO RPoC; DRY
        $uploadFolder = wpstg_get_rel_upload_dir();
        $uploadFolder = ltrim($uploadFolder, '/');
        $uploadFolder = rtrim($uploadFolder, '/');
        if( !empty( $matches[0] ) ) {
            $pattern = "/define\s*\(\s*'UPLOADS'\s*,\s*(.*)\s*\);/";

            $replace = "define('UPLOADS', '" . $uploadFolder . "');";
            if( null === ($content = preg_replace( array($pattern), $replace, $content )) ) {
                $this->log( "Preparing Data Step17: Failed to change UPLOADS", Logger::TYPE_ERROR );
                return false;
            }
        } else {
            $this->log( "Preparing Data Step17: UPLOADS not defined in wp-config.php. Creating new entry." );
            // Find line with ABSPATH and add UPLOADS constant above
            preg_match( "/if\s*\(\s*\s*!\s*defined\s*\(\s*['\"]ABSPATH['\"]\s*(.*)\s*\)\s*\)/", $content, $matches );
            if( !empty( $matches[0] ) ) {
                $matches[0];
                $pattern = "/if\s*\(\s*\s*!\s*defined\s*\(\s*['\"]ABSPATH['\"]\s*(.*)\s*\)\s*\)/";
                $replace = "define('UPLOADS', '" . $uploadFolder . "'); \n" .
                        "if ( ! defined( 'ABSPATH' ) )";
                if( null === ($content = preg_replace( array($pattern), $replace, $content )) ) {
                    $this->log( "Preparing Data Step 17: Failed to change UPLOADS", Logger::TYPE_ERROR );
                    return false;
                }
            } else {
                $this->log( "Preparing Data Step 17: Can not add UPLOAD constant to wp-config.php. Can not find free position to add it.", Logger::TYPE_ERROR );
            }
        }
        if( false === @wpstg_put_contents( $path, $content ) ) {
            $this->log( "Preparing Data Step17: Failed to update UPLOADS. Can't save contents", Logger::TYPE_ERROR );
            return false;
        }
        $this->Log( "Preparing Data Step17: Finished successfully" );
        return true;
    }

    /**
     * Save hostname of parent production site in option_name wpstg_connection
     * @return boolean
     */
    protected function step18() {

        $table = $this->prefix . 'options';

        $siteurl = get_site_url();

        $connection = json_encode( array('prodHostname' => $siteurl) );

        $data = array(
            'option_name'  => 'wpstg_connection',
            'option_value' => $connection
        );

        $format = array('%s', '%s');

        $result = $this->db->replace( $table, $data, $format );

        if( false === $result ) {
            $this->Log( "Preparing Data Step18: Could not save {$siteurl} in {$table}", Logger::TYPE_ERROR );
        }
        return true;
    }

    /**
     * Add option_name wpstg_execute and set it to true
     * This option is used to determine if the staging website has not been loaded initiall for executing certain custom actions from \WPStaging\initActions()
     * @return boolean
     */
    protected function step19() {

        $table = $this->prefix . 'options';

        // Skip - Table does not exist
        if( false === $this->isTable( $table ) ) {
            return true;
        }

        $result = $this->db->query(
                $this->db->prepare(
                        "INSERT INTO {$this->prefix}options (option_name,option_value) VALUES ('wpstg_execute',%s) ON DUPLICATE KEY UPDATE option_value = %s", "true", "true"
                )
        );

        if( false === $result ) {
            $this->Log( "Preparing Data Step19: Could not save wpstg_execute in {$table}", Logger::TYPE_ERROR );
        }
        return true;
    }

    /**
     * Preserve data and prevents data in wp_options from beeing cloned to staging site
     * @return bool
     */
    protected function step20() {
        $this->log( "Preparing Data Step20: Preserve Data in " . $this->prefix . "options" );

        // Skip - Table does not exist
        if( false === $this->isTable( $this->prefix . 'options' ) ) {
            return true;
        }

        // Skip - Table is not selected or updated
        if( !in_array( $this->prefix . 'options', $this->tables ) ) {
            $this->log( "Preparing Data Step20: Skipped" );
            return true;
        }

        $sql = '';

        $preserved_option_names = array('wpstg_existing_clones_beta');

        $preserved_option_names    = apply_filters( 'wpstg_preserved_options_cloning', $preserved_option_names );
        $preserved_options_escaped = esc_sql( $preserved_option_names );

        $preserved_options_data = array();

        // Get preserved data in wp_options tables
        $table                                             = $this->db->prefix . 'options';
        $preserved_options_data[$this->prefix . 'options'] = $this->db->get_results(
                sprintf(
                        "SELECT * FROM `{$table}` WHERE `option_name` IN ('%s')", implode( "','", $preserved_options_escaped )
                ), ARRAY_A
        );

        // Create preserved data queries for options tables
        foreach ( $preserved_options_data as $key => $value ) {
            if( false === empty( $value ) ) {
                foreach ( $value as $option ) {
                    $sql .= $this->db->prepare(
                            "DELETE FROM `{$key}` WHERE `option_name` = %s;\n", $option['option_name']
                    );

                    $sql .= $this->db->prepare(
                            "INSERT INTO `{$key}` ( `option_id`, `option_name`, `option_value`, `autoload` ) VALUES ( NULL , %s, %s, %s );\n", $option['option_name'], $option['option_value'], $option['autoload']
                    );
                }
            }
        }

        $this->debugLog( "Preparing Data Step20: Preserve values " . json_encode( $preserved_options_data ), Logger::TYPE_INFO );

        $this->executeSql( $sql );

        $this->log( "Preparing Data Step20: Successful!" );
        return true;
    }

    /**
     * Execute a batch of sql queries
     * @param string $sqlbatch
     */
    private function executeSql( $sqlbatch ) {
        $queries = array_filter( explode( ";\n", $sqlbatch ) );

        foreach ( $queries as $query ) {
            if( false === $this->db->query( $query ) ) {
                $this->log( "Data Crunching Warning:  Can not execute query {$query}", Logger::TYPE_WARNING );
            }
        }
        return true;
    }

    /**
     * Get upload path
     * @return boolean|string
     */
    protected function getNewUploadPath() {
        $uploadPath = get_option( 'upload_path' );

        if( !$uploadPath ) {
            return false;
        }

        $customSlug = str_replace( \WPStaging\WPStaging::getWPpath(), '', $uploadPath );

        $newUploadPath = $this->options->destinationDir . $customSlug;

        return $newUploadPath;
    }

    /**
     * Return URL to staging site
     * @return string
     */
    protected function getStagingSiteUrl() {
        if( !empty( $this->options->cloneHostname ) ) {
            return $this->options->cloneHostname;
        }
        if( $this->isSubDir() ) {
            return trailingslashit( $this->homeUrl ) . trailingslashit( $this->getSubDir() ) . $this->options->cloneDirectoryName;
        }

        return trailingslashit( $this->homeUrl ) . $this->options->cloneDirectoryName;
    }

    /**
     * Check if WP is installed in subdir
     * @return boolean
     */
    protected function isSubDir() {
        // Compare names without scheme to bypass cases where siteurl and home have different schemes http / https
        // This is happening much more often than you would expect
        $siteurl = preg_replace( '#^https?://#', '', rtrim( get_option( 'siteurl' ), '/' ) );
        $home    = preg_replace( '#^https?://#', '', rtrim( get_option( 'home' ), '/' ) );

        if( $home !== $siteurl ) {
            return true;
        }
        return false;
    }

    /**
     * Get the install sub directory if WP is installed in sub directory
     * @return string
     */
    protected function getSubDir() {
        $home    = get_option( 'home' );
        $siteurl = get_option( 'siteurl' );

        if( empty( $home ) || empty( $siteurl ) ) {
            return '';
        }

        $dir = str_replace( $home, '', $siteurl );
        return str_replace( '/', '', $dir );
    }

}