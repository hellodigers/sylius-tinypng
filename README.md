# Sylius GoImporter

## Installation

### Add to repositories in rcomposer.json
```json
{
    "type": "vcs",
    "url": "https://git.dige.com.pl/sylius-plugins/tinypng.git",
    "name": "dige/sylius-tinypng-plugin"
}
```

### Composer install
```
composer require dige/sylius-tinypng-plugin
```

### Register bundle:
```
 Dige\TinypngPlugin\DigeSyliusTinypngPlugin::class => ['all' => true]
```

### Register routing:
In file `config/routes.yaml` add:
```yaml
dige_tinypng_plugin:
  resource: "@DigeSyliusTinypngPlugin/Resources/config/admin_routing.yml"
```

### Register messenger routing
```yaml
routing:
    'Dige\TinypngPlugin\Message\CompressImage': default
    'Dige\TinypngPlugin\Message\CompressImages': default
    'Dige\TinypngPlugin\Message\CreateMediaLogs': default
```

### Import required config in your `config/packages/_sylius.yaml` file:

```yaml
# config/packages/_sylius.yaml

imports:
    ...
    - { resource: "@DigeSyliusTinypngPlugin/Resources/config/app/config.yml" }
```


### Finish the installation by updating the database schema and installing assets:

```
$ bin/console doctrine:migrations:diff
$ bin/console doctrine:migrations:migrate
```

### Modify AdminUser entity by adding method
```php
getUserIdentifier(): string
```


### Notes:
For automatic register compression for an image, entity image have to implement `Sylius\Component\Core\Model\ImageInterface`.
Otherwise, override configuration `dige_sylius_tinypng_plugin.evenet_listener.compress_image_register`
