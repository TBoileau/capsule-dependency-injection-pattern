Dependency Injection
====================

[Twitch](https://www.twitch.tv/toham)
[Youtube](https://www.youtube.com/c/ThomasBoileau)

# Installation

```
composer require tboileau/dependency-injection ^1.0
```

# Add alias 

```php
<?php

$container->addAlias(FooInterface::class, Foo::class);
$foo = $container->get(FooInterface::class);
```

# Add factory

```php
<?php

$container->addFactory(Foo::class, FooFactory::class, "create");
$foo = $container->get(Foo::class);
```
