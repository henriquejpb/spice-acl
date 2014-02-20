<?php
namespace Spice\Acl\Handler;

use Spice\Acl\DeniedAccessException;
use Spice\Acl\Role\RoleInterface;

class Handler implements HandlerInterface {
    private $roles = array();
    private $resources = array();
    private $allowMap = array();
    private $denyMap = array();

    /** (non-PHPdoc)
     * @see \Spice\Acl\Handler\HandlerInterface::addRole()
     */
    public function addRole(RoleInterface $role) {
        $this->roles[$role->getName()] = $role;
    }
    
    /**
     * (non-PHPdoc)
     *
     * @see \Spice\Acl\Handler\HandlerInterface::hasRole()
     */
    public function hasRole($roleName) {
        return array_key_exists($roleName, $this->roles);
    }

    /** (non-PHPdoc)
     * @see \Spice\Acl\Handler\HandlerInterface::getRoles()
     */
    public function getRoles() {
        return $this->roles;    
    }
       
    /**
     * (non-PHPdoc)
     *
     * @see \Spice\Acl\Handler\HandlerInterface::addResource()
     */
    public function addResource($resourceName) {
        $resourceName = (string) $resourceName;
        if (!$this->hasResource($resourceName)) {
            $this->resources[$resourceName] = true;
            $this->allowMap[$resourceName] = array();
            $this->denyMap[$resourceName] = array();
        }
    }
    
    /**
     * (non-PHPdoc)
     *
     * @see \Spice\Acl\Handler\HandlerInterface::hasResource()
     */
    public function hasResource($resourceName) {
        return array_key_exists($resourceName, $this->resources);
    }
    
    /**
     * (non-PHPdoc)
     *
     * @see \Spice\Acl\Handler\HandlerInterface::removeResource()
     */
    public function removeResource($resourceName) {
        if ($this->hasResource($resourceName)) {
            unset($this->resources[$resourceName]);
            $this->allowMap[$resourceName] = array();
            $this->denyMap[$resourceName] = array();
        }
    }
    
    /**
     * (non-PHPdoc)
     *
     * @see \Spice\Acl\Handler\HandlerInterface::allow()
     */
    public function allow($roleName, $resourceName) {
        $this->checkResourceExists($resourceName);
        $this->checkRoleNameExists($roleName);
        $this->removeDenial($roleName, $resourceName);
        $this->allowMap[$resourceName][$roleName] = true;
    }
    
    /**
     * 
     * @param string $roleName
     * @param string $resourceName
     */
    private function removeDenial($roleName, $resourceName) {
        if(array_key_exists($roleName, $this->denyMap[$resourceName])) {
            unset($this->denyMap[$resourceName][$roleName]);
        }
    }
    
    /**
     * (non-PHPdoc)
     *
     * @see \Spice\Acl\Handler\HandlerInterface::deny()
     */
    public function deny($roleName, $resourceName) {
        $this->checkResourceExists($resourceName);
        $this->checkRoleNameExists($roleName);
        $this->removeAllowance($roleName, $resourceName);
        $this->denyMap[$resourceName][$roleName] = true;
    }
    
    /**
     * 
     * @param string $roleName
     * @param string $resourceName
     */
    private function removeAllowance($roleName, $resourceName) {
        if(array_key_exists($roleName, $this->allowMap[$resourceName])) {
            unset($this->allowMap[$resourceName][$roleName]);
        }
    }
    
    /**
     * (non-PHPdoc)
     *
     * @see \Spice\Acl\Handler\HandlerInterface::check()
     */
    public function check($roleName, $resourceName) {
        if (!$this->doCheck($roleName, $resourceName)) {
            $parent = $this->roles[$roleName]->getParent();
            while ($parent !== null) {
                if ($this->doCheck($parent->getName(), $resourceName)) {
                    return;
                } else {
                    $parent = $parent->getParent();
                }
            }
            throw new DeniedAccessException("Role '{$roleName}' has no
                access to resource '{$resourceName}'");
        }
    }
    
    /**
     * @param string $roleName
     * @param string $resourceName
     * 
     * @return boolean
     * 
     * @throws DeniedAccessException
     */
    private function doCheck($roleName, $resourceName) {
        $this->checkResourceExists($resourceName);
        $this->checkRoleNameExists($roleName);
        
        if (array_key_exists($roleName, $this->denyMap[$resourceName])) {
            throw new DeniedAccessException("Role '{$roleName}' has no
                access to resource '{$resourceName}'");
        }
        
        return array_key_exists($roleName, $this->allowMap[$resourceName]);
    }
    
    /**
     * @param string $roleName
     * 
     * @throws \InvalidArgumentException
     */
    private function checkRoleNameExists($roleName) {
        if (!$this->hasRole($roleName)) {
            throw new \InvalidArgumentException("Unexistent role '{$roleName}'");
        }
    }
    
    /**
     * @param string $resourceName
     * @throws \InvalidArgumentException
     */
    private function checkResourceExists($resourceName) {
        if (!$this->hasResource($resourceName)) {
            throw new \InvalidArgumentException("Unexistent resource '{$resourceName}'");
        }
    }
	/** (non-PHPdoc)
	 * @see \Spice\Acl\Handler\HandlerInterface::getResources()
	 */
	public function getResources() {
		return $this->resources;
	}
}