<?php
/**
 * Created by PhpStorm.
 * User: joey
 * Date: 7/23/18
 * Time: 9:30 PM
 */

namespace NovakSolutions\RestSdkBase;

use NovakSolutions\RestSdkBase\Exception\RestException;

class SimpleWebRequester implements IWebRequester
{
    public $baseUrl = null;
    public $accessToken = null;

    public $tokenMethod = self::AUTHORIZATION_HEADER;
    /**
     * WebRequester constructor.
     */
    const AUTHORIZATION_HEADER = 'authorization_header';

    const GET_PARAMETER = 'get_parameter';

    public function getAccessToken($forceGetNewToken = false){
        return $this->accessToken;
    }

    public static function needsPostBody($requestVerb)
    {
        return in_array($requestVerb, ['POST', 'PUT', 'PATCH']);
    }

    /**
     * @param $endPoint
     * @param $requestVerb
     * @param $payload
     * @return WebRequestResult
     * @throws RestException
     */
    public function request($endPoint, $requestVerb, $payload){
        if(!in_array($requestVerb, ['GET', 'POST', 'PATCH', 'PUT', 'DELETE'])){
            throw new RestException('Invalid http verb - ' . $requestVerb);
        }

        $ch = curl_init();

        $url = $this->baseUrl . $endPoint;
        $urlParams = [];

        if($this->tokenMethod == self::GET_PARAMETER){
            $urlParams['access_token'] = $this->getAccessToken();
        }
        if(!self::needsPostBody($requestVerb)){
            $urlParams = array_merge($urlParams, $payload);
        }
        $url .= '?' . http_build_query($urlParams);

        curl_setopt($ch, CURLOPT_URL, $url);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $requestVerb);
        if(self::needsPostBody($requestVerb)){
            curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        }
        $headers = [
            'Accept: application/json, */*',
            'Content-Type: application/json'
        ];
        if($this->tokenMethod == '' . self::AUTHORIZATION_HEADER . ''){
            $headers[] = 'Authorization: Bearer ' . $this->getAccessToken();
        }

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $response = curl_exec($ch);


        $result = new WebRequestResult();
        $result->body = $response;
        $result->responseCode = curl_getinfo($ch, CURLINFO_RESPONSE_CODE);

        curl_close($ch);

        return $result;
    }
}