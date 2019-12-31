# URL Shortener

Api para acortar URLs en función de una estrategia dada.

## Puesta en marcha

1. Clonar el repositorio
```sh
git clone https://github.com/ivan-iglesias/url-shortener.git
```

2. Acceder a la carpeta del proyecto `url-shortener`

3. Crear la carpeta `mysql`

4. Creamos los contenedores
```sh
docker-compose up -d --build
```

5. Accedemos al contenedor en el que se encuentra php
```sh
docker exec -it  urlshortener_php_1 bash
```

6. Dentro del contenedor, instalamos las dependencias y creamos la base de datos con los datos de prueba
```sh
composer install

php bin/console doctrine:database:create
php bin/console doctrine:schema:update --force
php bin/console doctrine:fixtures:load --no-interaction
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
