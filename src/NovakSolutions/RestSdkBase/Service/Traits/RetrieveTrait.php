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

trait RetrieveTrait
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
     */

    public static function get($id){
        //Build Request
        $parameters = [];

        //Replace question mark(s) in url with criteria if it's present
        $url = static::$endPoint . '/' . $id;

        //Make Call...
        /** @var WebRequestResult $result */
        $result = Registry::$WebRequester->request($url, 'GET', $parameters, null);

        $objects = null;
        switch($result->responseCode){
            case 596:
                throw new BadRequestException("Unknown Service");
            case 401:
                throw new UnAuthorizedException("Got 401 response from Infusionsoft during call to " . static::$endPoint);
            case 400:
                throw new BadRequestException("Got Bad Request Exception");
                break;
            case 200:
                break;
            default:
                throw new UnknownResponseException("Got a response I don't know what to do with: " . $result->responseCode);
        }

        //Interperet Response
        $objects = json_decode($result->body, true);

        $result = new static::$class($objects);

        return $result;
    }
}