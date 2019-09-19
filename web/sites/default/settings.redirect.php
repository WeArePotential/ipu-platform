<?php
/**
 * @file
 * Drupal site-specific configuration file.
 *
 * IMPORTANT NOTE:
 * This file may have been set to read-only by the Drupal installation program.
 * If you make changes to this file, be sure to protect it again after making
 * your modifications. Failure to remove write permissions to this file is a
 * security risk.
 *
 * The configuration file to be loaded is based upon the rules below. However
 * if the multisite aliasing file named sites/sites.php is present, it will be
 * loaded, and the aliases in the array $sites will override the default
 * directory rules below. See sites/example.sites.php for more information about
 * aliases.
 *
 * The configuration directory will be discovered by stripping the website's
 * hostname from left to right and pathname from right to left. The first
 * configuration file found will be used and any others will be ignored. If no
 * other configuration file is found then the default configuration file at
 * 'sites/default' will be used.
 *
 * For example, for a fictitious site installed at
 * http://www.drupal.org:8080/mysite/test/, the 'settings.php' file is searched
 * for in the following directories:
 *
 * - sites/8080.www.drupal.org.mysite.test
 * - sites/www.drupal.org.mysite.test
 * - sites/drupal.org.mysite.test
 * - sites/org.mysite.test
 *
 * - sites/8080.www.drupal.org.mysite
 * - sites/www.drupal.org.mysite
 * - sites/drupal.org.mysite
 * - sites/org.mysite
 *
 * - sites/8080.www.drupal.org
 * - sites/www.drupal.org
 * - sites/drupal.org
 * - sites/org
 *
 * - sites/default
 *
 * Note that if you are installing on a non-standard port number, prefix the
 * hostname with that number. For example,
 * http://www.drupal.org:8080/mysite/test/ could be loaded from
 * sites/8080.www.drupal.org.mysite.test/.
 *
 * @see example.sites.php
 * @see conf_path()
 */

/**
 * Database settings:
 *
 * The $databases array specifies the database connection or
 * connections that Drupal may use.  Drupal is able to connect
 * to multiple databases, including multiple types of databases,
 * during the same request.
 *
 * Each database connection is specified as an array of settings,
 * similar to the following:
 * @code
 * array(
 *   'driver' => 'mysql',
 *   'database' => 'databasename',
 *   'username' => 'username',
 *   'password' => 'password',
 *   'host' => 'localhost',
 *   'port' => 3306,
 *   'prefix' => 'myprefix_',
 *   'collation' => 'utf8_general_ci',
 * );
 * @endcode
 *
 * The "driver" property indicates what Drupal database driver the
 * connection should use.  This is usually the same as the name of the
 * database type, such as mysql or sqlite, but not always.  The other
 * properties will vary depending on the driver.  For SQLite, you must
 * specify a database file name in a directory that is writable by the
 * webserver.  For most other drivers, you must specify a
 * username, password, host, and database name.
 *
 * Transaction support is enabled by default for all drivers that support it,
 * including MySQL. To explicitly disable it, set the 'transactions' key to
 * FALSE.
 * Note that some configurations of MySQL, such as the MyISAM engine, don't
 * support it and will proceed silently even if enabled. If you experience
 * transaction related crashes with such configuration, set the 'transactions'
 * key to FALSE.
 *
 * For each database, you may optionally specify multiple "target" databases.
 * A target database allows Drupal to try to send certain queries to a
 * different database if it can but fall back to the default connection if not.
 * That is useful for master/slave replication, as Drupal may try to connect
 * to a slave server when appropriate and if one is not available will simply
 * fall back to the single master server.
 *
 * The general format for the $databases array is as follows:
 * @code
 * $databases['default']['default'] = $info_array;
 * $databases['default']['slave'][] = $info_array;
 * $databases['default']['slave'][] = $info_array;
 * $databases['extra']['default'] = $info_array;
 * @endcode
 *
 * In the above example, $info_array is an array of settings described above.
 * The first line sets a "default" database that has one master database
 * (the second level default).  The second and third lines create an array
 * of potential slave databases.  Drupal will select one at random for a given
 * request as needed.  The fourth line creates a new database with a name of
 * "extra".
 *
 * For a single database configuration, the following is sufficient:
 * @code
 * $databases['default']['default'] = array(
 *   'driver' => 'mysql',
 *   'database' => 'databasename',
 *   'username' => 'username',
 *   'password' => 'password',
 *   'host' => 'localhost',
 *   'prefix' => 'main_',
 *   'collation' => 'utf8_general_ci',
 * );
 * @endcode
 *
 * You can optionally set prefixes for some or all database table names
 * by using the 'prefix' setting. If a prefix is specified, the table
 * name will be prepended with its value. Be sure to use valid database
 * characters only, usually alphanumeric and underscore. If no prefixes
 * are desired, leave it as an empty string ''.
 *
 * To have all database names prefixed, set 'prefix' as a string:
 * @code
 *   'prefix' => 'main_',
 * @endcode
 * To provide prefixes for specific tables, set 'prefix' as an array.
 * The array's keys are the table names and the values are the prefixes.
 * The 'default' element is mandatory and holds the prefix for any tables
 * not specified elsewhere in the array. Example:
 * @code
 *   'prefix' => array(
 *     'default'   => 'main_',
 *     'users'     => 'shared_',
 *     'sessions'  => 'shared_',
 *     'role'      => 'shared_',
 *     'authmap'   => 'shared_',
 *   ),
 * @endcode
 * You can also use a reference to a schema/database as a prefix. This may be
 * useful if your Drupal installation exists in a schema that is not the default
 * or you want to access several databases from the same code base at the same
 * time.
 * Example:
 * @code
 *   'prefix' => array(
 *     'default'   => 'main.',
 *     'users'     => 'shared.',
 *     'sessions'  => 'shared.',
 *     'role'      => 'shared.',
 *     'authmap'   => 'shared.',
 *   );
 * @endcode
 * NOTE: MySQL and SQLite's definition of a schema is a database.
 *
 * Advanced users can add or override initial commands to execute when
 * connecting to the database server, as well as PDO connection settings. For
 * example, to enable MySQL SELECT queries to exceed the max_join_size system
 * variable, and to reduce the database connection timeout to 5 seconds:
 *
 * @code
 * $databases['default']['default'] = array(
 *   'init_commands' => array(
 *     'big_selects' => 'SET SQL_BIG_SELECTS=1',
 *   ),
 *   'pdo' => array(
 *     PDO::ATTR_TIMEOUT => 5,
 *   ),
 * );
 * @endcode
 *
 * WARNING: These defaults are designed for database portability. Changing them
 * may cause unexpected behavior, including potential data loss.
 *
 * @see DatabaseConnection_mysql::__construct
 * @see DatabaseConnection_pgsql::__construct
 * @see DatabaseConnection_sqlite::__construct
 *
 * Database configuration format:
 * @code
 *   $databases['default']['default'] = array(
 *     'driver' => 'mysql',
 *     'database' => 'databasename',
 *     'username' => 'username',
 *     'password' => 'password',
 *     'host' => 'localhost',
 *     'prefix' => '',
 *   );
 *   $databases['default']['default'] = array(
 *     'driver' => 'pgsql',
 *     'database' => 'databasename',
 *     'username' => 'username',
 *     'password' => 'password',
 *     'host' => 'localhost',
 *     'prefix' => '',
 *   );
 *   $databases['default']['default'] = array(
 *     'driver' => 'sqlite',
 *     'database' => '/path/to/databasefilename',
 *   );
 * @endcode
 */
