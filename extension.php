<?php

use Illuminate\Foundation\Application;
use Cartalyst\Extensions\ExtensionInterface;
use Cartalyst\Settings\Repository as Settings;
use Cartalyst\Permissions\Container as Permissions;

return [

	/*
	|--------------------------------------------------------------------------
	| Name
	|--------------------------------------------------------------------------
	|
	| This is your extension name and it is only required for
	| presentational purposes.
	|
	*/

	'name' => 'Pricing',

	/*
	|--------------------------------------------------------------------------
	| Slug
	|--------------------------------------------------------------------------
	|
	| This is your extension unique identifier and should not be changed as
	| it will be recognized as a new extension.
	|
	| Ideally, this should match the folder structure within the extensions
	| folder, but this is completely optional.
	|
	*/

	'slug' => 'sanatorium/pricing',

	/*
	|--------------------------------------------------------------------------
	| Author
	|--------------------------------------------------------------------------
	|
	| Because everybody deserves credit for their work, right?
	|
	*/

	'author' => 'Sanatorium',

	/*
	|--------------------------------------------------------------------------
	| Description
	|--------------------------------------------------------------------------
	|
	| One or two sentences describing the extension for users to view when
	| they are installing the extension.
	|
	*/

	'description' => 'Pricing of objects',

	/*
	|--------------------------------------------------------------------------
	| Version
	|--------------------------------------------------------------------------
	|
	| Version should be a string that can be used with version_compare().
	| This is how the extensions versions are compared.
	|
	*/

	'version' => '0.1.9',

	/*
	|--------------------------------------------------------------------------
	| Requirements
	|--------------------------------------------------------------------------
	|
	| List here all the extensions that this extension requires to work.
	| This is used in conjunction with composer, so you should put the
	| same extension dependencies on your main composer.json require
	| key, so that they get resolved using composer, however you
	| can use without composer, at which point you'll have to
	| ensure that the required extensions are available.
	|
	*/

	'require' => [
		
	],

	/*
	|--------------------------------------------------------------------------
	| Autoload Logic
	|--------------------------------------------------------------------------
	|
	| You can define here your extension autoloading logic, it may either
	| be 'composer', 'platform' or a 'Closure'.
	|
	| If composer is defined, your composer.json file specifies the autoloading
	| logic.
	|
	| If platform is defined, your extension receives convetion autoloading
	| based on the Platform standards.
	|
	| If a Closure is defined, it should take two parameters as defined
	| bellow:
	|
	|	object \Composer\Autoload\ClassLoader      $loader
	|	object \Illuminate\Foundation\Application  $app
	|
	| Supported: "composer", "platform", "Closure"
	|
	*/

	'autoload' => 'composer',

	/*
	|--------------------------------------------------------------------------
	| Service Providers
	|--------------------------------------------------------------------------
	|
	| Define your extension service providers here. They will be dynamically
	| registered without having to include them in app/config/app.php.
	|
	*/

	'providers' => [

		'Sanatorium\Pricing\Providers\CurrencyServiceProvider',
		'Sanatorium\Pricing\Providers\MoneyServiceProvider',
		'Sanatorium\Pricing\Providers\CurrencieshistoryServiceProvider',

	],

	/*
	|--------------------------------------------------------------------------
	| Routes
	|--------------------------------------------------------------------------
	|
	| Closure that is called when the extension is started. You can register
	| any custom routing logic here.
	|
	| The closure parameters are:
	|
	|	object \Cartalyst\Extensions\ExtensionInterface  $extension
	|	object \Illuminate\Foundation\Application        $app
	|
	*/

	'routes' => function(ExtensionInterface $extension, Application $app)
	{

		Route::group([
				'prefix'    => admin_uri().'/pricing/currencies',
				'namespace' => 'Sanatorium\Pricing\Controllers\Admin',
			], function()
			{
				Route::get('/' , ['as' => 'admin.sanatorium.pricing.currencies.all', 'uses' => 'CurrenciesController@index']);
				Route::post('/', ['as' => 'admin.sanatorium.pricing.currencies.all', 'uses' => 'CurrenciesController@executeAction']);

				Route::get('grid', ['as' => 'admin.sanatorium.pricing.currencies.grid', 'uses' => 'CurrenciesController@grid']);

				Route::get('create' , ['as' => 'admin.sanatorium.pricing.currencies.create', 'uses' => 'CurrenciesController@create']);
				Route::post('create', ['as' => 'admin.sanatorium.pricing.currencies.create', 'uses' => 'CurrenciesController@store']);

				Route::get('{id}'   , ['as' => 'admin.sanatorium.pricing.currencies.edit'  , 'uses' => 'CurrenciesController@edit']);
				Route::post('{id}'  , ['as' => 'admin.sanatorium.pricing.currencies.edit'  , 'uses' => 'CurrenciesController@update']);

				Route::delete('{id}', ['as' => 'admin.sanatorium.pricing.currencies.delete', 'uses' => 'CurrenciesController@delete']);
			});

		Route::group([
			'prefix'    => 'pricing/currencies',
			'namespace' => 'Sanatorium\Pricing\Controllers\Frontend',
		], function()
		{
			Route::get('/', ['as' => 'sanatorium.pricing.currencies.index', 'uses' => 'CurrenciesController@index']);
		
			Route::get('test', ['as' => 'sanatorium.pricing.currencies.test', 'uses' => 'CurrenciesController@test']);

			Route::get('{id}', ['as' => 'sanatorium.pricing.currencies.set', 'uses' => 'CurrenciesController@set']);
		});

					Route::group([
				'prefix'    => admin_uri().'/pricing/money',
				'namespace' => 'Sanatorium\Pricing\Controllers\Admin',
			], function()
			{
				Route::get('/' , ['as' => 'admin.sanatorium.pricing.money.all', 'uses' => 'MoneyController@index']);
				Route::post('/', ['as' => 'admin.sanatorium.pricing.money.all', 'uses' => 'MoneyController@executeAction']);

				Route::get('grid', ['as' => 'admin.sanatorium.pricing.money.grid', 'uses' => 'MoneyController@grid']);

				Route::get('create' , ['as' => 'admin.sanatorium.pricing.money.create', 'uses' => 'MoneyController@create']);
				Route::post('create', ['as' => 'admin.sanatorium.pricing.money.create', 'uses' => 'MoneyController@store']);

				Route::get('{id}'   , ['as' => 'admin.sanatorium.pricing.money.edit'  , 'uses' => 'MoneyController@edit']);
				Route::post('{id}'  , ['as' => 'admin.sanatorium.pricing.money.edit'  , 'uses' => 'MoneyController@update']);

				Route::delete('{id}', ['as' => 'admin.sanatorium.pricing.money.delete', 'uses' => 'MoneyController@delete']);
			});

		Route::group([
			'prefix'    => 'pricing/money',
			'namespace' => 'Sanatorium\Pricing\Controllers\Frontend',
		], function()
		{
			Route::get('/', ['as' => 'sanatorium.pricing.money.index', 'uses' => 'MoneyController@index']);
		});

					Route::group([
				'prefix'    => admin_uri().'/pricing/currencieshistories',
				'namespace' => 'Sanatorium\Pricing\Controllers\Admin',
			], function()
			{
				Route::get('/' , ['as' => 'admin.sanatorium.pricing.currencieshistories.all', 'uses' => 'CurrencieshistoriesController@index']);
				Route::post('/', ['as' => 'admin.sanatorium.pricing.currencieshistories.all', 'uses' => 'CurrencieshistoriesController@executeAction']);

				Route::get('grid', ['as' => 'admin.sanatorium.pricing.currencieshistories.grid', 'uses' => 'CurrencieshistoriesController@grid']);

				Route::get('create' , ['as' => 'admin.sanatorium.pricing.currencieshistories.create', 'uses' => 'CurrencieshistoriesController@create']);
				Route::post('create', ['as' => 'admin.sanatorium.pricing.currencieshistories.create', 'uses' => 'CurrencieshistoriesController@store']);

				Route::get('history' , ['as' => 'admin.sanatorium.pricing.currencieshistories.history', 'uses' => 'CurrencieshistoriesController@history']);
				
				Route::get('{id}'   , ['as' => 'admin.sanatorium.pricing.currencieshistories.edit'  , 'uses' => 'CurrencieshistoriesController@edit']);
				Route::post('{id}'  , ['as' => 'admin.sanatorium.pricing.currencieshistories.edit'  , 'uses' => 'CurrencieshistoriesController@update']);

				Route::delete('{id}', ['as' => 'admin.sanatorium.pricing.currencieshistories.delete', 'uses' => 'CurrencieshistoriesController@delete']);

			});

		Route::group([
			'prefix'    => 'pricing/currencieshistories',
			'namespace' => 'Sanatorium\Pricing\Controllers\Frontend',
		], function()
		{
			Route::get('/', ['as' => 'sanatorium.pricing.currencieshistories.index', 'uses' => 'CurrencieshistoriesController@index']);
		});
	},

	/*
	|--------------------------------------------------------------------------
	| Database Seeds
	|--------------------------------------------------------------------------
	|
	| Platform provides a very simple way to seed your database with test
	| data using seed classes. All seed classes should be stored on the
	| `database/seeds` directory within your extension folder.
	|
	| The order you register your seed classes on the array below
	| matters, as they will be ran in the exact same order.
	|
	| The seeds array should follow the following structure:
	|
	|	Vendor\Namespace\Database\Seeds\FooSeeder
	|	Vendor\Namespace\Database\Seeds\BarSeeder
	|
	*/

	'seeds' => [

		'Sanatorium\Pricing\Database\Seeds\CurrenciesTableSeeder',

	],

	/*
	|--------------------------------------------------------------------------
	| Permissions
	|--------------------------------------------------------------------------
	|
	| Register here all the permissions that this extension has. These will
	| be shown in the user management area to build a graphical interface
	| where permissions can be selected to allow or deny user access.
	|
	| For detailed instructions on how to register the permissions, please
	| refer to the following url https://cartalyst.com/manual/permissions
	|
	*/

	'permissions' => function(Permissions $permissions)
	{
		$permissions->group('currency', function($g)
		{
			$g->name = 'Currencies';

			$g->permission('currency.index', function($p)
			{
				$p->label = trans('sanatorium/pricing::currencies/permissions.index');

				$p->controller('Sanatorium\Pricing\Controllers\Admin\CurrenciesController', 'index, grid');
			});

			$g->permission('currency.create', function($p)
			{
				$p->label = trans('sanatorium/pricing::currencies/permissions.create');

				$p->controller('Sanatorium\Pricing\Controllers\Admin\CurrenciesController', 'create, store');
			});

			$g->permission('currency.edit', function($p)
			{
				$p->label = trans('sanatorium/pricing::currencies/permissions.edit');

				$p->controller('Sanatorium\Pricing\Controllers\Admin\CurrenciesController', 'edit, update');
			});

			$g->permission('currency.delete', function($p)
			{
				$p->label = trans('sanatorium/pricing::currencies/permissions.delete');

				$p->controller('Sanatorium\Pricing\Controllers\Admin\CurrenciesController', 'delete');
			});
		});

		$permissions->group('money', function($g)
		{
			$g->name = 'Money';

			$g->permission('money.index', function($p)
			{
				$p->label = trans('sanatorium/pricing::money/permissions.index');

				$p->controller('Sanatorium\Pricing\Controllers\Admin\MoneyController', 'index, grid');
			});

			$g->permission('money.create', function($p)
			{
				$p->label = trans('sanatorium/pricing::money/permissions.create');

				$p->controller('Sanatorium\Pricing\Controllers\Admin\MoneyController', 'create, store');
			});

			$g->permission('money.edit', function($p)
			{
				$p->label = trans('sanatorium/pricing::money/permissions.edit');

				$p->controller('Sanatorium\Pricing\Controllers\Admin\MoneyController', 'edit, update');
			});

			$g->permission('money.delete', function($p)
			{
				$p->label = trans('sanatorium/pricing::money/permissions.delete');

				$p->controller('Sanatorium\Pricing\Controllers\Admin\MoneyController', 'delete');
			});
		});

		$permissions->group('currencieshistory', function($g)
		{
			$g->name = 'Currencieshistories';

			$g->permission('currencieshistory.index', function($p)
			{
				$p->label = trans('sanatorium/pricing::currencieshistories/permissions.index');

				$p->controller('Sanatorium\Pricing\Controllers\Admin\CurrencieshistoriesController', 'index, grid');
			});

			$g->permission('currencieshistory.create', function($p)
			{
				$p->label = trans('sanatorium/pricing::currencieshistories/permissions.create');

				$p->controller('Sanatorium\Pricing\Controllers\Admin\CurrencieshistoriesController', 'create, store');
			});

			$g->permission('currencieshistory.edit', function($p)
			{
				$p->label = trans('sanatorium/pricing::currencieshistories/permissions.edit');

				$p->controller('Sanatorium\Pricing\Controllers\Admin\CurrencieshistoriesController', 'edit, update');
			});

			$g->permission('currencieshistory.delete', function($p)
			{
				$p->label = trans('sanatorium/pricing::currencieshistories/permissions.delete');

				$p->controller('Sanatorium\Pricing\Controllers\Admin\CurrencieshistoriesController', 'delete');
			});
		});
	},

	/*
	|--------------------------------------------------------------------------
	| Widgets
	|--------------------------------------------------------------------------
	|
	| Closure that is called when the extension is started. You can register
	| all your custom widgets here. Of course, Platform will guess the
	| widget class for you, this is just for custom widgets or if you
	| do not wish to make a new class for a very small widget.
	|
	*/

	'widgets' => function()
	{

	},

	/*
	|--------------------------------------------------------------------------
	| Settings
	|--------------------------------------------------------------------------
	|
	| Register any settings for your extension. You can also configure
	| the namespace and group that a setting belongs to.
	|
	*/

	'settings' => function(Settings $settings, Application $app)
	{

	},

	/*
	|--------------------------------------------------------------------------
	| Menus
	|--------------------------------------------------------------------------
	|
	| You may specify the default various menu hierarchy for your extension.
	| You can provide a recursive array of menu children and their children.
	| These will be created upon installation, synchronized upon upgrading
	| and removed upon uninstallation.
	|
	| Menu children are automatically put at the end of the menu for extensions
	| installed through the Operations extension.
	|
	| The default order (for extensions installed initially) can be
	| found by editing app/config/platform.php.
	|
	*/

	'menus' => [

		'admin' => [
			[
				'slug' => 'admin-sanatorium-pricing',
				'name' => 'Pricing',
				'class' => 'fa fa-circle-o',
				'uri' => 'pricing',
				'regex' => '/:admin\/pricing/i',
				'children' => [
					[
						'class' => 'fa fa-circle-o',
						'name' => 'Currencies',
						'uri' => 'pricing/currencies',
						'regex' => '/:admin\/pricing\/currency/i',
						'slug' => 'admin-sanatorium-pricing-currency',
					],
					[
						'class' => 'fa fa-circle-o',
						'name' => 'Money',
						'uri' => 'pricing/money',
						'regex' => '/:admin\/pricing\/money/i',
						'slug' => 'admin-sanatorium-pricing-money',
					],
					[
						'class' => 'fa fa-circle-o',
						'name' => 'Currencieshistories',
						'uri' => 'pricing/currencieshistories',
						'regex' => '/:admin\/pricing\/currencieshistory/i',
						'slug' => 'admin-sanatorium-pricing-currencieshistory',
					],
				],
			],
		],
		'main' => [
			
		],
	],

];
