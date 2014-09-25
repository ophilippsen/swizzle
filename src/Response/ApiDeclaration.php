<?php
namespace Loco\Utils\Swizzle\Response;

use Loco\Utils\Swizzle\ModelCollection;

/**
 * Response class for Swagger API declaration
 */
class ApiDeclaration extends BaseResponse {

    /**
     * Get basePath sepcified outside of api operations
     * @return string
     */
    public function getBasePath(){
        return $this->get('basePath')?:'';
    }
    
    
    /**
     * Get resourcePath sepcified outside of api operations
     * @return string
     */
    public function getResourcePath(){
        return $this->get('resourcePath')?:'';
    }
    

    
    /**
     * Get model definitions
     * @return ModelCollection
     */
    public function getModels(){
        $models = $this->get('models')?:array();
        return new ModelCollection( $models );
    }
    
}