<?php
/**
 * PHP-DI
 *
 * @link      http://mnapoli.github.io/PHP-DI/
 * @copyright Matthieu Napoli (http://mnapoli.fr/)
 * @license   http://www.opensource.org/licenses/mit-license.php MIT (see the LICENSE file)
 */

namespace DI\Definition\Helper;

use DI\Definition\ClassDefinition;
use DI\Definition\MethodInjection;
use DI\Definition\ParameterInjection;
use DI\Definition\PropertyInjection;
use DI\Scope;

/**
 * Help to create a ClassDefinition
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class ClassDefinitionHelper
{

    /**
     * @var ClassDefinition
     */
    private $classDefinition;

    /**
     * @param string $entryName
     */
    public function __construct($entryName)
    {
        $this->classDefinition = new ClassDefinition($entryName);
    }

    /**
     * Bind the entry to a class
     * @param string $className
     * @return $this
     */
    public function bindTo($className)
    {
        $this->classDefinition->setClassName($className);
        return $this;
    }

    /**
     * Set the scope for the class
     * @param Scope $scope
     * @return $this
     */
    public function withScope(Scope $scope)
    {
        $this->classDefinition->setScope($scope);
        return $this;
    }

    /**
     * Define a property injection
     * @param string $propertyName Property name
     * @param string $entryToInject Name of the entry that should be injected in the property
     * @param bool   $lazy If the injected object should be a proxy for lazy-loading
     * @return $this
     */
    public function withProperty($propertyName, $entryToInject, $lazy = false)
    {
        $this->classDefinition->addPropertyInjection(new PropertyInjection($propertyName, $entryToInject, $lazy));
        return $this;
    }

    /**
     * Injections using the constructor
     * @param string[] $params Parameters for the constructor: array of container entries names
     * @return $this
     */
    public function withConstructor(array $params)
    {
        $paramInjections = array();
        foreach ($params as $key => $param) {
            $paramInjections[] = new ParameterInjection($key, $param);
        }
        $this->classDefinition->setConstructorInjection(new MethodInjection('__construct', $paramInjections));
        return $this;
    }

    /**
     * Injections by calling a method of the class
     * @param string[] $params Parameters for the method: array of container entries names
     * @return $this
     */
    public function withMethod($methodName, array $params)
    {
        $paramInjections = array();
        foreach ($params as $key => $param) {
            $paramInjections[] = new ParameterInjection($key, $param);
        }
        $this->classDefinition->addMethodInjection(new MethodInjection($methodName, $paramInjections));
        return $this;
    }

    /**
     * @return ClassDefinition
     */
    public function getDefinition()
    {
        return $this->classDefinition;
    }

}
