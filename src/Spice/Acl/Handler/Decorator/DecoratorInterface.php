<?php
/**
 * Interface that describes a ACL Handler decorator.
 * 
 * @author Henrique Barcelos <rick.hjpbarcelos@gmail.com>
 */
namespace Spice\Acl\Handler\Decorator;

use Spice\Acl\Handler\HandlerInterface;

/**
 * Describes a ACL Handler decorator.
 */
interface DecoratorInterface extends HandlerInterface {
    /**
     * Obtains the decorated handler
     * 
     * @return \Spice\Acl\Handler\HandlerInterface
     */
    public function getActualHandler();
}