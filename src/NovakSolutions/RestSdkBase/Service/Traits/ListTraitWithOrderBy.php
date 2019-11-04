<?php
/**
 * Created by PhpStorm.
 * User: joey
 * Date: 7/23/18
 * Time: 9:12 PM
 */

namespace NovakSolutions\RestSdkBase\Service\Traits;

use NovakSolutions\RestSdkBase\Exception\FindException;
use NovakSolutions\RestSdkBase\Exception\BadRequestException;
use NovakSolutions\RestSdkBase\Exception\UnAuthorizedException;
use NovakSolutions\RestSdkBase\Exception\UnknownResponseException;
use NovakSolutions\RestSdkBase\Model\Model;
use NovakSolutions\RestSdkBase\WebRequesterRegistry;
use NovakSolutions\RestSdkBase\WebRequestResult;

trait ListTraitWithOrderBy
{
    /**
     * @param array $criteria
     * @param null $orderBy
     * @param null $ascendingOrDescending
     * @param null $limit
     * @param null $offset
     * @return Model[]
     * @throws FindException
     * @throws \ReflectionException
     */

    public static function find(array $criteria = [], $orderBy = null, $ascendingOrDescending = null, $limit = null, $offset = null, $accessToken = null){
        //Build Request
        $parameters = [];
        foreach($criteria as $criteriaFieldName => $criterion){
            if(!in_array($criteriaFieldName, static::$findByFields)){
                throw new FindException("Invalid field name: " . $criteriaFieldName . ' in service ' . (new \ReflectionClass(self))->getShortName());
            }

            $parameters[$criteriaFieldName] = $criterion;
        }

        if($orderBy != null){
            $parameters['order'] = $orderBy;
            if($ascendingOrDescending == null) {
                $ascendingOrDescending = 'ascending';
            }
            if(!in_array($ascendingOrDescending, ['ascending', 'descending'])){
                throw new FindException("Invalid ascendingOrDescending value: " . $ascendingOrDescending . ' valid values are (ascending, descending)');
            }
            $parameters['order_direction'] = $ascendingOrDescending;
        }

        if($limit != null){
            $parameters['limit'] = $limit;
        }

        if($offset != null){
            $parameters['offset'] = $offset;
        }

        //Make Call...
        /** @var WebRequestResult $result */
        $result = WebRequesterRegistry::getWebRequesterForKey("default")->request(static::$endPoint, 'GET', $parameters, null);

        $objects = null;
        switch($result->responseCode){
            case 400:
                throw new BadRequestException("Got Bad Request Exception");
            case 401:
                throw new UnAuthorizedException("Got 401 response from Infusionsoft during call to " . static::$endPoint);
                break;
            case 200:
                break;
            default:
                throw new UnknownResponseException("Got a response I don't know what to do with: " . $result->responseCode);
        }

        //Interperet Response
        $objects = json_decode($result->body, true);
        $results = [];

        foreach($objects[static::$arrayKey] as $objectAsArray){
            $results[] = new static::$class($objectAsArray);
        }

        return $results;
    }
}