/**
 * @file
 * JavaScript para manejar la paginacion y busqueda con AJAX.
 * 
 * Este archivo se encarga de interceptar los clics en el paginador
 * y el envío del formulario de búsqueda para que no recarguen toda la página.
 * En su lugar, hace una peticion AJAX y actualiza solo el contenido de la tabla.
 */
(function ($, Drupal) {
  'use strict';

  /**
   * Comportamiento Drupal para manejar la paginacion y búsqueda AJAX.
   */
  Drupal.behaviors.usuariosDiegoPager = {
    /**
     * Función auxiliar para añadir parámetros de búsqueda.
     * Debe estar disponible para todo el behavior.
     */
    addSearchParams: function(params) {
      // Obtener valores actuales del formulario
      var searchName = $('#edit-search-name').val();
      var searchSurname = $('#edit-search-surname').val();
      var searchEmail = $('#edit-search-email').val();
      
      // Añadir parámetros si tienen valor
      if (searchName) params.search_name = searchName;
      if (searchSurname) params.search_surname = searchSurname;
      if (searchEmail) params.search_email = searchEmail;
      
      return params;
    },
    
    /**
     * Funcion attach que se ejecuta al cargar la página y tras cambios AJAX.
     */
    attach: function (context, settings) {
      var self = this;
      
      // Debug de inicialización para verificar que el script se ejecuta
      console.log('Inizializado UsuariosDiegoPager - Escuchando clics en el paginador');
      
      // Registrar todos los elementos del paginador para depuración
      $('.pager__item a, .pager a, .pager-item a, .pager li a, ul.pager li a', context).each(function() {
        console.log('Enlace paginador encontrado:', $(this).attr('href'), 'Texto:', $(this).text(), 'Data-page:', $(this).data('page'));
      });
      
      // Manejar clics en el paginador - Versión mejorada con más selectores
      $('.pager__item a, .pager a, .pager-item a, .pager li a, ul.pager li a', context).once('ajax-pager').on('click', function (e) {
        e.preventDefault();
        console.log('CLICK en paginador detectado');
        
        // Verificar que el enlace tenga un atributo href válido
        var originalUrl = $(this).attr('href');
        console.log('Paginador clickeado:', this, 'href:', originalUrl);
        
        if (!originalUrl || originalUrl === '#') {
          console.error('Error: El enlace del paginador no tiene un atributo href válido', this);
          
          // Intentar obtener la página del elemento de datos
          var pageData = $(this).data('page');
          if (pageData !== undefined) {
            console.log('Usando el atributo data-page como número de página:', pageData);
            var params = { page: pageData };
            // Añadir parámetros de búsqueda si existen
            params = self.addSearchParams(params);
            self.performAjaxRequest(params);
            return;
          }
          
          // Intentar obtener el número de página del texto
          var pageText = $(this).text().trim();
          if (!isNaN(pageText) && pageText !== '') {
            console.log('Usando el texto del enlace como número de página:', pageText);
            var params = { page: parseInt(pageText) - 1 };
            // Añadir parámetros de búsqueda si existen
            params = self.addSearchParams(params);
            self.performAjaxRequest(params);
            return;
          }
          
          // Intentar determinar por el contexto (siguiente, anterior, etc.)
          var classes = $(this).parent().attr('class');
          if (classes) {
            var page = 0;
            if (classes.indexOf('pager__item--next') >= 0 || 
                classes.indexOf('pager-next') >= 0) {
              console.log('Detectada acción: Página siguiente');
              // Intentar encontrar la página actual y avanzar una
              var activePage = $('.pager__item.is-active a, .pager-current', context).first().text();
              page = !isNaN(activePage) ? parseInt(activePage) : 0;
              var params = { page: page };
              // Añadir parámetros de búsqueda si existen
              params = self.addSearchParams(params);
              self.performAjaxRequest(params);
              return;
            } else if (classes.indexOf('pager__item--previous') >= 0 || 
                       classes.indexOf('pager-previous') >= 0) {
              console.log('Detectada acción: Página anterior');
              // Intentar encontrar la página actual y retroceder una
              var activePage = $('.pager__item.is-active a, .pager-current', context).first().text();
              page = !isNaN(activePage) ? parseInt(activePage) - 2 : 0;
              page = Math.max(0, page); // No permitir páginas negativas
              var params = { page: page };
              // Añadir parámetros de búsqueda si existen
              params = self.addSearchParams(params);
              self.performAjaxRequest(params);
              return;
            }
          }
          
          // Si llegamos aquí, no pudimos determinar la página
          alert('No se pudo determinar la página a cargar. Por favor, intenta recargar la página.');
          return;
        }
        
        console.log('URL del paginador:', originalUrl);
        
        // Extraer el número de página del enlace usando varias estrategias
        var page = 0;
        var pageMatch = originalUrl.match(/[?&]page=(\d+)/);
        if (pageMatch) {
          page = pageMatch[1];
          console.log('Página extraída de la URL mediante parámetro page=:', page);
        } else {
          // Estrategia alternativa: extraer de la ruta si tiene formato "/page/N"
          var pathMatch = originalUrl.match(/\/page\/(\d+)/);
          if (pathMatch) {
            page = pathMatch[1];
            console.log('Página extraída de la URL mediante ruta /page/:', page);
          } else {
            // Si no encontramos la página en la URL, intentamos con data-page
            var dataPage = $(this).data('page');
            if (dataPage !== undefined) {
              page = dataPage;
              console.log('Página extraída del atributo data-page:', page);
            }
          }
        }
        
        // Construir parámetros
        var params = {};
        if (page !== undefined) params.page = page;
        
        // Añadir parámetros de búsqueda
        params = self.addSearchParams(params);
        
        console.log('Parámetros finales para la petición AJAX:', params);
        
        // Realizar petición AJAX
        self.performAjaxRequest(params);
      });

      // Manejar envío del formulario de búsqueda
      $('#user-search-form, .usuario-search-form, .search-form-manual form', context).once('ajax-search').on('submit', function (e) {
        e.preventDefault();
        console.log('Formulario de búsqueda enviado');
        
        // Construir parámetros con los valores del formulario
        var params = {};
        params = self.addSearchParams(params);
        
        // Realizar petición AJAX
        self.performAjaxRequest(params);
        
        // Debugging - Mostrar los valores y parámetros
        console.log('Formulario enviado. Parámetros:', params);
      });
    },

    /**
     * Realiza la petición AJAX para actualizar el listado.
     */
    performAjaxRequest: function(params) {
      // Usar window.location.pathname para conseguir la URL base actual
      var currentPath = window.location.pathname;
      var baseUrl = currentPath;
      
      // Si estamos en una URL que no termina en "usuarios-list", corregir
      if (currentPath.indexOf('usuarios-list') === -1) {
        baseUrl = drupalSettings.path.baseUrl + 'usuarios-list';
      }
      
      console.log('URL base determinada para AJAX:', baseUrl);
      console.log('Parámetros:', params);
      
      $.ajax({
        url: baseUrl,
        type: 'GET',
        data: params,
        success: function (response) {
          console.log('Respuesta AJAX recibida correctamente');
          // Actualizar todo el contenedor en lugar de solo la tabla
          $('#usuarios-diego-container').html(response);
          // Aplicar comportamientos a todo el contenedor
          Drupal.attachBehaviors($('#usuarios-diego-container')[0]);
        },
        error: function(xhr, status, error) {
          console.error('Error en la petición AJAX:');
          console.error('- URL intentada:', baseUrl);
          console.error('- Estado:', status);
          console.error('- Error:', error);
          console.error('- Respuesta:', xhr.responseText);
          
          alert('Error al cargar la página. Consulta la consola para más detalles.');
        }
      });
    }
  };

})(jQuery, Drupal); 