# Milebits Authorizer - For Laravel +6, +7, +8

Milebits Authorizer gives you access to simplified role/permissions management system for your laravel application in
one simple installation.

## How to Install

### Install the package

```bash
composer require milebits/authorizer
```

### Add it to the Model

```php
use Illuminate\Database\Eloquent\Model;
use Milebits\Authorizer\Concerns\Authorizer;

class User extends Model{
    use Authorizer;
}
```

And that's it, you are done! you can now use this package

## How to use it

### Relations

#### User roles

```php
$user= App\Models\User::find(1);

$user->roles();
```

#### User permissions

```php
$user= App\Models\User::find(1);

$user->permissions();
```

Or you can also use the `getByClassAction` method of the `Milebits\Authorizer\Models\Permission` model.
**THIS IS NOT AN ELOQUENT RELATIONSHIP**

#### Getting permissions

##### getByClassAction

```php
use App\Models\User;
use Milebits\Authorizer\Models\Permission;

$permissions = Permission::getByClassAction(class: User::class, action: 'viewAny', getCollection: true);
$permissions = Permission::getByClassAction(['class' => User::class, 'action'=> 'viewAny'], getCollection: true);
$permissions = Permission::getByClassAction([User::class, 'viewAny'], getCollection: true);
$permissions = Permission::getByClassAction('App\Models\User.viewAny', getCollection: true);
```

### Methods

#### Adding a role to a user

```php
use App\Models\User;use Milebits\Authorizer\Models\Role;
$user = User::find(1);

$role = Role::find(2);
$user->addRole($role);

$role = 'admin';
$user->addRole($role);
```

In this case `$role` should be the ID of the desired role to add

#### Removing a role from a user

```php
use App\Models\User;use Milebits\Authorizer\Models\Role;
$user = User::find(1);

$role = Role::find(2);
$user->removeRole($role);

$role = 'admin';
$user->removeRole($role);
```

In this case `$role` should be the ID of the desired role to add

#### Checking if a user has a certain role

```php
use App\Models\User;
use Milebits\Authorizer\Models\Role;
$user = User::find(1);

$heHasIt = $user->hasRole('editor');
$heHasIt = $user->hasRole(Role::find(1));
$heHasIt = $user->hasRole(Role::slug('editor')->first());
```

#### Checking if a user has a certain permission

```php
use App\Models\User;use Milebits\Authorizer\Models\Permission;
$user = User::find(1);

$canViewAnyUser = $user->hasPermission('App\Models\User.viewAny');
$canViewAnyUser = $user->hasPermission(class: User::class,action: 'viewAny');
$canViewAnyUser = $user->hasPermission([User::class, 'viewAny']);
$canViewAnyUser = $user->hasPermission([
    'class' => User::class,
    'action' => 'viewAny',
]);

// Or you can do it using the hasPermissions instead of hasPermission
$canViewOrUpdateUser = $user->hasPermissions([1, 2, 3]);
$canViewOrUpdateUser = $user->hasPermissions([
    ...Permission::getByClassAction(action: 'view', pluck: 'id'),
    ...Permission::getByClassAction(action: 'update', pluck: 'id'),
]);
```

## Using Middlewares

#### HasPermission Middleware

```php
use Milebits\Authorizer\Http\Middleware\HasPermission;
$this->middleware(HasPermission::class.':permission_slug_or_id');
```

#### HasRole Middleware

```php
use Milebits\Authorizer\Http\Middleware\HasRole;
$this->middleware(HasRole::class.':role_slug_or_id');
```

# Contributions

If in any case while using this package, and you which to request a new functionality to it, please contact us at
suggestions@os.milebits.com and mention the package you are willing to contribute or suggest a new functionality.

# Vulnerabilities

If in any case while using this package, you encounter security issues or security vulnerabilities, please do report
them as soon as possible by issuing an issue here in Github or by sending an email to security@os.milebits.com with the
mention **Vulnerability Report milebits/authorizer** as your subject.
