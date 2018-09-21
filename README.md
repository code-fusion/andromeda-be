# andromeda-be
Backend for Andromeda App

# Introduccion
Esta es la documentacion para enteder como funciona el framework de la API

## Funcionamiento
Todos los request llegan al index.php el cual, luego de cargar la configuracion inicial, variables de entorno y conexion a base
de datos carga las rutas disponibles para ser llamadas.
Si la llamas corresponde con una de las definidas entonces se pasa a resolver la misma.
Una llamadas generalmente corresponde a una metodo de una clase definida en domain.
Las clases en domain son hijas de DomainObject con lo cual vienen con varios metodos y atributos para manejarse de manera mas sencillas.

## Estructura de proyecto
El proyecto se divide en las siguientes entidades y sus respectivas descripciones:

* class: Aca van todas las clases de funcionamiento interno del framework. Clases desarrolladas propias.
* domain: Las controladores de la aplicacion.
* sql: Las queries que pueden ser ejecutadas por los controladores.
* tests: Los tests de PHPUnit.
* config.php: Configuraciones basicas del framework.
* db.config.php: Configuracion de la conexion a la base de datos.
* functions.php: Funciones globales de PHP para ser utilizadas en cualquier momento.
* index.php: Punto de entrada del framework
* queries.php: Contiene el vector de consultas a la base de datos. Es una alternativa al uso de la carpeta sql.
* router.php: Definicion de las rutas de la API.
* tables.php: Permite definir sobrenombres a las tablas.
