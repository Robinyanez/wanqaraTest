# Wanqara Test Api
<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://travis-ci.org/laravel/framework"><img src="https://travis-ci.org/laravel/framework.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## Levantamiento de proyecto

Se procede a trabajar con la aplicación **laragon** para desplegar las herramientas como **PHP** en su versión 8.1 en adelante, **Nginx** y **Mysql** en su versión 8 en adelante.

Nos ayuda y facilita el despliegue rapido para las aplicaciones **Laravel** asi como otros entornos de desarrollo.

Tambien nos proporciona certificados ssl y ayuda con la creación automatica de un **Host Name** para nuesta aplicación ejemplo: **wanqara.test**.


## Clonación del proyecto

Para clonar el repositorio de **Wanqara Test Api**, realizar un **git clone** de la rama de development ya que aqui se encuentra el test, se puede usar los siguientes comandos.

        -  git clone --branch development https://github.com/Robinyanez/wanqaraTest.git
        -  git clone -b development https://github.com/Robinyanez/wanqaraTest.git

## Instalacion de paquetes

Luego de clonar el repositorio de **Wanqara Test Api** seguir lo siguientes paso para que todo funcione correctamente, todos los siguientes comando tienen que ser ejecutados por linea de comandos en la raiz del proyecto que se clono.

Ejecutar **composer install**, esto instalara las dependecias necesarias para que laravel funcione.

Ejecutar el comando **cp .env.example .env** para copiar el .env de ejemplo y posteriormente llenar los datos que correspondan (solicitar al encargado).

En caso de tener problemas para iniciar el proyecto debido a una key erronea ejecutar **php artisan key:generate** para obtener una nueva clave para el proyecto.

Crear una base de datos y configurar los parametros dentro del archivo .env para luego poder ejecutar **php artisan migrate**, con esto generaremos las tablas necesarias para el proyecto.

Luego de esto tambien ejecutar **php artisan db:seed**, lo que nos ayudara a tener usuarios de prueba para realizar la autenticación en la aplicación.

Se necesita que se tenga una **Api Key** del sitio [OpenWeatherMap](https://openweathermap.org/api), luego de obtener la **Api Key** se debe crear una variable en el archivo .env con el nombre de **OPENWEATHERMAP_API_KEY** con el valor obtenidO de **OpenWeatherMap**

# Explicación de funcionamiento del proyecto.

Se explica de manera rapida el funcionamiento de las apis para lo solicitado en la prueba.

Para el manejor de token se utilizo **Sanctum** ya que este es apropiado para este test ya que facil de utilizar y ligero a su vez.

## Api Auth

Sirve para la obtención del token de autenticación para el resto de apis ya que todas estan protegidas con el **middleware auth:sanctum**.

        - Login
        - https://wanqara.test/api/auth/login
        - POST

Usuarios creados con los **seeders**:

        - User: test1@example.com 
        - Pass: test12345

        - User: test2@example.com 
        - Pass: test23456

## Api Clima

### 1. List

Sirve para obtener todos los registros con sus respectivos comentarios.

        - https://wanqara.test/api/weather
        - GET

### 2. Store

Sirve para realizar el guardado de los datos desde la **Api** de **OpenWeatherMap** en nuestra base de datos.

La **Api** esta creada con el parametro a buscar que es la **ciudad** (city).

        - https://wanqara.test/api/weather
        - POST

Parametros:

        {
            "city": "Ambato"
        }

### 3. Show

Sirve para traer un dato en especifico tambien trae sus respectivos comentarios.

El valor **1** de la **url** pertenece al id del registro guardado en la base de datos.

        - https://wanqara.test/api/weather/1
        - GET

### 4. Clima Comentario

Sirve para crear un registro de comentario asociado a un clima, el proceso para lograr esto es realizar una petición al metodo **List** o **Show** para poder obtener un id, en el caso de que sea la primera vez, si estos metodos anteriores no retornan valor alguno es necesario realizar una petición al metodo **Store**, para empezar a guardar datos en nuestra base de datos.

        - https://wanqara.test/api/weather/comment
        - POST

Parametros:

        {
            "city_id": 1,
            "comment": "Nuevo reporte del clima"
        }

## Api Reporte

### 1. List

Sirve para obtener todos los registros con sus respectivos comentarios.

        - https://wanqara.test/api/record
        - GET

### 2. Store

Sirve para realizar el guardado de los datos de un registro.

La **Api** esta creada con los parametros **tipo** (type) y **descripción** (description).

        - https://wanqara.test/api/record
        - POST

Parametros:

        {
            "type": "clima",
            "description": "frio"
        }

### 3. Show

Sirve para traer un dato en especifico tambien trae sus respectivos comentarios.

El valor **1** de la **url** pertenece al id del registro guardado en la base de datos.

        - https://wanqara.test/api/record/1
        - GET

### 4. Reporte Comentario

Sirve para crear un comentario asociado a registro, el proceso para lograr esto es realizar una petición al metodo **List** o **Show** para poder obtener un id, en el caso de que sea la primera vez, si estos metodos anteriores no retornan valor alguno es necesario realizar una petición al metodo **Store**, para empezar a guardar datos en nuestra base de datos.

        - https://wanqara.test/api/record/comment
        - POST

Parametros:

        {
            "record_id": 1,
            "comment": "Nuevo reporte del clima frio"
        }

# Observaciones

En caso de tener un error asi en el log **Route [login] not defined**. de debe agregar a los **headers** de la api en **Postman** el **Accept: application/json**.

El archivo **Postman** en la **collection** tiene una variable de nombre **base_url** es la que se debe cambiar con el **host name** que se vaya a utiliar.
