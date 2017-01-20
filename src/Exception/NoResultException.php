<?php

namespace peterrehm\TimeProfiles\Exception;

class NoResultException extends \Exception
{
    /**
     * @var array
     */
    private $exceptions;

    /**
     * @param string $message
     * @param array  $exceptions Array of Exception instances
     */
    public function __construct($message = '', array $exceptions = [])
    {
        parent::__construct($message);
        $this->exceptions = $exceptions;
    }

    /**
     * @return array
     */
    public function getExceptions()
    {
        return $this->exceptions;
    }
}
