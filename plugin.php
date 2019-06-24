<?php

namespace Xpressengine\XePlugin\DemoImport;

use Route;
use Xpressengine\Plugin\AbstractPlugin;
use Xpressengine\Plugins\DemoImport\Handler;

class Plugin extends AbstractPlugin
{
    /**
     * 이 메소드는 활성화(activate) 된 플러그인이 부트될 때 항상 실행됩니다.
     *
     * @return void
     */
    public function boot()
    {
        $this->route();
        $this->storeSettingMenu();
        $this->bindClass();
    }

    /**
     * 플러그인이 활성화될 때 실행할 코드를 여기에 작성한다.
     *
     * @param string|null $installedVersion 현재 XpressEngine에 설치된 플러그인의 버전정보
     *
     * @return void
     */
    public function activate($installedVersion = null)
    {
        // implement code
    }

    /**
     * 플러그인을 설치한다. 플러그인이 설치될 때 실행할 코드를 여기에 작성한다
     *
     * @return void
     */
    public function install()
    {
        $this->importLang();
    }

    /**
     * 해당 플러그인이 설치된 상태라면 true, 설치되어있지 않다면 false를 반환한다.
     * 이 메소드를 구현하지 않았다면 기본적으로 설치된 상태(true)를 반환한다.
     *
     * @return boolean 플러그인의 설치 유무
     */
    public function checkInstalled()
    {
        // implement code

        return parent::checkInstalled();
    }

    /**
     * 플러그인을 업데이트한다.
     *
     * @return void
     */
    public function update()
    {
        // implement code
    }

    /**
     * 해당 플러그인이 최신 상태로 업데이트가 된 상태라면 true, 업데이트가 필요한 상태라면 false를 반환함.
     * 이 메소드를 구현하지 않았다면 기본적으로 최신업데이트 상태임(true)을 반환함.
     *
     * @return boolean 플러그인의 설치 유무,
     */
    public function checkUpdated()
    {
        // implement code

        return parent::checkUpdated();
    }

    protected function route()
    {
        Route::settings('demo_import', function () {
            Route::group([
                'namespace' => 'Xpressengine\\Plugins\\DemoImport\\Controllers',
                'as' => 'demo_import.'
            ], function () {
                Route::get('/index', [
                    'as' => 'index',
                    'uses' => 'ImportController@index',
                    'settings_menu' => 'theme.demo_import'
                ]);
                Route::post('/store_theme', ['as' => 'store_theme', 'uses' => 'ImportController@storeTheme']);
            });
        });
    }

    private function importLang()
    {
        \XeLang::putFromLangDataSource('demo_import', $this->path('langs/lang.php'));
    }

    private function storeSettingMenu()
    {
        app('xe.register')->push(
            'settings/menu',
            'theme.demo_import',
            [
                'title' => 'demo_import::demoImport',
                'description' => 'demo_import::settingMenuDescription',
                'display' => true,
                'ordering' => 500
            ]
        );
    }

    private function bindClass()
    {
        app()->singleton(Handler::class, function () {
            $handler = new Handler();

            return $handler;
        });
        app()->alias(Handler::class, 'demo_import::handler');
    }
}