$databases = array (
  'default' =>
    array (
      'default' =>
        array (
          'database' => 'ipu',
          'username' => 'root',
          'password' => 'qburst',
          'host' => 'localhost',
          'port' => '',
          'driver' => 'mysql',
          'prefix' => '',
        ),
    ),
);


/**
 * Access control for update.php script.
 *
 * If you are updating your Drupal installation using the update.php script but
 * are not logged in using either an account with the "Administer software
 * updates" permission or the site maintenance account (the account that was
 * created during installation), you will need to modify the access check
 * statement below. Change the FALSE to a TRUE to disable the access check.
 * After finishing the upgrade, be sure to open this file again and change the
 * TRUE back to a FALSE!
 */
$update_free_access = FALSE;

/**
 * Salt for one-time login links and cancel links, form tokens, etc.
 *
 * This variable will be set to a random value by the installer. All one-time
 * login links will be invalidated if the value is changed. Note that if your
 * site is deployed on a cluster of web servers, you must ensure that this
 * variable has the same value on each server. If this variable is empty, a hash
 * of the serialized database credentials will be used as a fallback salt.
 *
 * For enhanced security, you may set this variable to a value using the
 * contents of a file outside your docroot that is never saved together
 * with any backups of your Drupal files and database.
 *
 * Example:
 *   $drupal_hash_salt = file_get_contents('/home/example/salt.txt');
 *
 */
$drupal_hash_salt = 'Kz0yKfBcEms6nDV90bZXinjwDUhAE2MHbMBUZ3BtiPY';

/**
 * Base URL (optional).
 *
 * If Drupal is generating incorrect URLs on your site, which could
 * be in HTML headers (links to CSS and JS files) or visible links on pages
 * (such as in menus), uncomment the Base URL statement below (remove the
 * leading hash sign) and fill in the absolute URL to your Drupal installation.
 *
 * You might also want to force users to use a given domain.
 * See the .htaccess file for more information.
 *
 * Examples:
 *   $base_url = 'http://www.example.com';
 *   $base_url = 'http://www.example.com:8888';
 *   $base_url = 'http://www.example.com/drupal';
 *   $base_url = 'https://www.example.com:8888/drupal';
 *
 * It is not allowed to have a trailing slash; Drupal will add it
 * for you.
 */
