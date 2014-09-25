<?php
namespace Loco\Utils\Swizzle;

use GuzzleHttp\Command\Guzzle\Description;
use GuzzleHttp\Command\Guzzle\Operation;
use GuzzleHttp\Command\Guzzle\Parameter;
use GuzzleHttp\ToArrayInterface;
use GuzzleHttp\Url;

/**
 * object oriented creation of a Description
 *
 * Tightly modeled after Guzzle3's ServiceDescription.
 * Lots of features where dropped in {@see \GuzzleHttp\Command\Guzzle\Description}.
 */
class DescriptionBuilder extends Description implements ToArrayInterface {

    /**
     * array of { @see \GuzzleHttp\Command\Guzzle\Parameter }
     *
     * @var array
     */
//    protected $models = array();

    /**
     * array of { @see \GuzzleHttp\Command\Guzzle }
     *
     * @var array
     */
//    protected $operations = array();

    /**
     * @param array $models
     */
    public function setModels($models) {
        foreach($models as $model) {
            $this->setModels($model);
        }
    }

    /**
     * @param Parameter $model
     */
    public function addModel(Parameter $model) {
        $this->models[$model->getName()] = $model;
    }

    /**
     * @return array
     */
//    public function getModels() {
//        return $this->models;
//    }

    /**
     * @param string $id
     * @return bool
     */
//    public function hasModel($id) {
//        return array_key_exists($id, $this->models);
//    }

    /**
     * @param $name
     * @return Parameter
     */
//    public function getModel($name) {
//        if(!$this->hasModel($name)) {
//            throw new \InvalidArgumentException(sprintf(
//                'Model with id "%s" does not exist',
//                $name
//            ));
//        }
//        return $this->models[$name];
//    }

    /**
     * @param array $operations
     */
    public function setOperations($operations) {
        foreach($operations as $operation) {
            $this->addOperation($operation);
        }
    }

    /**
     * @param Operation $operation
     */
    public function addOperation(Operation $operation) {
        $this->operations[$operation->getName()] = $operation;
    }

    /**
     * @return array
     */
//    public function getOperations() {
//        return $this->operations;
//    }

    /**
     * @param $name
     * @return bool
     */
//    public function hasOperation($name) {
//        return array_key_exists($name, $this->operations);
//    }

    /**
     * @param $name
     * @return mixed
     */
//    public function getOperation($name) {
//        if(!$this->hasOperation($name)) {
//            throw new \InvalidArgumentException(sprintf(
//                'Operation with name "%s" does not exist',
//                $name
//            ));
//        }
//        return $this->operations[$name];
//    }

    /**
     * Get the array representation of an object
     *
     * @return array
     */
    public function toArray() {
        $result = array(
            'name'        => $this->getName(),
            'apiVersion'  => $this->getApiVersion(),
            'baseUrl'     => $this->getBaseUrl(),
            'description' => $this->getDescription()
        );

        $result['operations'] = [];
        foreach($this->getOperations() as $name => $operation) {
            /** @var $operation Operation */
            $result['operations'][$operation->getName() ?: $name] = $operation->toArray();
        }

        $result['models'] = [];
        foreach($this->getModels() as $id => $model) {
            /** @var $model Parameter */
            $result['models'][$id] = $model instanceof Parameter ? $model->toArray() : $model;
        }

        return $result;
    }

    /**
     * @param string $baseUrl
     */
    public function setBaseUrl($baseUrl = '') {
        $this->baseUrl = Url::fromString($baseUrl ?: '');
    }
}