<h1 align="center">Scaffold Laravue</h1>

### Requisitos:

- PHP 8.1.13
- Laravel Framework 10.18.0
- DB: MySql

### Personalized Command
- php artisan crud:generator ClassName --fields=fields-name-1 --fields=fields-name-2 --fields=fields-name-etc It creates controller layer, model layer, service layer, migration, http request and a route all based on class name defined

### Back-end
- Para subir o server do php.

```bash
  php artisan serve
```

- Criar as tabelas do banco de dados e as seeds.

```bash
  php artisan migrate --seed
```
