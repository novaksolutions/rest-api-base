<?php
/**
 * Created by PhpStorm.
 * User: joey
 * Date: 7/23/18
 * Time: 9:12 PM
 */

namespace NovakSolutions\RestSdkBase\Service\Traits;

use JsonMapper;

use NovakSolutions\RestSdkBase\Exception\BadRequestException;
use NovakSolutions\RestSdkBase\Exception\UnAuthorizedException;
use NovakSolutions\RestSdkBase\Exception\UnknownResponseException;
use NovakSolutions\RestSdkBase\IWebRequester;
use NovakSolutions\RestSdkBase\WebRequesterRegistry;
use NovakSolutions\RestSdkBase\WebRequestResult;

trait RetrieveTrait
{
    /**
     * @param $id
     * @param string $key
     * @return object
     * @throws BadRequestException
     * @throws UnAuthorizedException
     * @throws UnknownResponseException
     * @throws \JsonMapper_Exception
     * @throws \Exception
     */

    public static function get($id, $key = 'default'){
        //Build Request
        $parameters = [];

        //Replace question mark(s) in url with id if question mark is present

        if(strpos(static::$endPoint, "?") !== false){
            $url = str_replace("?",  $id, static::$endPoint);
        } else {
            $url = static::$endPoint . '/' . $id;
        }

//Make Call...
        /** @var IWebRequester $webRequester */
        $webRequester = WebRequesterRegistry::getWebRequesterForKey(static::$webRequesterRegistryKeyPrefix . '-' . $key);

        /** @var WebRequestResult $result */
        $result = $webRequester->request($url, 'GET', $parameters);

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
        $responseAsStdClassObjectTree = json_decode($result->body);
        $jsonMapper = new JsonMapper();
        $response = $jsonMapper->map($responseAsStdClassObjectTree, new static::$class);

        return $response;
    }
}