# $base_url = 'http://www.example.com';  // NO trailing slash!

/**
 * PHP settings:
 *
 * To see what PHP settings are possible, including whether they can be set at
 * runtime (by using ini_set()), read the PHP documentation:
 * http://www.php.net/manual/en/ini.list.php
 * See drupal_environment_initialize() in includes/bootstrap.inc for required
 * runtime settings and the .htaccess file for non-runtime settings. Settings
 * defined there should not be duplicated here so as to avoid conflict issues.
 */

/**
 * Some distributions of Linux (most notably Debian) ship their PHP
 * installations with garbage collection (gc) disabled. Since Drupal depends on
 * PHP's garbage collection for clearing sessions, ensure that garbage
 * collection occurs by using the most common settings.
 */
ini_set('session.gc_probability', 1);
ini_set('session.gc_divisor', 100);

/**
 * Set session lifetime (in seconds), i.e. the time from the user's last visit
 * to the active session may be deleted by the session garbage collector. When
 * a session is deleted, authenticated users are logged out, and the contents
 * of the user's $_SESSION variable is discarded.
 */
ini_set('session.gc_maxlifetime', 200000);

/**
 * Set session cookie lifetime (in seconds), i.e. the time from the session is
 * created to the cookie expires, i.e. when the browser is expected to discard
 * the cookie. The value 0 means "until the browser is closed".
 */
ini_set('session.cookie_lifetime', 2000000);

/**
 * If you encounter a situation where users post a large amount of text, and
 * the result is stripped out upon viewing but can still be edited, Drupal's
 * output filter may not have sufficient memory to process it.  If you
 * experience this issue, you may wish to uncomment the following two lines
 * and increase the limits of these variables.  For more information, see
 * http://php.net/manual/en/pcre.configuration.php.
 */
# ini_set('pcre.backtrack_limit', 200000);
# ini_set('pcre.recursion_limit', 200000);

/**
 * Drupal automatically generates a unique session cookie name for each site
 * based on its full domain name. If you have multiple domains pointing at the
 * same Drupal site, you can either redirect them all to a single domain (see
 * comment in .htaccess), or uncomment the line below and specify their shared
 * base domain. Doing so assures that users remain logged in as they cross
 * between your various domains. Make sure to always start the $cookie_domain
 * with a leading dot, as per RFC 2109.
 */
# $cookie_domain = '.example.com';

/**
 * Variable overrides:
 *
 * To override specific entries in the 'variable' table for this site,
 * set them here. You usually don't need to use this feature. This is
 * useful in a configuration file for a vhost or directory, rather than
 * the default settings.php. Any configuration setting from the 'variable'
 * table can be given a new value. Note that any values you provide in
 * these variable overrides will not be modifiable from the Drupal
 * administration interface.
 *
 * The following overrides are examples:
 * - site_name: Defines the site's name.
 * - theme_default: Defines the default theme for this site.
 * - anonymous: Defines the human-readable name of anonymous users.
 * Remove the leading hash signs to enable.
 */
# $conf['site_name'] = 'My Drupal site';
# $conf['theme_default'] = 'garland';
# $conf['anonymous'] = 'Visitor';

/**
 * A custom theme can be set for the offline page. This applies when the site
 * is explicitly set to maintenance mode through the administration page or when
 * the database is inactive due to an error. It can be set through the
 * 'maintenance_theme' key. The template file should also be copied into the
 * theme. It is located inside 'modules/system/maintenance-page.tpl.php'.
 * Note: This setting does not apply to installation and update pages.
 */
# $conf['maintenance_theme'] = 'bartik';

/**
 * Reverse Proxy Configuration:
 *
 * Reverse proxy servers are often used to enhance the performance
 * of heavily visited sites and may also provide other site caching,
 * security, or encryption benefits. In an environment where Drupal
 * is behind a reverse proxy, the real IP address of the client should
 * be determined such that the correct client IP address is available
 * to Drupal's logging, statistics, and access management systems. In
 * the most simple scenario, the proxy server will add an
 * X-Forwarded-For header to the request that contains the client IP
 * address. However, HTTP headers are vulnerable to spoofing, where a
 * malicious client could bypass restrictions by setting the
 * X-Forwarded-For header directly. Therefore, Drupal's proxy
 * configuration requires the IP addresses of all remote proxies to be
 * specified in $conf['reverse_proxy_addresses'] to work correctly.
 *
 * Enable this setting to get Drupal to determine the client IP from
 * the X-Forwarded-For header (or $conf['reverse_proxy_header'] if set).
 * If you are unsure about this setting, do not have a reverse proxy,
 * or Drupal operates in a shared hosting environment, this setting
 * should remain commented out.
 *
 * In order for this setting to be used you must specify every possible
 * reverse proxy IP address in $conf['reverse_proxy_addresses'].
 * If a complete list of reverse proxies is not available in your
 * environment (for example, if you use a CDN) you may set the
 * $_SERVER['REMOTE_ADDR'] variable directly in settings.php.
 * Be aware, however, that it is likely that this would allow IP
 * address spoofing unless more advanced precautions are taken.
 */
