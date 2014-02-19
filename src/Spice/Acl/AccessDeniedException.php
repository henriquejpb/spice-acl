<?php
/**
 * Defines an Exception subtype for denying access to a resource.
 *  
 * @author Henrique Barcelos <rick.hjpbarcelos@gmail.com>
 */
namespace Spice\Acl;

/**
 * Thrown when access to a resource is denied to a user. 
 */
class AccessDeniedException extends \RuntimeException {

}