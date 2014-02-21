<?php
/**
 * Boilerplate implementation of DecoratorInterface.
 * @author Henrique Barcelos <rick.hjpbarcelos@gmail.com>
 */
namespace Spice\Acl\Handler\Decorator;

use Spice\Acl\Handler\Decorator\DecoratorInterface;
use Spice\Acl\Handler\HandlerInterface;
use Spice\Acl\Role\RoleInterface;

/**
 * Boilerplate implementation of DecoratorInterface.
 */
abstract class AbstractDecorator implements DecoratorInterface {
    /**
     * @var \Spice\Acl\Handler\HandlerInterface
     */
    private $actualHandler;
    
    /**
     * @param HandlerInterface $handler
     */
    public function __construct(HandlerInterface $handler) {
        $this->actualHandler = $handler;
    }
    
    /**
     * (non-PHPdoc) 
     * @see \Spice\Acl\Handler\HandlerInterface::hasRole()
     */
    public function hasRole($roleName) {
        return $this->actualHandler->hasRole($roleName);
    }
    
    /**
     * (non-PHPdoc) 
     * @see \Spice\Acl\Handler\HandlerInterface::getResources()
     */
    public function getResources() {
        return $this->actualHandler->getResources();
    }
    
    /**
     * (non-PHPdoc) 
     * @see \Spice\Acl\Handler\HandlerInterface::check()
     */
    public function check($roleName, $resourceName) {
        $this->actualHandler->check($roleName, $resourceName);
    }
    
    /**
     * (non-PHPdoc) 
     * @see \Spice\Acl\Handler\HandlerInterface::allow()
     */
    public function allow($roleName, $resourceName) {
        $this->actualHandler->allow($roleName, $resourceName);
    }
    
    /**
     * (non-PHPdoc) 
     * @see \Spice\Acl\Handler\HandlerInterface::removeResource()
     */
    public function removeResource($resourceName) {
        $this->actualHandler->removeResource($resourceName);
    }
    
    /**
     * (non-PHPdoc) 
     * @see \Spice\Acl\Handler\HandlerInterface::deny()
     */
    public function deny($roleName, $resourceName) {
        $this->actualHandler->deny($roleName, $resourceName);
    }
    
    /**
     * (non-PHPdoc) 
     * @see \Spice\Acl\Handler\HandlerInterface::hasResource()
     */
    public function hasResource($resourceName) {
        return $this->actualHandler->hasResource($resourceName);
    }
    
    /**
     * (non-PHPdoc) 
     * @see \Spice\Acl\Handler\Decorator\DecoratorInterface::getActualHandler()
     */
    public function getActualHandler() {
        return $this->actualHandler;
    }
    
    /**
     * (non-PHPdoc) @see \Spice\Acl\Handler\HandlerInterface::addRole()
     */
    public function addRole(RoleInterface $role) {
        $this->actualHandler->addRole($role);
    }
    
    /**
     * (non-PHPdoc) @see \Spice\Acl\Handler\HandlerInterface::addResource()
     */
    public function addResource($resourceName) {
        $this->actualHandler->addResource($resourceName);
    }
    
    /**
     * (non-PHPdoc) @see \Spice\Acl\Handler\HandlerInterface::getRoles()
     */
    public function getRoles() {
        $this->actualHandler->getRoles();
    }
}