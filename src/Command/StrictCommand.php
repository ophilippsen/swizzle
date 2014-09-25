<?php

namespace Loco\Utils\Swizzle\Command;

use GuzzleHttp\Command\Model;
use GuzzleHttp\Command\Guzzle\Operation as OperationCommand;
use GuzzleHttp\Command\Guzzle\SchemaValidator;

/**
 * Operation command that validates response models.
 */
class StrictCommand extends OperationCommand {
    
    
    /**
     * Validate response model after processing
     * @throws ValidationException
     * @override
     */
    protected function process(){
        parent::process();
        if( $this[ AbstractCommand::DISABLE_VALIDATION] ){
            // skipping validation in all cases
            return;
        }
        if( ! $this->result instanceof Model ){
            // result is not a model - no way to validate
            return; 
        }
        $errors = array();
        $validator = SchemaValidator::getInstance();
        // validate parameters present
        $schema = $this->result->getStructure();
        $value = $this->result->toArray();
        if( ! $validator->validate( $schema, $value ) ){
            $errors = $validator->getErrors();
        }
        // @todo validate additional parameters?
        // $schema->getAdditionalProperties() );
        if( $errors ){
            $e = new ValidationException('Response failed model validation: ' . implode("\n", $errors) );
            $e->setErrors( $errors );
            throw $e;
        }
    }        
    
    
    
    /**
     * Get the overridden response parser used for the schema-aware operation
     * @override
     * @return ResponseParserInterface
     */
    public function getResponseParser(){
        if( ! $this->responseParser ) {
            // Use our overridden response parser that injects models into schemas
            $this->responseParser = StrictResponseParser::getInstance();
        }
        return $this->responseParser;
    }    
    
    
    
}