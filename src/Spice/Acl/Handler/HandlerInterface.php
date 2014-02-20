<?php

/**
 * Definition of Spice\Acl\HandlerInterface.
 * 
 * @author Henrique Barcelos <rick.hjpbarcelos@gmail.com
 */
namespace Spice\Acl\Handler;

use Spice\Acl\DeniedAccessException;
use Spice\Acl\Role\RoleInterface;

/**
 * Interface for ACL handlers.
 */
interface HandlerInterface {
    /**
     * Adds a role to the handler.
     *
     * @param \Spice\Acl\Role\RoleInterface $role
     *            
     * @return void
     */
    public function addRole(RoleInterface $role);
    
    /**
     * Verifies if a role exists within the handler.
     * 
     * @param string $roleName The role name;
     * 
     * @return boolean
     */
    public function hasRole($roleName);
    
    /**
     * Retrives all handled roles.
     * 
     * @return array of \Spice\Acl\Role\RoleInterface
     */
    public function getRoles();
    
    /**
     * Adds a resource that will be access controlled.
     *
     * @param string $resourceName            
     *
     * @return void
     */
    public function addResource($resourceName);
    
    /**
     * Verifies if there is a resource registered within the handler.
     * 
     * @param string $resourceName
     * 
     * @return boolean
     */
    public function hasResource($resourceName);
    
    /**
     * Removes a resource from the list.
     *
     * @param string $resourceName            
     *
     * @return void
     */
    public function removeResource($resourceName);
    
    /**
     * Retrives the handled resources.
     * 
     * @return array of string
     */
    public function getResources();
    
    /**
     * Allows a role to access a resource.
     *
     * @param string $roleName
     *            The name of the role.
     * @param unknown $resourceName
     *            The name of the resource.
     *            
     * @return void
     * 
     * @throws \InvalidParameterException If there is no such role
     *          as `$roleName`
     * @throws \InvalidParameterException If there is no such resource
     *          as `$resourceName`
     */
    public function allow($roleName, $resourceName);
    
    /**
     * Denies a role from accessing a resource.
     *
     * @param string $roleName            
     * @param string $resourceName            
     *
     * @return void
     * 
     * @throws \InvalidParameterException If there is no such role
     *          as `$roleName`
     * @throws \InvalidParameterException If there is no such resource
     *          as `$resourceName`
     */
    public function deny($roleName, $resourceName);
    
    /**
     * Check if a role has privileges to access a resource.
     *
     * @param string $roleName            
     * @param string $resourceName            
     *
     * @return void
     *
     * @throws \Spice\Acl\DeniedAccessException If the role
     *         does not have access to the resource.
     * @throws \InvalidParameterException If there is no such role
     *          as `$roleName`
     * @throws \InvalidParameterException If there is no such resource
     *          as `$resourceName`
     */
    public function check($roleName, $resourceName);
}