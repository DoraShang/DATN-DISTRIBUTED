<?php

/**
 * Routes configuration
 *
 * In this file, you set up routes to your controllers and their actions.
 * Routes are very important mechanism that allows you to freely connect
 * different URLs to chosen controllers and their actions (functions).
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Config
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
/**
 * Here, we are connecting '/' (base path) to controller called 'Pages',
 * its action called 'display', and we pass a param to select the view file
 * to use (in this case, /app/View/Pages/home.ctp)...
 */
// Router::connect('/', array('plugin'=>'default', 'controller' => 'home', 'action' => 'intro'));
// Router::connect('/home', array('plugin'=>'default', 'controller' => 'home', 'action' => 'home_index'));
Router::connect('/', array('plugin' => 'default', 'controller' => 'home', 'action' => 'home_index'));

Router::connect('/en', array('plugin' => 'default', 'controller' => 'home', 'action' => 'home_index', 'en'));
Router::connect('/en/contact', array('plugin' => 'default', 'controller' => 'contact', 'action' => 'index', 'en'));

Router::connect('/jp', array('plugin' => 'default', 'controller' => 'home', 'action' => 'home_index', 'jp'));
Router::connect('/jp/contact', array('plugin' => 'default', 'controller' => 'contact', 'action' => 'index', 'jp'));

Router::connect('/kr', array('plugin' => 'default', 'controller' => 'home', 'action' => 'home_index', 'kr'));
Router::connect('/kr/contact', array('plugin' => 'default', 'controller' => 'contact', 'action' => 'index', 'kr'));


Router::connect('/admin', array('plugin' => 'admin', 'controller' => 'admin_dashboard', 'action' => 'dashboard_login'));

Router::connect('/cart/add/*', array('plugin' => 'default', 'controller' => 'cart', 'action' => 'cart_add'));
Router::connect('/cart/adds/*', array('plugin' => 'default', 'controller' => 'cart', 'action' => 'cart_add_multiple'));
Router::connect('/cart/delete/*', array('plugin' => 'default', 'controller' => 'cart', 'action' => 'cart_delete'));
Router::connect('/cart/list', array('plugin' => 'default', 'controller' => 'cart', 'action' => 'cart_list'));
Router::connect('/cart/update/', array('plugin' => 'default', 'controller' => 'cart', 'action' => 'cart_update'));
Router::connect('/cart/payment', array('plugin' => 'default', 'controller' => 'cart', 'action' => 'cart_payment'));
Router::connect('/cart/payment-now', array('plugin' => 'default', 'controller' => 'cart', 'action' => 'cart_payment_now'));
Router::connect('/cart/success', array('plugin' => 'default', 'controller' => 'cart', 'action' => 'cart_success'));

Router::connect('/sitemap.xml', array('plugin' => 'default', 'controller' => 'meta', 'action' => 'sitemap'));
Router::connect('/contact', array('plugin' => 'default', 'controller' => 'contact', 'action' => 'index'));
Router::connect('/search/*', array('plugin' => 'default', 'controller' => 'node', 'action' => 'search_els'));
Router::connect('/tags/*', array('plugin' => 'default', 'controller' => 'node', 'action' => 'tags'));
Router::connect('/comment', array('plugin' => 'default', 'controller' => 'node', 'action' => 'comment'));
Router::connect('/email', array('plugin' => 'default', 'controller' => 'contact', 'action' => 'email'));

Router::connect('/videos', array('plugin' => 'default', 'controller' => 'node', 'action' => 'videos'));

Router::connect('/login', array('plugin' => 'default', 'controller' => 'node', 'action' => 'login'));
Router::connect('/register', array('plugin' => 'default', 'controller' => 'node', 'action' => 'register'));
Router::connect('/logout', array('plugin' => 'default', 'controller' => 'node', 'action' => 'logout'));
Router::connect('/recover', array('plugin' => 'default', 'controller' => 'node', 'action' => 'recover'));

