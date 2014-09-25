<?php
namespace Loco\Utils\Swizzle\Response;

use GuzzleHttp\Stream\Stream;


/**
 * Base response class for Swagger docs resources
 */
abstract class BaseResponse {
    
    /**
     * Raw response data
     * @var array
     */
    protected $raw;

    /**
     * Create a response model object from a completed command
     * @internal
     * @param Stream $stream
     * @return ResourceListing
     */
    public static function fromCommand( Stream $stream ) {
        return new static( (string)$stream );
    }

    /**
     * Construct from http response
     * @internal
     * @param string $jsonString
     */
    final protected function __construct( $jsonString ) {
        $this->raw = \GuzzleHttp\json_decode($jsonString, TRUE);
    }
    
    
    /**
     * Test if key was found in original JSON, even if empty
     * @internal 
     * @param string
     * @return bool
     */
    protected function has( $key ){
        return isset($this->raw[$key]) || array_key_exists( $key, $this->raw );
    }
    
    
    /**
     * Get raw data value
     * @internal 
     * @return mixed
     */
    protected function get($key){
        return isset($this->raw[$key]) ? $this->raw[$key] : null;
    }    
    

    
    /**
     * Get declared API version number
     * @return string
     */
    public function getApiVersion(){
        return $this->get('apiVersion')?:'';
    }    
    

    
    /**
     * Get declared Swagger spec version number
     * @return string 
     */
    public function getSwaggerVersion(){
        return $this->get('swaggerVersion')?:'1.2';
    }    


    /**
     * Test if Swagger spec version number is declared. 
     * @return bool
     */
    public function isSwagger(){
        return $this->has('swaggerVersion');
    }    
    

    
    /**
     * Get all path strings in objects under apis:
     * @return array
     */   
    public function getApiPaths(){
        $paths = array();
        if( $apis = $this->get('apis') ){
            foreach( (array) $apis as $api ){
                if( isset($api['path']) ){
                    $paths[] = $api['path'];
                }
            }
        }
        return $paths;
    }    
    

    
    /**
     * Get api definitions
     * @return array
     */
    public function getApis(){
        return $this->get('apis')?:array();
    }

    
}


