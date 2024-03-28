<?php
/**
 * ImportController.php
 *
 * PHP version 7
 *
 * @category    DemoImport
 *
 * @author      XE Developers <developers@xpressengine.com>
 * @copyright   2019 Copyright XEHub Corp. <https://www.xehub.io>
 * @license     http://www.gnu.org/licenses/lgpl-3.0-standalone.html LGPL
 *
 * @link        https://xpressengine.io
 */

namespace Xpressengine\Plugins\DemoImport\Controllers;

use App\Http\Controllers\Controller;
use XeFrontend;
use XePresenter;
use Xpressengine\Http\Request;
use Xpressengine\XePlugin\DemoImport\Plugin;

/**
 * Class ImportController
 *
 * @category    DemoImport
 *
 * @author      XE Developers <developers@xpressengine.com>
 * @copyright   2019 Copyright XEHub Corp. <https://www.xehub.io>
 * @license     http://www.gnu.org/licenses/lgpl-3.0-standalone.html LGPL
 *
 * @link        https://xpressengine.io
 */
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
            'type' => 'success',
        ];

        return \XePresenter::makeApi($data);
    }
}
