# Milebits Authorizer - For Laravel +6, +7, +8
Milebits Authorizer gives you access to simplified role/permissions management system for your laravel application in one simple installation.

## How to Install
### Install the package

```
composer require milebits/authorizer
```

### Add it to the Model

```
use Milebits\Authorizer\Concerns\Authorizer;

class User extends Model{
    ...
    use Authorizer;
    ...
}
```
And that's it, you are done! you can now use this package

## How to use it

### Relations
#### User roles

```
$user->roles
$user->roles()
```

#### User permissions

```
$user->permissions
$user->permissions()
```

### Methods
#### Adding a role to a user
```
$user->addRole($role)
```
In this case `$role` should be the ID of the desired role to add
#### Removing a role from a user
```
$user->removeRole($role)
```
In this case `$role` should be the ID of the desired role to add
#### Checking if a user has a certain role
```
$user->hasRole($role)
```
In this case `$role` can be either: the ID, the slug, or the role model itself.
#### Checking if a user has a certain permission
```
$user->hasPermission($role)
```
In this case `$role` can be either: the ID, the slug, or the permission model itself.

## Using Middlewares
#### HasPermission Middleware
```
use Milebits\Authorizer\Http\Middleware\HasPermission;
...
$this->middleware(HasPermission::class.':permission_slug_or_id');
```
#### HasRole Middleware
```
use Milebits\Authorizer\Http\Middleware\HasRole;
...
$this->middleware(HasRole::class.':role_slug_or_id');
```

# Contributions
If in any case while using this package, and you which to request a new functionality to it, please contact us at suggestions@os.milebits.com and mention the package you are willing to contribute or suggest a new functionality.

# Vulnerabilities
If in any case while using this package, you encounter security issues or security vulnerabilities, please do report them as soon as possible by issuing an issue here in Github or by sending an email to security@os.milebits.com with the mention **Vulnerability Report milebits/authorizer** as your subject.
