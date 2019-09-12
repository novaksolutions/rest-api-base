<?php
/**
 * Created by PhpStorm.
 * User: joey
 * Date: 7/26/18
 * Time: 12:34 PM
 */

namespace NovakSolutions\RestSdkBase\Model;


use NovakSolutions\RestSdkBase\Exception\InvalidFieldException;
//use function PHPSTORM_META\type;

class Model
{
    protected static $primaryKeyFieldName = 'id';
    protected static $serviceClassName = null;

    public function __construct($key = null)
    {

    }
}