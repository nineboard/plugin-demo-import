<?php

namespace Xpressengine\Plugins\DemoImport\Controllers;

use XeFrontend;
use XePresenter;
use App\Http\Controllers\Controller;
use Xpressengine\Http\Request;
use Xpressengine\XePlugin\DemoImport\Plugin;

class ImportController extends Controller
{
    public function __construct()
    {
        XeFrontend::css(Plugin::asset('assets/css/style.css'))->load();
        XeFrontend::js(Plugin::asset('assets/js/demo_import.js'))->load();
    }

    public function index()
    {
        $demoSupplyThemes = app('demo_import::handler')->getDemoSupplyThemes();

        return XePresenter::make('demo_import::views.import.index', ['demoSupplyThemes' => $demoSupplyThemes]);
    }

    public function storeTheme(Request $request)
    {
        foreach ($request->get('themeIds', []) as $themeId) {
            $themeId = $themeId['value'];

            app('demo_import::handler')->storeDemoData($themeId);
        }

        $data = [
            'type' => 'success'
        ];

        return \XePresenter::makeApi($data);
    }
}
