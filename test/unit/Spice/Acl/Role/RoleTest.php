<?php
namespace Spice\Acl\Role;

class RoleTest extends \PHPUnit_Framework_TestCase {
    public function testObjectCreation() {
        $this->assertInstanceOf('\\Spice\\Acl\\Role\\Role', new Role('admin'));
        $this->assertInstanceOf('\\Spice\\Acl\\Role\\RoleInterface', new Role('admin'));
    }
    
    public function testGetName() {
        $role = new Role('admin');
        $this->assertEquals('admin', $role->getName());    
    }
    
    public function testGetParent() {
        $parent = new Role('user');
        
        $role = new Role('admin', $parent);
        $this->assertSame($parent, $role->getParent());
        
        $anotherRole = new Role('root');
        $this->assertNull($anotherRole->getParent());
    }
}