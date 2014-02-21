# spice-acl [![Build Status](https://travis-ci.org/henriquejpb/spice-acl.png?branch=master)](https://travis-ci.org/henriquejpb/spice-acl)#

Access Control List component of Spice Framework

## Usage

```php
use Spice\Acl\Role\Role;
use Spice\Acl\Handler\Handler;

$handler = new Handler();
$user = new Role('user');
$admin = new Role('admin', $user);

$handler->addRole($user);
$handler->addRole($admin);

$handler->addResource('my_resource');

$handler->allow('user', 'my_resource');

$handler->check('user', 'my_resource'); // will pass
$handler->check('admin', 'my_resource'; // will also pass, because 'admin' role extends 'user' role
```

##Resources
Resources are parts of your system whose access you want to restrict.

###Creating Resources
Resources are created just giving its name to a `Handler` object:

```php
$handler->addResource('my_resource');
```

###Giving permissions to access a Resource

```php
$handler = new Handler();
$user = new Role('user');

$handler->addRole($user);
$handler->addResource('my_resource');

$handler->allow('user', 'my_resource');
```

Notice that you MUST add a `resource` to the `Handler` before you being able to allow access to it, 
otherwise, an `\InvalidArgumentException` will be raised:
```php
$handler = new Handler();
$user = new Role('user');

$handler->addRole($user);

$handler->allow('user', 'my_resource'); // exception thrown!!!
```

## Roles
Roles are the actors of your system. You can have as many roles as you need.

### Creating Roles
The default implementation of `Spice\Acl\Role\RoleInterface` is `Spice\Acl\Role\Role`:

```php
use Spice\Acl\Role\Role;
$user = new Role('user');
```

Notice that you MUST add a `Role` to the `Handler` before you being able to allow it to access a resource, 
otherwise, an `\InvalidArgumentException` will be raised:
```php
$handler = new Handler();
$user = new Role('user');

$handler->addResource('my_resource');

$handler->allow('user', 'my_resource'); // exception thrown!!!
```

### Extending Roles
A role can **extend** another one if there is a second parameter of type `RoleInterface` on `Role` constructor:

```php
$user = new Role('user');
$admin = new Role('admin', $user);
```

A role extending another one will **inherit** its permissions by default, 
but you can ovewrite them as you need:

```php
$handler = new Handler();
$user = new Role('user');
$admin = new Role('admin', $user);

$handler->addRole($user);
$handler->addRole($admin);

$handler->addResource('my_resource');
$handler->addResource('my_secret_resource');
$handler->addResource('just_for_regular_users_resource');

$handler->allow('user', 'my_resource');
$handler->allow('user', 'just_for_regular_users_resource');
$handler->allow('admin', 'my_secret_resource');
$handler->deny('admin', 'just_for_regular_users_resource');

$handler->check('user', 'my_resource'); // will pass
$handler->check('admin', 'my_resource'; // will pass
$handler->check('admin', 'my_secret_resource'; // will pass
$handler->check('user', 'my_secret_resource'; // will NOT pass
$handler->check('user', 'just_for_regular_users_resource'); // will pass
$handler->check('admin', 'just_for_regular_users_resource'; // will NOT pass
```

In the above example, `admin` role inherits `user` role permissions. 
For this reason, it is able to access `my_resource`. To overwrite this permissions, you have to `deny` 
the role `admin` to access a resource.

### Checking Access Permissions
If a given role does not have permission to access a resource, when you call the `check` method, 
an `Exception` of type `Spice\Acl\DeniedAccessException` will be raised:

```php
$handler = new Handler();
$user = new Role('user');
$admin = new Role('admin', $user);

$handler->addRole($user);
$handler->addRole($admin);

$handler->addResource('my_resource');
$handler->addResource('my_secret_resource');
$handler->addResource('just_for_regular_users_resource');

$handler->allow('user', 'my_resource');
$handler->allow('user', 'just_for_regular_users_resource');
$handler->allow('admin', 'my_secret_resource');
$handler->deny('admin', 'just_for_regular_users_resource');

try {
	$handler->chech('user', 'my_secret_resoruce');
} catch (DeniedAccessException $e) {
    die('Permission Denied!');
}
```