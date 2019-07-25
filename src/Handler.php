<?php
/**
 * Handler.php
 *
 * PHP version 7
 *
 * @category    DemoImport
 * @package     Xpressengine\Plugins\DemoImport
 * @author      XE Developers <developers@xpressengine.com>
 * @copyright   2019 Copyright XEHub Corp. <https://www.xehub.io>
 * @license     http://www.gnu.org/licenses/lgpl-3.0-standalone.html LGPL
 * @link        https://xpressengine.io
 */

namespace Xpressengine\Plugins\DemoImport;

use Xpressengine\Menu\MenuHandler;
use Xpressengine\Permission\Grant;
use Xpressengine\Plugins\DemoImport\Exceptions\ExistThemeNameException;
use Xpressengine\Routing\InstanceRoute;
use Xpressengine\Skin\SkinHandler;
use Xpressengine\Theme\ThemeHandler;
use XeMenu;
use XeSite;

/**
 * Class Handler
 *
 * @category    DemoImport
 * @package     Xpressengine\Plugins\DemoImport
 * @author      XE Developers <developers@xpressengine.com>
 * @copyright   2019 Copyright XEHub Corp. <https://www.xehub.io>
 * @license     http://www.gnu.org/licenses/lgpl-3.0-standalone.html LGPL
 * @link        https://xpressengine.io
 */
class Handler
{
    const DEMO_MENU_ID = 'demo_import::DEMO_MENU_ID';
    const DEMO_BOARD_ID = 'demo_import::DEMO_BOARD_ID';
    const DEMO_BANNER_ID = 'demo_import::DEMO_BANNER_ID';
    const DEMO_WIDGET_PAGE_ID = 'demo_import::DEMO_WIDGET_PAGE_ID';

    protected $supportMethodNames = [];
    protected $themeConfigs = [];

    protected $menuId = '';
    protected $menuItemIds = [];

    public function __construct()
    {
        $this->supportMethodNames['theme'] = 'getThemeConfigInformation';
        $this->supportMethodNames['menuItems'] = 'getMenuItemContents';
        $this->supportMethodNames['board'] = 'getBoardContentsInformation';
        $this->supportMethodNames['banner'] = 'getBannerContentsInformation';
        $this->supportMethodNames['widgetpage'] = 'getWidgetpageContentsInformation';
    }

    public function getDemoSupplyThemes()
    {
        $demoSupplyThemes = [];

        /** @var ThemeHandler $themeHandler */
        $themeHandler = app('xe.theme');

        $themes = $themeHandler->getAllTheme();
        foreach ($themes as $theme) {
            $themeObject = $theme->getObject();

            if (method_exists($themeObject, $this->supportMethodNames['theme']) == true) {
                $demoSupplyThemes[] = $theme;
            }
        }

        return $demoSupplyThemes;
    }

    public function storeDemoData($themeId)
    {
        $theme = $this->getTheme($themeId);

        $menu = $this->createMenu($theme);
        $this->menuId = $menu->id;

        $this->storeThemeConfig($theme, $theme->{$this->supportMethodNames['theme']}());
        $this->setMenuTheme($menu);

        $themeObject = $theme->getObject();
        $this->createMenuItems($theme->getTitle(), $menu, $themeObject->{$this->supportMethodNames['menuItems']}());

        if (method_exists($themeObject, $this->supportMethodNames['board']) == true) {
            $this->storeBoardData($themeObject->{$this->supportMethodNames['board']}());
        }

        if (method_exists($themeObject, $this->supportMethodNames['banner']) == true) {
            $this->storeBannerData($themeObject->{$this->supportMethodNames['banner']}());
        }

        if (method_exists($themeObject, $this->supportMethodNames['widgetpage']) == true) {
            $this->storeWidgetpageData($themeObject->{$this->supportMethodNames['widgetpage']}());
        }
    }

    protected function setMenuTheme($menu)
    {
        if (isset($this->themeConfigs['default']) == true) {
            $defaultTheme = $this->themeConfigs['default'];
        } else {
            $defaultTheme = end($this->themeConfigs);
        }

        XeMenu::setMenuTheme($menu, $defaultTheme, $defaultTheme);
    }

    protected function getTheme($themeId)
    {
        /** @var ThemeHandler $themeHandler */
        $themeHandler = app('xe.theme');

        $themes = $themeHandler->getAllTheme();

        $targetThemes = array_where($themes, function ($theme) use ($themeId) {
            return $theme->getObject()->getId() == $themeId;
        });

        if (count($targetThemes) > 1) {
            throw new ExistThemeNameException();
        }

        $theme = array_shift($targetThemes);

        return $theme;
    }

    protected function storeThemeConfig($theme, $themeConfigs)
    {
        /** @var ThemeHandler $themeHandler */
        $themeHandler = app('xe.theme');

        $configs = $themeHandler->getThemeConfigList($theme->getId());
        $newId = 0;

        if (count($configs) > 0) {
            $last = array_pop($configs);
            $lastId = $last->name;

            $prefix = $themeHandler->getConfigId($theme->getId());
            $id = str_replace([$prefix, $themeHandler->configDelimiter], '', $lastId);

            $newId = (int)$id + 1;
        }

        foreach ($themeConfigs as $configName => $config) {
            $this->themeConfigs[$configName] = $theme->getId() . $themeHandler->configDelimiter . $newId++;

            app('xe.theme')->setThemeConfig($this->themeConfigs[$configName], $config);
        }
    }

