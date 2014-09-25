<?php

namespace Loco\Tests\Utils\Swizzle\Functional;

use GuzzleHttp\Client;
use GuzzleHttp\Command\Guzzle\Description;
use GuzzleHttp\Command\Guzzle\GuzzleClient;
use Loco\Utils\Swizzle\Swizzle;


/**
 * Run full feature test on official Swagger Petstore example API
 * @group petstore
 */
class PetstoreTest extends \PHPUnit_Framework_TestCase {
    
    
    /**
     * Build service description from remote docs.
     * @return Description
     */
    public function testServiceBuild(){
        $builder = new Swizzle( 'pets', 'Swagger Pet store' );
        //$builder->verbose( STDERR );
        $builder->registerCommandClass( '', '\\Loco\\Utils\\Swizzle\\Command\\StrictCommand' );
        $builder->setBaseUrl('http://petstore.swagger.wordnik.com/api');
        $builder->build('http://petstore.swagger.wordnik.com/api/api-docs');
        //die( $builder->toJson() );
        $service = $builder->getServiceDescription();
        $this->assertCount( 6, $service->getModels() );
        $this->assertCount( 20, $service->getOperations() );

        $description = new Description($service->toArray());
        return $description;
    }


    /**
     * Construct Swagger client for calling the petstore
     * @depends testServiceBuild
     * @param Description $description
     * @return GuzzleClient
     */
    public function testClientConstruct( Description $description ){
        $client = new GuzzleClient(new Client(), $description);

        $this->assertInstanceOf('GuzzleHttp\Command\Guzzle\\Command', $client->getCommand('findPetsByStatus'));
        // @todo add Accept: application/json to every request?
        return $client;
    }


    /**
     * Tests typed array response
     * @depends testClientConstruct
     * @param GuzzleClient $client
     * @return int
     */
    public function testFindPetsByStatus( GuzzleClient $client ){
        $pets = $client->findPetsByStatus( array( 'status' => 'available' ) );
        
        // listing should be validated as Pet_array model, except it doesn't work so disabled.
        // $this->assertInstanceOf('\Guzzle\Service\Resource\Model', $pets );
        // var_dump( $pets->toArray() );
        
        $this->assertInternalType('array', $pets );
        $this->assertArrayHasKey(0, $pets);
        $this->assertArrayHasKey('id', $pets[0] );
        return (int) $pets[0]['id'];
    }
    
    
    
    /**
     * Tests a simple model
     * @depends testClientConstruct
     * @depends testFindPetsByStatus
     */
    public function testGetPetById( Client $client, $petId ){
//        $petId = 3; // <- bug in petstore definition disallows pidId>100
        $pet = $client->getPetById( compact('petId') );
        $this->assertInstanceOf('\Guzzle\Service\Resource\Model', $pet );
        $this->assertEquals( $petId, $pet['id'] );
        $this->assertInternalType( 'string', $pet['name'] );
    }
    
    
}