Router::connect('/user/dashboard', array('plugin' => 'default', 'controller' => 'node', 'action' => 'user_dashboard'));
Router::connect('/user/history', array('plugin' => 'default', 'controller' => 'node', 'action' => 'user_history'));
Router::connect('/user/refs', array('plugin' => 'default', 'controller' => 'node', 'action' => 'user_refs'));
Router::connect('/user/thanks', array('plugin' => 'default', 'controller' => 'node', 'action' => 'user_thanks'));
Router::connect('/user/postadd', array('plugin' => 'default', 'controller' => 'node', 'action' => 'user_post_add'));
Router::connect('/user/account', array('plugin' => 'default', 'controller' => 'node', 'action' => 'user_account'));
Router::connect('/user/pass', array('plugin' => 'default', 'controller' => 'node', 'action' => 'user_change_pass'));
Router::connect('/user/banner', array('plugin' => 'default', 'controller' => 'node', 'action' => 'user_banner_list'));
Router::connect('/user/banner_add', array('plugin' => 'default', 'controller' => 'node', 'action' => 'user_banner_add'));
Router::connect('/user/banner_edit', array('plugin' => 'default', 'controller' => 'node', 'action' => 'user_banner_edit'));
Router::connect('/user/banner_delete/*', array('plugin' => 'default', 'controller' => 'node', 'action' => 'user_banner_delete'));
Router::connect('/user/wishlist', array('plugin' => 'default', 'controller' => 'node', 'action' => 'user_wishlist'));

Router::connect('/upload_image', array('plugin' => 'default', 'controller' => 'node', 'action' => 'upload_image'));

Router::connect(
	'/profile/:slug:ext',
	array('plugin' => 'default', 'controller' => 'node', 'action' => 'team_detail'),
	array(
		'slug' => '[a-zA-Z0-9\-]+',
		'ext' => '\.html',
		'pass' => array('slug', 'ext'),
	)
);

// Router::connect('/style.css', array('plugin'=>'default', 'controller' => 'home', 'action' => 'style'));


Router::connect(
	'/:slug:ext/*',
	array('plugin' => 'default', 'controller' => 'node', 'action' => 'index'),
	array(
		'slug' => '[a-zA-Z0-9\-]+',
		'ext' => '\.html',
		'pass' => array('slug', 'ext'),
	)
);

// Router::connect('/:lang/:slug:ext',
//         array('plugin'=>'default', 'controller' => 'node', 'action' => 'index'),
//         array(
//                 'lang'=>'en|vi|jp|kr',
//                 'slug'=>'[a-zA-Z0-9\-]+',
//                 'ext'=>'\.html',
//                 'pass'=> array('slug', 'ext', 'lang'),
//         )
// );

Router::connect(
	'/:slug:ext',
	array('plugin' => 'default', 'controller' => 'node', 'action' => 'index'),
	array(
		'slug' => '[a-zA-Z0-9\-]+',
		'ext' => '\.html',
		'pass' => array('slug', 'ext'),
	)
);

Router::connect(
	'/:day/:mon/:year/:slug:ext',
	array('plugin' => 'default', 'controller' => 'node', 'action' => 'index2'),
	array(
		'day' => '[0-9]+',
		'mon' => '[0-9]+',
		'year' => '[0-9]+',
		'slug' => '[a-zA-Z0-9\-]+',
		'ext' => '\.html',
		'pass' => array('day', 'mon', 'year', 'slug', 'ext'),
	)
);
Router::connect(
	'/tour/:slug:ext',
	array('plugin' => 'default', 'controller' => 'node', 'action' => 'index'),
	array(
		'slug' => '[a-zA-Z0-9\-]+',
		'ext' => '\.html',
		'pass' => array('tour', 'slug', 'ext'),
	)
);

// trang page
Router::connect(
	'/page/:slug:ext',
	array('plugin' => 'default', 'controller' => 'node', 'action' => 'index_page'),
	array(
		'slug' => '[a-zA-Z0-9\-]+',
		'ext' => '\.html',
		'pass' => array('page', 'slug', 'ext'),
	)
);
// trang page
Router::connect(
	'/bh/:id',
	array('plugin' => 'default', 'controller' => 'node', 'action' => 'check_qr'),
	array(
		'id' => '[a-zA-Z0-9\-]+',
		'pass' => array('id'),
	)
);

Router::connect(
	'/bh-infor/:id',
	array('plugin' => 'default', 'controller' => 'node', 'action' => 'check_qr_infor'),
	array(
		'id' => '[a-zA-Z0-9\-]+',
		'pass' => array('id'),
	)
);
Router::connect('/404.html', array('plugin' => 'default', 'controller' => 'node', 'action' => 'page404'));


/**
 * Load all plugin routes. See the CakePlugin documentation on
 * how to customize the loading of plugin routes.
 */
CakePlugin::routes();

/**
 * Load the CakePHP default routes. Only remove this if you do not want to use
 * the built-in default routes.
 */
require CAKE . 'Config' . DS . 'routes.php';
