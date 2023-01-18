<h1 align="center"> versionable </h1>

<p align="center"> ⏱️ Make Laravel model versionable.</p>

It's a minimalist way to make your model support version history, and it's very simple to roll back to the specified version.

## Requirement

1. PHP >= 8.1
2. laravel/framework >= 9.0

## Installing

```shell
composer require cape-and-bay/versionable -vvv
```

Then run this command to create a database migration:

```bash
php artisan migrate
```

## Usage

Add `CapeAndBay\Versionable\Versionable` trait to the model that you want to be versioned:

```php
use CapeAndBay\Versionable\Versionable;

class Post extends Model
{
    use Versionable;

    # Prevents versioning Post if only the "is_active" column is updated.
    protected array $dont_version = ['is_active'];
}
```

Versions will be created on vensionable model saved.

```php
$post = Post::create(['title' => 'version1', 'content' => 'version1 content']);
$post->update(['title' => 'version2']);
```

You can prevent a new version of model from being created by return `false` on `onNewVersionCreate`. to use this method, your model must implement the `VersionableInterface` in conjunction with `Versionable` trait:

```php
use CapeAndBay\Versionable\Versionable;
use CapeAndBay\Versionable\VersionableInterface;
use Illuminate\Database\Eloquent\Model;

class User extends Model implements VersionableInterface
{
    use Versionable;
    
    public function onNewVersionCreate(Model $model) : bool
    {
        // Create new version if email is not "skip@mail.com"
        return $model->email !== 'skip@mail.com';
    }
}
```

### Get versions

```php
$post->versions; // all versions
$post->latestVersion(); // latest version
$post->versions->first(); // first version
```
