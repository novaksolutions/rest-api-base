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
    public function save(){
        $serviceClassName = static::$serviceClassName;
        $primaryKeyFieldName = static::$primaryKeyFieldName;
        if(static::$primaryKeyFieldName != null && $this->$primaryKeyFieldName != null){
            $serviceClassName::update($this);
            return $this;
        } else {
            $result = $serviceClassName::create($this);
            return $result;
        }
    }
}