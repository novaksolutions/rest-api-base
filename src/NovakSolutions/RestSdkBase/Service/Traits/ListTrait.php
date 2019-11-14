<?php
/**
 * Created by PhpStorm.
 * User: joey
 * Date: 7/23/18
 * Time: 9:12 PM
 */

namespace NovakSolutions\RestSdkBase\Service\Traits;

use JsonMapper;
use NovakSolutions\RestSdkBase\AssociativeArrayToApiModel;
use NovakSolutions\RestSdkBase\Exception\FindException;
use NovakSolutions\RestSdkBase\Exception\BadRequestException;
use NovakSolutions\RestSdkBase\Exception\NotFoundException;
use NovakSolutions\RestSdkBase\Exception\UnAuthorizedException;
use NovakSolutions\RestSdkBase\Exception\UnknownResponseException;
use NovakSolutions\RestSdkBase\IWebRequester;
use NovakSolutions\RestSdkBase\Model\Model;
use NovakSolutions\RestSdkBase\WebRequesterRegistry;
use NovakSolutions\RestSdkBase\WebRequestResult;

trait ListTrait
{
    /**
     * @param array $criteria
     * @param null $orderBy
     * @param null $ascendingOrDescending
     * @param null $limit
     * @param null $offset
     * @return Model[]
     * @throws \NovakSolutions\RestBaseSdk\Exception\FindException
     * @throws \ReflectionException
     * @throws \Exception
     */

    public static function find(array $criteria = [], $limit = null, $offset = null, $key = 'default'){
        //Build Request
        $parameters = [];

        //Replace question mark(s) in url with criteria if it's present
        $url = static::$endPoint;
        if(property_exists(static::class, 'parameterToReplaceQuestionMark') && isset($criteria[static::$parameterToReplaceQuestionMark])){
            $url = str_replace("?", $criteria[static::$parameterToReplaceQuestionMark], $url);
            unset($criteria[static::$parameterToReplaceQuestionMark]);
        }

        foreach($criteria as $criteriaFieldName => $criterion){
            if(!in_array($criteriaFieldName, static::$findByFields)){
                throw new FindException("Invalid field name: " . $criteriaFieldName . ' in service ' . (new \ReflectionClass(self))->getShortName());
            }
            $parameters[$criteriaFieldName] = $criterion;
        }

        if($limit != null){
            $parameters['limit'] = $limit;
        }

        if($offset != null){
            $parameters['offset'] = $offset;
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
            case 404:
                throw new NotFoundException("Url: " . $url . " Not Found");
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
        $response = $jsonMapper->mapArray($responseAsStdClassObjectTree->results, [], static::$class);

//        if(static::$arrayKey != null){
//            $objects = $response[static::$arrayKey];
//        } else {
//            $objects = $response;
//        }
//
//        foreach($objects as $objectAsArray){
//            $results[] = new static::$class($objectAsArray);
//        }

        return $response;
    }
}