# $conf['reverse_proxy'] = TRUE;

/**
 * Specify every reverse proxy IP address in your environment.
 * This setting is required if $conf['reverse_proxy'] is TRUE.
 */
# $conf['reverse_proxy_addresses'] = array('a.b.c.d', ...);

/**
 * Set this value if your proxy server sends the client IP in a header
 * other than X-Forwarded-For.
 */
# $conf['reverse_proxy_header'] = 'HTTP_X_CLUSTER_CLIENT_IP';

/**
 * Page caching:
 *
 * By default, Drupal sends a "Vary: Cookie" HTTP header for anonymous page
 * views. This tells a HTTP proxy that it may return a page from its local
 * cache without contacting the web server, if the user sends the same Cookie
 * header as the user who originally requested the cached page. Without "Vary:
 * Cookie", authenticated users would also be served the anonymous page from
 * the cache. If the site has mostly anonymous users except a few known
 * editors/administrators, the Vary header can be omitted. This allows for
 * better caching in HTTP proxies (including reverse proxies), i.e. even if
 * clients send different cookies, they still get content served from the cache.
 * However, authenticated users should access the site directly (i.e. not use an
 * HTTP proxy, and bypass the reverse proxy if one is used) in order to avoid
 * getting cached pages from the proxy.
 */
# $conf['omit_vary_cookie'] = TRUE;

/**
 * CSS/JS aggregated file gzip compression:
 *
 * By default, when CSS or JS aggregation and clean URLs are enabled Drupal will
 * store a gzip compressed (.gz) copy of the aggregated files. If this file is
 * available then rewrite rules in the default .htaccess file will serve these
 * files to browsers that accept gzip encoded content. This allows pages to load
 * faster for these users and has minimal impact on server load. If you are
 * using a webserver other than Apache httpd, or a caching reverse proxy that is
 * configured to cache and compress these files itself you may want to uncomment
 * one or both of the below lines, which will prevent gzip files being stored.
 */
# $conf['css_gzip_compression'] = FALSE;
# $conf['js_gzip_compression'] = FALSE;

/**
 * Block caching:
 *
 * Block caching may not be compatible with node access modules depending on
 * how the original block cache policy is defined by the module that provides
 * the block. By default, Drupal therefore disables block caching when one or
 * more modules implement hook_node_grants(). If you consider block caching to
 * be safe on your site and want to bypass this restriction, uncomment the line
 * below.
 */
# $conf['block_cache_bypass_node_grants'] = TRUE;

/**
 * String overrides:
 *
 * To override specific strings on your site with or without enabling the Locale
 * module, add an entry to this list. This functionality allows you to change
 * a small number of your site's default English language interface strings.
 *
 * Remove the leading hash signs to enable.
 */
# $conf['locale_custom_strings_en'][''] = array(
#   'forum'      => 'Discussion board',
#   '@count min' => '@count minutes',
# );

/**
 *
 * IP blocking:
 *
 * To bypass database queries for denied IP addresses, use this setting.
 * Drupal queries the {blocked_ips} table by default on every page request
 * for both authenticated and anonymous users. This allows the system to
 * block IP addresses from within the administrative interface and before any
 * modules are loaded. However on high traffic websites you may want to avoid
 * this query, allowing you to bypass database access altogether for anonymous
 * users under certain caching configurations.
 *
 * If using this setting, you will need to add back any IP addresses which
 * you may have blocked via the administrative interface. Each element of this
 * array represents a blocked IP address. Uncommenting the array and leaving it
 * empty will have the effect of disabling IP blocking on your site.
 *
 * Remove the leading hash signs to enable.
 */
# $conf['blocked_ips'] = array(
#   'a.b.c.d',
# );

/**
 * Fast 404 pages:
 *
 * Drupal can generate fully themed 404 pages. However, some of these responses
 * are for images or other resource files that are not displayed to the user.
 * This can waste bandwidth, and also generate server load.
 *
 * The options below return a simple, fast 404 page for URLs matching a
 * specific pattern:
 * - 404_fast_paths_exclude: A regular expression to match paths to exclude,
 *   such as images generated by image styles, or dynamically-resized images.
 *   The default pattern provided below also excludes the private file system.
 *   If you need to add more paths, you can add '|path' to the expression.
 * - 404_fast_paths: A regular expression to match paths that should return a
 *   simple 404 page, rather than the fully themed 404 page. If you don't have
 *   any aliases ending in htm or html you can add '|s?html?' to the expression.
 * - 404_fast_html: The html to return for simple 404 pages.
 *
 * Add leading hash signs if you would like to disable this functionality.
 */
