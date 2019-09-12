<?php
/**
 * Created by PhpStorm.
 * User: joey
 * Date: 7/23/18
 * Time: 9:12 PM
 */

namespace NovakSolutions\RestSdkBase\Service\Traits;

use NovakSolutions\RestSdkBase\AssociativeArrayToApiModel;
use NovakSolutions\RestSdkBase\Exception\FindException;
use NovakSolutions\RestSdkBase\Exception\BadRequestException;
use NovakSolutions\RestSdkBase\Exception\UnAuthorizedException;
use NovakSolutions\RestSdkBase\Exception\UnknownResponseException;
use NovakSolutions\RestSdkBase\Model\Model;
use NovakSolutions\RestSdkBase\Registry;
use NovakSolutions\RestSdkBase\WebRequesterRegistry;
use NovakSolutions\RestSdkBase\WebRequestResult;

trait CreateTrait
{
    /**
     * @param array $criteria
     * @param null $orderBy
     * @param null $ascendingOrDescending
     * @param null $limit
     * @param null $offset
     * @return Model[]
     * @throws \ReflectionException
     * @throws \Exception
     */

    public static function create(&$data, $reloadFromResponse = true, $key = null){
        if($key == null){
            $key = static::$webRequesterRegistryKeyPrefix . '-default';
        }
        //Replace question mark(s) in url with criteria if it's present
        $url = static::$endPoint;

        //Make Call...
        $mapper = new \JsonMapper();
        /** @var WebRequestResult $result */

        $jsonBody = json_encode($data, JSON_PRETTY_PRINT);
        $result = WebRequesterRegistry::getWebRequesterForKey($key)->request($url, 'POST', $jsonBody);

        self::throwExceptionIfError($result);

        //Interperet Response
        if($reloadFromResponse) {
            $mapper->map(json_decode($result->body, false), $data);
        }
    }
}