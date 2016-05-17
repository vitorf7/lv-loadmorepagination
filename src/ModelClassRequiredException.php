<?php

namespace VitorF7\LoadMorePagination;

/**
* ModelClassRequiredException
*
* @author Vitor Faiante
*/
class ModelClassRequiredException extends \Exception
{
    // Redefine the exception so message isn't optional
    public function __construct($message = null, $code = null, Exception $previous = null)
    {
        $message = $message ?: 'Please make sure you either provide an Eloquent Model Class as the 3rd argument or use this inside an Eloquent Model';
        $code    = $code ?: 403;

        // make sure everything is assigned properly
        parent::__construct($message, $code, $previous);
    }

    // custom string representation of object
    public function __toString()
    {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }
}
