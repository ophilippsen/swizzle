<?php

namespace Loco\Utils\Swizzle\Command;

/**
 * Response parser that enables schema to be injected into response models.
 */
class StrictResponseParser extends OperationResponseParser {

    /** 
     * Singleton
     * @var StrictResponseParser
     */
    protected static $instance;
    
    /**
     * Get singleton
     * @return StrictResponseParser
     */
    public static function getInstance(){
        if( ! static::$instance ) {
            static::$instance = new StrictResponseParser( VisitorFlyweight::getInstance(), true );
        }
        return static::$instance;
    }
    
    
}
