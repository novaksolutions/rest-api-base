<?php
/**
 * Created by PhpStorm.
 * User: joey
 * Date: 7/26/18
 * Time: 12:34 PM
 */

namespace NovakSolutions\RestSdkBase\Model;

use NovakSolutions\RestSdkBase\Model\Traits\IgnoreIgnoreOnJsonSerialize;

//use function PHPSTORM_META\type;

class Model implements \JsonSerializable
{
    use IgnoreIgnoreOnJsonSerialize;
    protected static $primaryKeyFieldName = 'id';
    protected static $serviceClassName = null;
    public function __construct($key = null)
    {

    }
}