$conf['404_fast_paths_exclude'] = '/\/(?:styles)|(?:system\/files)\//';
$conf['404_fast_paths'] = '/\.(?:txt|png|gif|jpe?g|css|js|ico|swf|flv|cgi|bat|pl|dll|exe|asp)$/i';
$conf['404_fast_html'] = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML+RDFa 1.0//EN" "http://www.w3.org/MarkUp/DTD/xhtml-rdfa-1.dtd"><html xmlns="http://www.w3.org/1999/xhtml"><head><title>404 Not Found</title></head><body><h1>Not Found</h1><p>The requested URL "@path" was not found on this server.</p></body></html>';

/**
 * By default the page request process will return a fast 404 page for missing
 * files if they match the regular expression set in '404_fast_paths' and not
 * '404_fast_paths_exclude' above. 404 errors will simultaneously be logged in
 * the Drupal system log.
 *
 * You can choose to return a fast 404 page earlier for missing pages (as soon
 * as settings.php is loaded) by uncommenting the line below. This speeds up
 * server response time when loading 404 error pages and prevents the 404 error
 * from being logged in the Drupal system log. In order to prevent valid pages
 * such as image styles and other generated content that may match the
 * '404_fast_paths' regular expression from returning 404 errors, it is
 * necessary to add them to the '404_fast_paths_exclude' regular expression
 * above. Make sure that you understand the effects of this feature before
 * uncommenting the line below.
 */
# drupal_fast_404();

/**
 * External access proxy settings:
 *
 * If your site must access the Internet via a web proxy then you can enter
 * the proxy settings here. Currently only basic authentication is supported
 * by using the username and password variables. The proxy_user_agent variable
 * can be set to NULL for proxies that require no User-Agent header or to a
 * non-empty string for proxies that limit requests to a specific agent. The
 * proxy_exceptions variable is an array of host names to be accessed directly,
 * not via proxy.
 */
# $conf['proxy_server'] = '';
# $conf['proxy_port'] = 8080;
# $conf['proxy_username'] = '';
# $conf['proxy_password'] = '';
# $conf['proxy_user_agent'] = '';
# $conf['proxy_exceptions'] = array('127.0.0.1', 'localhost');

/**
 * Authorized file system operations:
 *
 * The Update manager module included with Drupal provides a mechanism for
 * site administrators to securely install missing updates for the site
 * directly through the web user interface. On securely-configured servers,
 * the Update manager will require the administrator to provide SSH or FTP
 * credentials before allowing the installation to proceed; this allows the
 * site to update the new files as the user who owns all the Drupal files,
 * instead of as the user the webserver is running as. On servers where the
 * webserver user is itself the owner of the Drupal files, the administrator
 * will not be prompted for SSH or FTP credentials (note that these server
 * setups are common on shared hosting, but are inherently insecure).
 *
 * Some sites might wish to disable the above functionality, and only update
 * the code directly via SSH or FTP themselves. This setting completely
 * disables all functionality related to these authorized file operations.
 *
 * @see http://drupal.org/node/244924
 *
 * Remove the leading hash signs to disable.
 */
# $conf['allow_authorize_operations'] = FALSE;

/**
 * Smart start:
 *
 * If you would prefer to be redirected to the installation system when a
 * valid settings.php file is present but no tables are installed, remove
 * the leading hash sign below.
 */
# $conf['pressflow_smart_start'] = TRUE;

/**
 * Theme debugging:
 *
 * When debugging is enabled:
 * - The markup of each template is surrounded by HTML comments that contain
 *   theming information, such as template file name suggestions.
 * - Note that this debugging markup will cause automated tests that directly
 *   check rendered HTML to fail.
 *
 * For more information about debugging theme templates, see
 * https://www.drupal.org/node/223440#theme-debug.
 *
 * Not recommended in production environments.
 *
 * Remove the leading hash sign to enable.
 */
$conf['theme_debug'] = TRUE;

