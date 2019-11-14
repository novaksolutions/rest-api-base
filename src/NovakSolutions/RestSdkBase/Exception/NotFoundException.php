<?php


namespace NovakSolutions\RestSdkBase\Exception;

class NotFoundException extends RestException
{

    /**
     * NotFoundException constructor.
     * @param string $string
     */
    public function __construct(string $string)
    {
    }
}