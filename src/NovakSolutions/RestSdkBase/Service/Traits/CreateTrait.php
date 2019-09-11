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
     * @throws \NovakSolutions\Exception\FindException
     * @throws \ReflectionException
     * @throws \NovakSolutions\Exception\RestException
     */

    public static function create(array $data, $accessToken = null){
        //Replace question mark(s) in url with criteria if it's present
        $url = static::$endPoint;

        //Make Call...
        /** @var WebRequestResult $result */
        $result = Registry::$WebRequester->request($url, 'POST', json_encode($data), $accessToken);

        self::throwExceptionIfError($result);

        //Interperet Response
        $data = json_decode($result->body, true);

        $object = new static::$class($data);

        return $object;
    }
}