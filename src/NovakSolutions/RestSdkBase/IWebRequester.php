<?php
/**
 * Created by PhpStorm.
 * User: joey
 * Date: 7/23/18
 * Time: 9:30 PM
 */

namespace NovakSolutions\RestSdkBase;

interface IWebRequester{
    /**
     * @param $endPoint
     * @param $requestVerb
     * @param $payload
     * @return WebRequestResult
     */
    public function request($endPoint, $requestVerb, $payload);
}