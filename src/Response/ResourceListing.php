<?php
namespace Loco\Utils\Swizzle\Response;

/**
 * Response class for Swagger resource listing
 */
class ResourceListing extends BaseResponse {

    /**
     * Get info field, comprising title and description
     * @return array
     */
    public function getInfo(){
        $defaults = array( 'title' => '', 'description' => '' );
        $info = $this->get('info')?:array();
        return array_intersect_key( $info, $defaults ) + $defaults;
    }     
    
    
}