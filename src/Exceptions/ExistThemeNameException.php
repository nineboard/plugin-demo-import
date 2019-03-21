<?php

namespace Xpressengine\Plugins\DemoImport\Exceptions;

use Xpressengine\Support\Exceptions\HttpXpressengineException;

class ExistThemeNameException extends HttpXpressengineException
{
    protected $message = '같은 이름을 사용중인 테마가 있습니다.';
}
