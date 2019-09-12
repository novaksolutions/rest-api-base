<?php


namespace NovakSolutions\RestSdkBase;

class WebRequesterRegistry
{
    /**
     * @var IWebRequester[]
     */
    static $webRequesters = [];

    /**
     * @param $key
     * @param $baseUrl
     * @param null $webRequesterFullClassName
     * @return IWebRequester
     * @throws \Exception
     */
    public static function createAndRegisterWebRequester($key, $baseUrl, $webRequesterFullClassName = null){

        if(isset(static::$webRequesters[$key])){
            throw new \Exception("AuthToken: " . $key . " is already present, clear it first.");
        }


        if($webRequesterFullClassName != null){
            $webRequester = new $webRequesterFullClassName();
        } else {
            $webRequester = new SimpleWebRequester();
        }

        $webRequester->baseUrl = $baseUrl;
        static::$webRequesters[$key] = $webRequester;

        return $webRequester;
    }

    /**
     * @param $key
     */
    public static function unregisterWebRequester($key){
        if(isset(static::$webRequesters[$key])){
            unset(static::$webRequesters[$key]);
        }
    }

    /**
     * @param $key
     * @return IWebRequester
     * @throws \Exception
     */
    public static function getWebRequesterForKey($key){
        if(!isset(static::$webRequesters[$key])){
            throw new \Exception("No WebRequester for key: " . $key);
        }

        return static::$webRequesters[$key];
    }
}