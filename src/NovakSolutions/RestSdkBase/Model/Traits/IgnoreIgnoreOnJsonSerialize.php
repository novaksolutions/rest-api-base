<?php


namespace NovakSolutions\RestSdkBase\Model\Traits;

trait IgnoreIgnoreOnJsonSerialize
{
    public function jsonSerialize(){
        $data = (array) $this;
        foreach($data as $index => $value){
            try {
                $reflectionProperty = new \ReflectionProperty(static::class, $index);
                $docBlock = $reflectionProperty->getDocComment();
                $annotations = static::parseAnnotations($docBlock);
                if(isset($annotations['ignoreOnJsonSerialize'])){
                    unset($data[$index]);
                }
            } catch (\ReflectionException $e) {
                //Do nothing...
            }
        }

        return $data;
    }

    private static function parseAnnotations($docblock)
    {
        $annotations = array();
        // Strip away the docblock header and footer
        // to ease parsing of one line annotations
        $docblock = substr($docblock, 3, -2);

        $re = '/@(?P<name>[A-Za-z_-]+)(?:[ \t]+(?P<value>.*?))?[ \t]*\r?$/m';
        if (preg_match_all($re, $docblock, $matches)) {
            $numMatches = count($matches[0]);

            for ($i = 0; $i < $numMatches; ++$i) {
                $annotations[$matches['name'][$i]][] = $matches['value'][$i];
            }
        }

        return $annotations;
    }
}