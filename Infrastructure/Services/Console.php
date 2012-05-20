<?php
namespace Infrastructure\Services;
/**
 * @author Brian Scaturro
 */
class Console
{
    public function writeLine($text)
    {
        return fwrite(STDOUT,$text . "\n");
    }

    /**
     * @param $input a string of user input
     * @param $actions an array of string,callable key value pairs
     */
    public function input($input, $actions)
    {
        $default = @$actions['default'] ?: function($c) {};

        array_walk($actions,function($item){
            if(!is_callable($item)) {
                throw new \InvalidArgumentException("All actions must be callable");
            }
        });

        if(isset($actions[$input]))
            return call_user_func_array($actions[$input],array($this));

        return call_user_func_array($default,array($this));
    }
}
