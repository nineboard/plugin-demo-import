<?php
/**
 * ExistThemeNameException.php
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

namespace Xpressengine\Plugins\DemoImport\Exceptions;

use Xpressengine\Support\Exceptions\HttpXpressengineException;

/**
 * Class ExistThemeNameException
 *
 * @category    DemoImport
 * @package     Xpressengine\Plugins\DemoImport
 * @author      XE Developers <developers@xpressengine.com>
 * @copyright   2019 Copyright XEHub Corp. <https://www.xehub.io>
 * @license     http://www.gnu.org/licenses/lgpl-3.0-standalone.html LGPL
 * @link        https://xpressengine.io
 */
class ExistThemeNameException extends HttpXpressengineException
{
    protected $message = '같은 이름을 사용중인 테마가 있습니다.';
}