// Require HTTPS.
// Check if Drupal is running via command line
if (isset($_SERVER['PANTHEON_ENVIRONMENT']) &&
  ($_SERVER['HTTPS'] === 'OFF') &&
  (php_sapi_name() != "cli")) {
  if (!isset($_SERVER['HTTP_X_SSL']) ||
    (isset($_SERVER['HTTP_X_SSL']) && $_SERVER['HTTP_X_SSL'] != 'ON')) {
    header('HTTP/1.0 301 Moved Permanently');
    header('Location: https://'. $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
    exit();
  }
}
//Disable Notices display
//ini_set('error_reporting', 'E_ALL ^ E_NOTICE');

//Pantheon Redis conf
if (defined('PANTHEON_ENVIRONMENT')) {
  // Use Redis for caching.
  $conf['redis_client_interface'] = 'PhpRedis';
  $conf['cache_backends'][] = 'sites/all/modules/contrib/redis/redis.autoload.inc';
  //or if you have a contrib subfolder for modules use
  // $conf['cache_backends'][] = 'sites/all/modules/contrib/redis/redis.autoload.inc';
  $conf['cache_default_class'] = 'Redis_Cache';
  $conf['cache_prefix'] = array('default' => 'pantheon-redis');
  // Do not use Redis for cache_form (no performance difference).
  $conf['cache_class_cache_form'] = 'DrupalDatabaseCache';
  // Use Redis for Drupal locks (semaphore).
  $conf['lock_inc'] = 'sites/all/modules/contrib/redis/redis.lock.inc';
  //or if you have a contrib subfolder for modules use
  // $conf['lock_inc'] = 'sites/all/modules/contrib/redis/redis.lock.inc';
}


// Optional Pantheon Redis settings.
// Option A: Higher performance for smaller page counts.
if (defined('PANTHEON_ENVIRONMENT')) {
  // High performance - no hook_boot(), no hook_exit(), ignores Drupal IP blacklists.
  $conf['page_cache_without_database'] = TRUE;
  $conf['page_cache_invoke_hooks'] = FALSE;
  // Explicitly set page_cache_maximum_age as database won't be available.
  $conf['page_cache_maximum_age'] = 900;
}

// Optional Pantheon Redis settings.
// Option B: Higher performance for larger page counts.
//if (defined('PANTHEON_ENVIRONMENT')) {
//// Use the database for cached HTML.
//    $conf['cache_class_cache_page'] = 'DrupalDatabaseCache';
//}

if (isset($_ENV['PANTHEON_ENVIRONMENT'])) {
  // set schema for apachesolr OR set schema for search_api_solr (uncomment the line you need)
  // $conf['pantheon_apachesolr_schema'] = 'sites/all/modules/apachesolr/solr-conf/solr-3.x/schema.xml';
  // $conf['pantheon_apachesolr_schema'] = 'sites/all/modules/search_api_solr/solr-conf/solr-3.x/schema.xml';
  // or if you have a contrib folder for modules use
  // $conf['pantheon_apachesolr_schema'] = 'sites/all/modules/contrib/apachesolr/solr-conf/solr-3.x/schema.xml';
  $conf['pantheon_apachesolr_schema'] = 'sites/all/modules/contrib/search_api_solr/solr-conf/3.x/schema.xml';
}

// 301 Redirect for references to documents to archive.ipu.org
$pattern = "/^(\/fr)?(\/(cnl|conf|splz|news|parline|wmn|dem|hr|press|strct|Un|finance|cntr|eb|idd)-(e|f)\/(.+))$/i";
$matches = array();
$pdf_pattern = "/^(\/fr)?(\/pdf\/(.+)\.pdf)$/i";
$pdf_matches = array();


if(strtolower($_SERVER['HTTP_HOST']) === 'beta.ipu.org') {
  $permanent_redirect = true;
  $redirect = "https://www.ipu.org{$_SERVER['REQUEST_URI']}";
} else {
  $uri = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : NULL;
  $redirect = false;
  $archive_website = 'http://archive.ipu.org';

  if($uri) {
    $permanent_redirect = false;
    if(preg_match($pattern, $uri, $matches)) {
      $permanent_redirect = true;
      $redirect = "{$archive_website}{$matches[2]}"; // Fix to remove the /fr prefix as archive do not have it (Use regex capture group 1)
    } else if (preg_match($pdf_pattern, $uri, $pdf_matches)) {
      $permanent_redirect = true;
      $redirect = "{$archive_website}{$pdf_matches[2]}";
    } else if(in_array($uri, array("/parline/",  "/parline"))) {
      $permanent_redirect = true;
      $redirect = "{$archive_website}/parline/";
    } else if (in_array($uri, array("/parline-e/",  "/parline-e"))) {
      $permanent_redirect = true;
      $redirect = "{$archive_website}/parline-e/parlinesearch.asp";
    } else if (in_array($uri, array("/idd/",  "/idd"))) {
      $permanent_redirect = true;
      $redirect = "{$archive_website}/idd/";
    } else if (in_array($uri, array("/gpr/",  "/gpr"))) {
      $permanent_redirect = true;
      $redirect = "{$archive_website}/gpr/";
    } else if ($uri == '/home') {
      $redirect = "/";
    } else if ($uri == '/ipu_admin' || $uri == '/ipu_admin/') {
      $redirect = "{$archive_website}/ipu_admin";
    }

    else {
      //Generic redirect for asp and html
      $pattern = "/^\/(.+)[.](htm|asp)$/";
      $styles_pattern = "/^\/Styles\/(.+)$/";
      if(preg_match($pattern, $uri)) {
        $permanent_redirect = true;
        $redirect = "{$archive_website}{$uri}";
      } else if($uri == '/our-work/strong-parliaments/international-day-democracy') {
        $permanent_redirect = true;
        $redirect = '/our-work/strong-parliaments/international-day-democracy-0';
      } else if($uri == '/fr/our-work/strong-parliaments/journee-internationale-de-la-democratie') {
        $permanent_redirect = true;
        $redirect = '/fr/our-work/strong-parliaments/journee-internationale-de-la-democratie-0';
      } else if(preg_match($styles_pattern, $uri)) {
        $redirect = "{$archive_website}{$uri}";
      }
    }
    //--------------------------------------------------------------------------
    // Upcoming events temporary redirects
    //--------------------------------------------------------------------------
    // 137th Assembly and related meetings
    if($uri == '/event/137th-ipu-assembly-and-related-meetings') {
      $redirect = 'http://archive.ipu.org/conf-e/137agnd.htm';
    } else if($uri == '/fr/event/137eme-assemblee-de-luip-et-reunions-connexes') {
      $redirect = 'http://archive.ipu.org/conf-f/137agnd.htm';
    }

    //Parliamentary meeting on the occasion of the United Nations Climate Change Conference
    if($uri == '/event/parliamentary-meeting-occasion-un-climate-change-conference') {
      $redirect = 'http://archive.ipu.org/splz-e/cop23.htm';
    } else if($uri == '/fr/event/reunion-parlementaire-loccasion-de-la-conference-des-nations-unies-sur-les-changements-climatiques') {
      $redirect = 'http://archive.ipu.org/splz-f/cop23.htm';
    }

    //Promoting better regional cooperation towards smart and humane migration across the Mediterranean
    if($uri == '/event/promoting-better-regional-cooperation-towards-smart-and-humane-migration-across-mediterranean') {
      $redirect = 'http://archive.ipu.org/splz-e/valletta17.htm';
    } else if($uri == '/fr/event/promouvoir-une-meilleure-cooperation-regionale-pour-des-migrations-sensees-et-humaines-en-mediterranee') {
      $redirect = 'http://archive.ipu.org/splz-f/valletta17.htm';
    }

    //Fourth IPU Global Conference of Young Parliamentarians
    if($uri == '/event/fourth-ipu-global-conference-young-parliamentarians') {
      $redirect = 'http://archive.ipu.org/splz-e/youngmp17.htm';
    } else if($uri == '/fr/event/quatrieme-conference-mondiale-des-jeunes-parlementaires-de-luip') {
      $redirect = 'http://archive.ipu.org/splz-f/youngMP17.htm';
    }

    //Translating international human rights commitments into national realities
    if($uri == '/event/translating-international-human-rights-commitments-national-realities-role-parliaments-and-their-contribution-universal-periodic-review-united') {
      $redirect = 'http://archive.ipu.org/splz-e/salvador17.htm';
    } else if($uri == '/fr/event/seminaire-regional-sur-le-theme-traduire-les-engagements-internationaux-en-matiere-de-droits-de-lhomme-en-realites-nationales-la-contribution') {
      $redirect = 'http://archive.ipu.org/splz-f/salvador17.htm';
    }

    //Buenos Aires session of the Parliamentary Conference on the WTO
    if($uri == '/event/annual-session-parliamentary-conference-wto') {
      $redirect = 'http://archive.ipu.org/splz-e/trade17.htm';
    } else if($uri == '/fr/event/session-annuelle-de-la-conference-parlementaire-sur-lomc') {
      $redirect = 'http://archive.ipu.org/splz-f/trade17.htm';
    }

    //12th summit of women speakers of parliament
    if($uri == '/event/12th-summit-women-speakers-parliament-please-check-back-here-future-information') {
      $redirect = 'http://archive.ipu.org/splz-e/Cochabamba17.htm';
    } else if($uri == '/fr/event/12eme-sommet-des-presidentes-de-parlement-sil-vous-plait-verifier-ici-pour-information-future') {
      $redirect = 'http://archive.ipu.org/splz-f/Cochabamba17.htm';
    }

    if($uri == '/events') {
      $redirect = '/events/new-events';
    }

    if($uri == '/fr/events') {
      $redirect = '/fr/events/new-events';
    }

    //Some images
    if(strtolower($uri) == '/images/signature/img_logo_ipu_f.png') {
      $permanent_redirect = true;
      $redirect = 'http://archive.ipu.org/images/signature/img_logo_ipu_f.png';
    }
    if(strtolower($uri) == '/images/signature/get-engaged-now.png') {
      $permanent_redirect = true;
      $redirect = 'http://archive.ipu.org/images/signature/get-engaged-now.png';
    }
    if(strtolower($uri) == '/images/signature/get-engaged-now-fr.png') {
      $permanent_redirect = true;
      $redirect = 'http://archive.ipu.org/images/signature/get-engaged-now-fr.png';
    }
    if(strtolower($uri) == '/images/signature/img_logo_ipu_e.png') {
      $permanent_redirect = true;
      $redirect = 'http://archive.ipu.org/images/signature/img_logo_ipu_e.png';
    }
    if(strtolower($uri) == '/images/signature/img_logo_ipu_fs.gif') {
      $redirect = 'http://archive.ipu.org/images/signature/img_logo_ipu_fs.gif';
    }

    $list_english_no_redirect = array(
      '/event/regional-seminar-contribution-parliament-promotion-and-protection-rights-child-occasion-cemac-parliamentary-session-please-check-back-here',
      '/event/conference-gender-equality-committees-in-framework-joint-ipu-un-women-and-committee-equal-opportunity-women-and-men-parliament-turkey-project',
      '/event/regional-seminar-parliaments-and-implementation-un-security-council-resolution-1540-please-check-back-here-future-information',
      '/event/conference-counter-terrorism-please-check-back-here-future-information',
      '/event/regional-seminar-migration-please-check-back-here-future-information',
      '/event/regional-seminar-latin-american-and-caribbean-parliaments-financial-inclusion-women-please-check-back-here-future-information',
      // '/event/138th-assembly-and-related-meetings-please-check-back-here-future-information',
      '/event/regional-seminar-sdgs-parliaments-eastern-and-central-europe-and-central-asia-please-check-back-here-future-information',
      '/event/third-south-asian-speakers-summit-achieving-sdgs-please-check-back-here-future-information',
      '/event/ipu-unisdr-sub-regional-seminar-northeast-asia-disaster-risk-reduction-and-2030-agenda-sustainable-development-please-check-back-here-future',
      // '/event/annual-parliamentary-hearing-united-nations-please-check-back-here-future-information'
    );

    if(in_array($uri, $list_english_no_redirect)) {
      $redirect = '/events/new-events';
    } else {
      $list_french_no_redirect = array(
        '/fr/event/seminaire-regional-sur-la-contribution-des-parlements-en-matiere-de-promotion-et-de-protection-des-droits-de-lenfant-loccasion-de-la-session',
        '/fr/event/conference-pour-les-commissions-sur-legalite-des-sexes-dans-le-cadre-du-projet-conjoint-de-luip-donu-femmes-et-de-la-commission-sur-legalite-des',
        '/fr/event/seminaire-regional-sur-les-parlements-et-la-mise-en-oeuvre-de-la-resolution-1540-du-conseil-de-securite-de-lonu-sil-vous-plait-verifier-ici-pour',
        '/fr/event/conference-sur-la-lutte-contre-le-terrorisme-sil-vous-plait-verifier-ici-pour-information-future',
        '/fr/event/seminaire-regional-sur-les-migrations-sil-vous-plait-verifier-ici-pour-information-future',
        '/fr/event/seminaire-regional-pour-les-parlements-damerique-latine-et-des-caraibes-sur-linclusion-financiere-des-femmes-sil-vous-plait-verifier-ici-pour',
        // '/fr/event/138eme-assemblee-et-reunions-connexes-sil-vous-plait-verifier-ici-pour-information-future',
        '/fr/event/seminaire-regional-pour-les-parlements-deurope-centrale-et-orientale-et-dasie-centrale-sur-les-odd-sil-vous-plait-verifier-ici-pour-information',
        '/fr/event/troisieme-sommet-des-presidents-de-parlement-dasie-du-sud-sur-la-mise-en-oeuvre-des-odd-sil-vous-plait-verifier-ici-pour-information-future',
        '/fr/event/seminaire-sous-regional-uip-unisdr-pour-les-pays-dasie-du-nord-est-sur-la-reduction-des-risques-de-catastrophe-et-le-programme-de-developpement',
        // '/fr/event/audition-parlementaire-annuelle-aux-nations-unies-sil-vous-plait-verifier-ici-pour-information-future'
      );

      if(in_array($uri, $list_french_no_redirect)) {
        $redirect = '/fr/events/new-events';
      }
    }
    if(strtolower($uri) == '/fr/news/press-releases') {
      $redirect = '/fr/actualites/communiques-de-presse';
    } else if(strtolower($uri) == '/fr/news/news-in-brief') {
      $redirect = '/fr/actualites/actualites-en-bref';
    } else if(strtolower($uri) == '/fr/news/features') {
      $redirect = '/fr/actualites/articles';
    } else if(strtolower($uri) == '/fr/news/features/profiles') {
      $redirect = '/fr/actualites/articles/profils';
    } else if(strtolower($uri) == '/fr/news/features/human-rights-cases') {
      $redirect = '/fr/actualites/articles/cas-datteinte-aux-droits-de-lhomme-des-parlementaires';
    }
  }
  //--------------------------------------------------------------------------
}
if($redirect && php_sapi_name() != "cli") {
  if($permanent_redirect) {
    header("HTTP/1.1 301 Moved Permanently");
  }
  header('Location: ' . $redirect);
  exit();
}

$databases['default']['default'] = array(
  'driver' => 'mysql',
  'database' => 'ipu_committee_members',
  'username' => 'root',
  'password' => 'qburst',
  'host' => 'localhost',
  'prefix' => '',
  'collation' => 'utf8_general_ci',
);
