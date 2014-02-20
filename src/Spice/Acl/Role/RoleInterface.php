<?php
/**
 * Defines an interfece for a ACL role.
 * 
 * @author Henrique Barcelos <rick.hjpbarcelos@gmail.com>
 */
namespace Spice\Acl\Role;

/**
 * Interfece for a ACL role.
 */
interface RoleInterface {
    /**
     * Returns the role name.
     * 
     * @return string
     */
    public function getName();
    
    /**
     * Return the role parent if it exists or NULL otherwise.
     * 
     * @return \Spice\Acl\Role\RoleInterface
     */
    public function getParent();
}