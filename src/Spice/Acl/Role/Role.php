<?php

/**
 * Default implementation for an ACL Role.
 * @author Henrique Barcelos <rick.hjpbarcelos@gmail.com>
 */
namespace Spice\Acl\Role;

/**
 * Default implementation for an ACL Role.
 */
class Role implements RoleInterface {
    /**
     * @var string The role name
     */
    private $name;
    
    /**
     * @var string The role param
     */
    private $parent;
    
    /**
     * @param string $name
     * @param \Spice\Acl\Role\RoleInterface $parent
     */
    public function __construct($name, RoleInterface $parent = null) {
        $this->name = $name;
        $this->parent = $parent;
    }
    
    /**
     * (non-PHPdoc)
     *
     * @see \Spice\Acl\Role\RoleInterface::getParent()
     */
    public function getParent() {
        return $this->parent;
    }
    
    /**
     * (non-PHPdoc)
     *
     * @see \Spice\Acl\Role\RoleInterface::getName()
     */
    public function getName() {
        return $this->name;
    }
}