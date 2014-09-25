<?php

namespace Loco\Utils\Swizzle;

use GuzzleHttp\Client;
use GuzzleHttp\Collection;
use GuzzleHttp\Command\Guzzle\Description;
use GuzzleHttp\Command\Guzzle\GuzzleClient;

/**
 * Client for pulling Swagger docs
 * 
 * @method array getResources
 * @method array getDeclaration
 */
class SwaggerClient extends GuzzleClient {

    /**
     * Factory method to create a new Swagger Docs client.
     * @param array|Collection $config Configuration data
     * @return SwaggerClient
     */
    public static function factory( $config = array() ){
       
        // no default base url, but must be passed
        $default = array();
        $required = array('base_url');

        // Merge in default settings and validate the config
        $config = Collection::fromConfig( $config, $default, $required );


        $client = new Client($config->toArray());

        $serviceDescription = \GuzzleHttp\json_decode(file_get_contents(__DIR__.'/Resources/service.json'), TRUE);
        $serviceDescription['baseUrl'] = rtrim($config->get('base_url'), '/') . '/';
        $description = new Description($serviceDescription);

        $guzzleClient = new self($client, $description, $config->toArray());

        // @TODO: Set User-Agent

        return $guzzleClient;
    }

}

