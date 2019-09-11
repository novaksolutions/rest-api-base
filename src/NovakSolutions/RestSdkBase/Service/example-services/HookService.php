<?php
/**
 * Created by PhpStorm.
 * User: joey
 * Date: 7/23/18
 * Time: 7:31 PM
 */

namespace NovakSolutions\RestSdkBase\Service;

use NovakSolutions\RestSdkBase\Model\Hook;

class HookService extends Service
{
    use Traits\ListTrait;
    use Traits\RetrieveTrait;
    use Traits\CreateTrait;

    public static $arrayKey = null;
    public static $class = Hook::class;
    public static $endPoint = '/hooks';
}