    protected function createMenu($theme)
    {
        $title = $theme->getTitle() . '_demo';
        $menuDescription = $theme->getTitle() . ' demo 메뉴입니다.';

        $menu = XeMenu::createMenu([
            'title' => $title,
            'description' => $menuDescription,
            'site_key' => XeSite::getCurrentSiteKey()
        ]);

        app('xe.permission')->register($menu->getKey(), XeMenu::getDefaultGrant());

        return $menu;
    }

    protected function createMenuItems($themeTitle, $menu, $menuItemContents)
    {
        /** @var MenuHandler $menuHandler */
        $menuHandler = app('xe.menu');

        foreach ($menuItemContents as $type => $itemContents) {
            if ($type == 'widgetpage' || $type == 'board') {
                foreach ($itemContents as $key => $menuItem) {
                    $input = $menuItem['input'];
                    $input['url'] = $this->getUrl($themeTitle);
                    $menuType = $menuItem['menuType'];

                    $item = $menuHandler->createItem($menu, $input, $menuType);

                    app('xe.permission')->register($menuHandler->permKeyString($item), new Grant);

                    //optional
                    $menuHandler->setMenuItemTheme($item, null, null);
                    if (isset($menuItem['options']) == true) {
                        $options = $menuItem['options'];

                        if (isset($options['theme']) == true) {
                            $themeName = $options['theme'];

                            if (array_key_exists($themeName, $this->themeConfigs) == true) {
                                $menuHandler->updateMenuItemTheme($item, $this->themeConfigs[$themeName], $this->themeConfigs[$themeName]);
                            }
                        }

                        if (isset($options['boardSkin']) == true) {
                            /** @var SkinHandler $skinHandler */
                            $skinHandler = app('xe.skin');

                            $skin = $skinHandler->get($options['boardSkin']);

                            $skinHandler->assign('module/board@board:' . $item->id, $skin, 'desktop');
                            $skinHandler->assign('module/board@board:' . $item->id, $skin, 'mobile');
                        }
                    }

                    $this->menuItemIds[$key] = $item->id;
                }
            } elseif ($type == 'banner') {
                foreach ($itemContents as $key => $bannerGroupItem) {
                    $bannerGroup = app('xe.banner')->createGroup([
                        'title' => $bannerGroupItem['title'],
                        'skin' => $bannerGroupItem['skin']
                    ]);

                    $this->menuItemIds[$key] = $bannerGroup->id;
                }
            }
        }
    }

    protected function storeBannerData($contents)
    {
        foreach ($contents as $group => $itemContents) {
            if (isset($this->menuItemIds[$group]) == false) {
                continue;
            }

            $groupId = $this->menuItemIds[$group];
            $banner = app('xe.banner')->getGroup($groupId);

            if ($banner == null) {
                continue;
            }

            foreach ($itemContents as $content) {
                app('xe.banner')->createItem($banner, $content);
            }
        }
    }

    protected function storeWidgetpageData($widgetpageContents)
    {
        foreach ($widgetpageContents as $key => $content) {
            if (array_key_exists($key, $this->menuItemIds) == false) {
                continue;
            }

            $widgetpageId = $this->menuItemIds[$key];
            $widgetpageContents = $this->contentsValueReplace($content);

            $this->updateWidgetpageContents($widgetpageId, $widgetpageContents);
        }
    }

    protected function storeBoardData($boardContents)
    {
        $boardHandler = app('xe.board.handler');

        foreach ($boardContents as $key => $contents) {
            if (array_key_exists($key, $this->menuItemIds) == false) {
                continue;
            }

            $boardId = $this->menuItemIds[$key];
            $config = app('xe.board.config')->get($boardId);

            foreach ($contents as $content) {
                $content['instance_id'] = $boardId;
                $boardHandler->add($content, \Auth::user(), $config);
            }
        }
    }

    protected function updateWidgetpageContents($widgetpageId, $contents)
    {
        app('xe.widgetbox')->update('widgetpage-' . $widgetpageId, ['content' => $contents]);
    }

    public function getUrl($prefix)
    {
        $prefix = strtolower($prefix);

        for ($i = 0; $i <= 1000; $i++) {
            $url = $prefix . rand(1, 20000);

            if (InstanceRoute::where('url', $url)->count() == 0) {
                return $url;
            }
        }
    }

    public function contentsValueReplace(&$contents)
    {
        foreach ($contents as $key => $content) {
            if (is_array($content) == true) {
                $content = $this->contentsValueReplace($content);

                $contents[$key] = $content;
            } else {
                if (array_key_exists($content, $this->menuItemIds) == true) {
                    $contents[$key] = $this->menuItemIds[$content];
                }
            }
        }

        return $contents;
    }
}
