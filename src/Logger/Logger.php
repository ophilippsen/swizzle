<?php

namespace Loco\Utils\Swizzle\Logger;

use Monolog\Handler\StreamHandler;

class Logger extends \Monolog\Logger {
    public function __construct($name, array $handlers = array(), array $processors = array()) {
        parent::__construct($name, $handlers, $processors);

        // if we don't add a handler we get debug messages by default.
        $this->pushHandler( new StreamHandler('php://stderr', static::ERROR ) );
    }

    /**
     * @internal Log debug events in verbose mode
     */
    public function writeDebug( $message ){
        if( 1 < func_num_args() ){
            $message = call_user_func_array( 'sprintf', func_get_args() );
        }
        $this->addDebug( $message );
    }

    /**
     * Enable debug logging to show build progress
     * @param string|resource
     * @return Logger
     */
    public function verbose( $resource ){
        $this->pushHandler( new StreamHandler( $resource, static::DEBUG ) );
    }


} 