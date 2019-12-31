# URL Shortener

Api para acortar URLs en función de una estrategia dada. No se han realizado o tenido en cuenta los
- Funcionalidad de añadir nuevos usuarios, por lo que hay que usar el existente (john.doe).



## Puesta en marcha

1. Ejecutar el siguiente comando, desde la carpeta del proyecto
```sh
sudo docker-compose up -d --build
```

2. Accedemos al contenedor donde se encuentra php
```sh
sudo docker exec -it  urlshortener_php_1 bash
```

3. Instalamos las dependencias
```sh
composer install
```

3. Creamos la base de datos con los datos de prueba
```sh
php bin/console doctrine:database:create
php bin/console doctrine:schema:update --force
php bin/console doctrine:fixtures:load
```

## Probando el API

La documentación del API se encuentra en
```
http://localhost:8001/api/doc
```

Para probarla desde el terminal ejecutamos los siguientes comandos
```sh
# Obtener todas las URLs almacenadas
curl -X GET "http://localhost:8001/api/url"
curl -X GET "http://localhost:8001/api/url?orderby=total"

# Obtener el token para poder añadir nuevas URLs
curl -X POST http://localhost:8001/api/login_check -H "Content-Type: application/json" -d '{"username": "john.doe", "password": "secret"}'

# Con el token obtenido, podemos almacenar nuevas URLs
curl -X POST "http://localhost:8001/api/url" -H "Authorization: Bearer <TOKEN>" -H "Content-Type: application/x-www-form-urlencoded" -d "url=https://www.elcorreo.com"
curl -X POST "http://localhost:8001/api/url" -H "Authorization: Bearer <TOKEN>" -H "Content-Type: application/x-www-form-urlencoded" -d "url=https://www.elcorreo.com&strategy=UrlToAlphanumeric"
```

Para probar la redirección, seleccionamos una de las URLs almacenadas
```json
{
    "id":"1",
    "namelong":"www.elcorreo.com",
    "nameshort":"vfcjm1852",
    ...
}
```
y accedemos en un navegador con la siguiente URL
```
http://localhost:8001/api/url/<NAMESHORT>
```


## Añadir nuevas estrategias

1. Crear la clase con la estrategia nueva en `src/Services/Strategies` implementado la interfaz `ShortenerInterface`.

2. En `src/Services/ShortenerService.php`, añadir en la constante el nombre de la nueva estrategia. La primera estrategia del array sera tomada por defecto si no se indica una estrategia a usar al acortar la url.

```php
private const STRATEGIES = [
    'UrlToNumber',
    'UrlToAlphanumeric'
];
```
