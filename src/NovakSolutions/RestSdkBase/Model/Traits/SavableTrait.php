<?php
/**
 * Created by PhpStorm.
 * User: joey
 * Date: 7/27/18
 * Time: 11:27 AM
 */
namespace NovakSolutions\RestSdkBase\Model\Traits;

trait SavableTrait
{
    public function save($reloadFromReturn = true){
        $serviceClassName = static::$serviceClassName;
        $primaryKeyFieldName = static::$primaryKeyFieldName;
        if($this->$primaryKeyFieldName != null){
            $serviceClassName::update($this->toArray());
        } else {
            $result = $serviceClassName::create($this->toArray());
        }

        if($reloadFromReturn) {
            $this->fromArray($result->toArray());
        }
    }
}