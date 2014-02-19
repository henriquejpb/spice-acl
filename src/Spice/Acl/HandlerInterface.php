<?php

/**
 * Definition of Spice\Acl\HandlerInterface.
 * 
 * @author Henrique Barcelos <rick.hjpbarcelos@gmail.com
 */
namespace Spice\Acl;

/**
 * Interface for ACL handlers.
 */
interface HandlerInterface {
    /**
     * Adds a role to the control list.
     *
     * @param string $roleName
     *            The name of the role.
     * @param string $extends
     *            [Optional] if the new role extends its privileges
     *            from other one, indicate which one in this parameter.
     *            
     * @return void
     */
    public function addRole($roleName, $extends = null);
    
    /**
     * Adds a resource that will be access controlled.
     *
     * @param string $resourceName            
     *
     * @return void
     */
    public function addResource($resourceName);
    
    /**
     * Removes a resource from the list.
     *
     * @param string $resourceName            
     *
     * @return void
     */
    public function removeResource($resourceName);
    
    /**
     * Allows a role to access a resource.
     *
     * @param string $roleName
     *            The name of the role.
     * @param unknown $resourceName
     *            The name of the resource.
     *            
     * @return void
     */
    public function allow($roleName, $resourceName);
    
    /**
     * Denies a role from accessing a resource.
     *
     * @param string $roleName            
     * @param string $resourceName            
     *
     * @return void
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
     * @throws \Spice\Acl\AccessDeniedException If the role
     *         does not have access to the resource.
     * @throws \InvalidParameterException If there is no such role
     *          as `$roleName`
     * @throws \InvalidParameterException If there is no such resource
     *          as `$resourceName`
     */
    public function check($roleName, $resourceName);
}