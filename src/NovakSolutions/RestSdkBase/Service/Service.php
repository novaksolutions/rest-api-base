<?php
/**
 * Created by PhpStorm.
 * User: joey
 * Date: 7/23/18
 * Time: 9:10 PM
 */

namespace NovakSolutions\RestSdkBase\Service;


use NovakSolutions\RestSdkBase\Exception\BadRequestException;
use NovakSolutions\RestSdkBase\Exception\UnAuthorizedException;
use NovakSolutions\RestSdkBase\Exception\UnknownResponseException;
use NovakSolutions\RestSdkBase\WebRequestResult;

class Service
{
    /**
     * @param WebRequestResult $result
     * @throws BadRequestException
     * @throws UnAuthorizedException
     * @throws UnknownResponseException
     */
    public static function throwExceptionIfError($result)
    {
        switch ($result->responseCode) {
            case 596:
                throw new BadRequestException("Unknown Service");
            case 401:
                throw new UnAuthorizedException("Got 401 response during call to " . static::$endPoint);
            case 400:
                throw new BadRequestException("Got 400 Bad Request Response\n" . $result->body);
                break;
            case 200:
            case 201:
                break;
            default:
                throw new UnknownResponseException("Got a response I don't know what to do with: " . $result->responseCode);
        }
    }
}