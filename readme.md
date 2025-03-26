# Módulo Usuarios Diego para Drupal

Este módulo proporciona un listado de usuarios con funcionalidades de búsqueda y paginación mediante AJAX para Drupal.

## Características

- Listado de usuarios con columnas configurables
- Filtros de búsqueda por nombre, apellidos y correo electrónico
- Paginación AJAX (sin recarga completa de la página)
- Interfaz responsive y amigable

## Instalación

1. Descarga el módulo en la carpeta `modules/custom/` de tu instalación de Drupal
2. Activa el módulo desde el panel de administración de Drupal (Extender > Lista)
3. Limpia la caché mediante el comando `drush cache:clear` o desde el panel de administración

## Uso

Accede a la página de listado de usuarios en la URL:

```
/usuarios-list
```

También puedes añadir el bloque "Listado de Usuarios" a cualquier región de tu tema desde la configuración de bloques.

## Estructura del Módulo

El módulo se compone de los siguientes archivos principales:

- `usuarios_diego.info.yml`: Información básica del módulo
- `usuarios_diego.module`: Funciones principales del módulo, hooks y preprocesadores
- `usuarios_diego.routing.yml`: Definición de rutas
- `usuarios_diego.services.yml`: Servicios del módulo
- `src/Controller/UserListController.php`: Controlador principal para el listado de usuarios
- `templates/usuarios-diego-list.html.twig`: Plantilla para renderizar el listado
- `js/ajax-pager.js`: Script JavaScript para la funcionalidad AJAX del paginador

## Personalización

### Modificar el número de elementos por página

Para cambiar el número de elementos mostrados por página, edita el archivo `src/Controller/UserListController.php` y modifica la variable `$items_per_page`.

### Cambiar los campos mostrados en el listado

Puedes modificar los campos mostrados editando la función `renderList()` en el archivo `src/Controller/UserListController.php`.

## Solución de problemas

### Los filtros de búsqueda no funcionan

## Requisitos

- Drupal 9.x o superior
- PHP 7.4 o superior
- jQuery (incluido en Drupal core)

## Licencia

Este módulo está licenciado bajo GPL v2 o posterior.

## Autor

Desarrollado por Diego.

## Contribuir

Si deseas contribuir al desarrollo de este módulo, por favor realiza un fork del repositorio y envía tus pull requests.