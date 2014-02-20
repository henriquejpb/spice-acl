<?php
namespace Spice\Acl\Handler;

use Spice\Acl\Role\RoleInterface;
use Spice\Acl\DeniedAccessException;

class HandlerTest extends \PHPUnit_Framework_TestCase {
    private $handler;
    
    public function getMockRole($roleName) {
        $mock = $this->getMockForAbstractClass('\Spice\Acl\Role\RoleInterface');
        $mock->expects($this->any())
             ->method('getName')
             ->will($this->returnValue($roleName));
        $mock->expects($this->any())
             ->method('getParent')
             ->will($this->returnValue(null));
        return $mock;
    }
    
    public function getMockRoleWithParent($roleName, RoleInterface $parent = null) {
        $mock = $this->getMockForAbstractClass('\Spice\Acl\Role\RoleInterface');
        $parent = $parent ?: $this->getMockRole($roleName . '-parent');
        $mock->expects($this->any())
            ->method('getName')
            ->will($this->returnValue($roleName));
        $mock->expects($this->any())
            ->method('getParent')
            ->will($this->returnValue($parent));
        return $mock;
    }

    public function setUp() {
        $this->handler = new Handler();
    }
    
    public function testObjectCreation() {
        $this->assertInstanceOf('\\Spice\\Acl\\Handler\\Handler', new Handler());
        $this->assertInstanceOf('\\Spice\\Acl\\Handler\\HandlerInterface', new Handler());
    }

    public function testAddRole() {
        $this->handler->addRole($this->getMockRole('mock'));
        $this->assertTrue($this->handler->hasRole('mock'));       
    }
    
    public function testGetRoles() {
        $role1 = $this->getMockRoleWithParent('mock');
        $role2 = $role1->getParent();
         
        $this->handler->addRole($role1);
        $this->handler->addRole($role2);
        $this->assertEquals(array($role1, $role2), array_values($this->handler->getRoles()));
    }
    
    public function testAddResource() {
        $this->handler->addResource('foo.bar');
        $this->assertTrue($this->handler->hasResource('foo.bar'));
    }
    
    /**
     * @depends testAddResource
     */
    public function testRemoveResource() {
        $this->handler->addResource('foo.bar');
        $this->handler->removeResource('foo.bar');
        $this->assertFalse($this->handler->hasResource('foo.bar'));
    }
    
    public function testAllow() {
        $this->handler->addResource('foo.bar');
        $this->handler->addRole($this->getMockRole('mock'));
        $this->handler->allow('mock', 'foo.bar');
        $this->assertAttributeEquals(array(
            'foo.bar' => array('mock' => true)
        ), 'allowMap', $this->handler);
    }
    
    /**
     * @depends testAllow
     * @expectedException \Spice\Acl\DeniedAccessException
     */
    public function testDeny() {
        $this->handler->addResource('foo.bar');
        $this->handler->addRole($this->getMockRole('mock'));
        $this->handler->allow('mock', 'foo.bar');
        $this->handler->deny('mock', 'foo.bar');
        $this->assertAttributeEquals(array(
                'foo.bar' => array()
        ), 'allowMap', $this->handler);
        $this->handler->check('mock', 'foo.bar');
    }
    
    /**
     * @depends testAllow
     */
    public function testAllowParentRoleWillAllowChildRole() {
        $this->handler->addResource('foo.bar');
        
        $role1 = $this->getMockRole('mock-granpa');
        $role2 = $this->getMockRoleWithParent('mock-parent', $role1);
        $role3 = $this->getMockRoleWithParent('mock', $role2);
        
        $this->handler->addRole($role1);
        $this->handler->addRole($role2);
        $this->handler->addRole($role3);
        
        $this->handler->allow('mock-granpa', 'foo.bar');
        
        $this->handler->check('mock', 'foo.bar');
    }
    
    /**
     * @depends testAllow
     * @depends testDeny
     * @expectedException \Spice\Acl\DeniedAccessException
     */
    public function testAllowChildRoleWillNotAllowParentRole() {
        $this->handler->addResource('foo.bar');
        
        $role1 = $this->getMockRole('mock-granpa');
        $role2 = $this->getMockRoleWithParent('mock-parent', $role1);
        $role3 = $this->getMockRoleWithParent('mock', $role2);
        
        $this->handler->addRole($role1);
        $this->handler->addRole($role2);
        $this->handler->addRole($role3);
        
        $this->handler->allow('mock-parent', 'foo.bar');
        
        try {
            $this->handler->check('mock', 'foo.bar');
        } catch (DeniedAccessException $e) {
            $this->fail("Role 'mock' should have access to resource 'foo.bar'");
        }
        
        $this->handler->check('mock-granpa', 'foo.bar');
    }
    
    /**
     * @depends testAllow
     * @depends testDeny
     * @expectedException \Spice\Acl\DeniedAccessException
     */
    public function testDenyChildRoleWillNotDenyParentRole() {
        $this->handler->addResource('foo.bar');
        
        $role1 = $this->getMockRoleWithParent('mock-parent');
        $role2 = $this->getMockRoleWithParent('mock', $role1);
        
        $this->handler->addRole($role1);
        $this->handler->addRole($role2);
        
        $this->handler->allow('mock-parent', 'foo.bar');
        $this->handler->deny('mock', 'foo.bar');
        
        try {
            $this->handler->check('mock-parent', 'foo.bar');
        } catch (DeniedAccessException $e) {
            $this->fail("Role 'mock-parent' should have access to resource 'foo.bar'");
        }
        
        $this->handler->check('mock', 'foo.bar');
    }
    
    /**
     * @expectedException \InvalidArgumentException
     */
    public function testAllowWithUnexistentRoleWillThrowException() {
        $this->handler->addResource('foo.bar');
        $this->handler->allow('mock', 'foo.bar');
    }
    
    /**
     * @expectedException \InvalidArgumentException
     */
    public function testAllowWithUnexistentResourceWillThrowException() {
        $this->handler->addRole($this->getMockRole('mock'));
        $this->handler->allow('mock', 'foo.bar');
    }
    
    /**
     * @depends testAllow
     * @depends testDeny
     */
    public function testDenyParentRoleAndAllowChildRole() {
        $this->handler->addResource('foo.bar');
        
        $role1 = $this->getMockRoleWithParent('mock-parent');
        $role2 = $this->getMockRoleWithParent('mock', $role1);

        $this->handler->addRole($role1);
        $this->handler->addRole($role2);
        
        $this->handler->deny('mock-parent', 'foo.bar');
        $this->handler->allow('mock', 'foo.bar');
        
        $this->handler->check('mock', 'foo.bar');
        try {
            $this->handler->check('mock-parent', 'foo.bar');
            $this->fail("Role 'mock-parent' should NOT have access to resource 'foo.bar'");
        } catch (DeniedAccessException $e) {
            
        }
    }
    
    /**
     * @expectedException \Spice\Acl\DeniedAccessException
     */
    public function testAccessToNotAssignedResourceWillBeDenied() {
        $role1 = $this->getMockRole('mock');
        
        $this->handler->addResource('foo.bar');
        $this->handler->addRole($role1);
        
        $this->handler->check('mock', 'foo.bar');
    }
}