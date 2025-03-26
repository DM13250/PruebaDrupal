<?php

/**
 * @file
 * Controlador para el listado de usuarios.
 */

namespace Drupal\usuarios_diego\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Drupal\Core\Form\FormBuilderInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Drupal\Core\Pager\PagerManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Controlador para el listado de usuarios.
 */
class UserListController extends ControllerBase {

  /**
   * El constructor del controlador.
   *
   * @var \Drupal\Core\Form\FormBuilderInterface
   */
  protected $formBuilder;

  /**
   * El servicio de paginación.
   *
   * @var \Drupal\Core\Pager\PagerManagerInterface
   */
  protected $pagerManager;

  /**
   * Constructor.
   *
   * @param \Drupal\Core\Form\FormBuilderInterface $form_builder
   *   El constructor de formularios.
   * @param \Drupal\Core\Pager\PagerManagerInterface $pager_manager
   *   El servicio de paginación.
   */
  public function __construct(FormBuilderInterface $form_builder, PagerManagerInterface $pager_manager) {
    $this->formBuilder = $form_builder;
    $this->pagerManager = $pager_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('form_builder'),
      $container->get('pager.manager')
    );
  }

  /**
   * Renderiza el listado de usuarios.
   *
   * @param \Symfony\Component\HttpFoundation\Request $request
   *   La solicitud HTTP.
   *
   * @return array|Response
   *   El array de renderizado o la respuesta AJAX.
   */
  public function renderList(Request $request) {
    // Obtener los parámetros de búsqueda
    $search_name = $request->query->get('search_name', '');
    $search_surname = $request->query->get('search_surname', '');
    $search_email = $request->query->get('search_email', '');
    
    // Crear el formulario de búsqueda
    $search_form = $this->formBuilder->getForm('\Drupal\usuarios_diego\Form\UserSearchForm');
    
    // Obtener la página actual
    $page = $request->query->get('page', 0);
    
    // Simular la llamada a la API
    $users = $this->getUsersFromApi($search_name, $search_surname, $search_email);
    
    // Configurar la paginación correctamente
    $items_per_page = 5;
    $total = count($users);
    
    // Guardar los parámetros de consulta para el paginador
    $query = [
      'search_name' => $search_name,
      'search_surname' => $search_surname,
      'search_email' => $search_email,
    ];
    // Filtrar parámetros vacíos
    $query = array_filter($query);
    
    // No usar el servicio pager.manager directamente para la paginación
    // El sistema de paginación estándar de Drupal funciona usando variables globales
    $pager_manager = \Drupal::service('pager.manager');
    $pager_manager->createPager($total, $items_per_page);
    
    // Obtener la página actual del servicio pager.manager
    $pager = $pager_manager->getPager();
    $current_page = $pager ? $pager->getCurrentPage() : 0;
    
    // Obtener los usuarios para la página actual
    $start = $current_page * $items_per_page;
    $paged_users = array_slice($users, $start, $items_per_page);
    
    // Crear el encabezado de la tabla
    $header = [
      ['data' => $this->t('Usuario'), 'class' => ['usuario-table-header']],
      ['data' => $this->t('Nombre'), 'class' => ['usuario-table-header']],
      ['data' => $this->t('Apellido 1'), 'class' => ['usuario-table-header']],
      ['data' => $this->t('Apellido 2'), 'class' => ['usuario-table-header']],
      ['data' => $this->t('Email'), 'class' => ['usuario-table-header']],
    ];
    
    // Crear las filas de la tabla
    $rows = [];
    foreach ($paged_users as $user) {
      $rows[] = [
        'data' => [
          $user['username'],
          $user['name'],
          $user['surname1'],
          $user['surname2'],
          $user['email'],
        ],
      ];
    }
    
    // Crear el paginador para el render array - usar el elemento estándar de Drupal
    $pager = [
      '#type' => 'pager',
      '#element' => 0, // El índice del paginador (0 es el predeterminado)
      '#parameters' => $query, // Mantener los parámetros de búsqueda al paginar
      '#route_name' => 'usuarios_diego.list', // Ruta a la que apuntará el paginador
      '#tags' => [
        // Textos para los enlaces de navegación
        0 => $this->t('« Primera'),
        1 => $this->t('‹ Anterior'),
        3 => $this->t('Siguiente ›'),
        4 => $this->t('Última »'),
      ],
      '#quantity' => 5, // Número de enlaces de página a mostrar
    ];
    
    // Construir el render array
    $build = [
      '#theme' => 'usuarios_diego_list',
      '#header' => $header,
      '#rows' => $rows,
      '#empty' => $this->t('No se encontraron usuarios.'),
      '#attributes' => [
        'class' => ['usuario-table'],
      ],
      '#pager' => $pager,
      '#search_form' => $search_form,
      '#search_name' => $search_name,
      '#search_surname' => $search_surname,
      '#search_email' => $search_email,
      '#current_page' => $current_page,
      '#total_pages' => ceil($total / $items_per_page),
      '#attached' => [
        'library' => [
          'usuarios_diego/usuario_list',
        ],
      ],
      '#cache' => [
        'max-age' => 0,  // Desactivar caché para desarrollo
      ],
    ];
    
    // Verificar si es una solicitud AJAX
    if ($request->isXmlHttpRequest()) {
      // Si es AJAX, devolvemos solo el HTML del contenedor
      try {
        $renderer = \Drupal::service('renderer');
        // Renderizar sin el wrapper de la página
        $content = $renderer->renderRoot($build);
        $response = new Response($content);
        $response->headers->set('Content-Type', 'text/html');
        return $response;
      } 
      catch (\Exception $e) {
        // Registrar el error para depuración
        \Drupal::logger('usuarios_diego')->error('Error al procesar la solicitud AJAX: @error', ['@error' => $e->getMessage()]);
        // Devolver una respuesta de error
        $response = new Response('Error al procesar la solicitud: ' . $e->getMessage(), 500);
        $response->headers->set('Content-Type', 'text/plain');
        return $response;
      }
    }
    
    return $build;
  }

  /**
   * Simula la llamada a la API para obtener usuarios.
   *
   * @param string $search_name
   *   Nombre para filtrar.
   * @param string $search_surname
   *   Apellido para filtrar.
   * @param string $search_email
   *   Email para filtrar.
   *
   * @return array
   *   Array de usuarios filtrados.
   */
  private function getUsersFromApi($search_name, $search_surname, $search_email) {
    // Simular datos de la API
    $users = [
      [
        'username' => 'usuario1',
        'name' => 'Juan',
        'surname1' => 'Pérez',
        'surname2' => 'García',
        'email' => 'juan@example.com',
      ],
      [
        'username' => 'usuario2',
        'name' => 'María',
        'surname1' => 'López',
        'surname2' => 'Martínez',
        'email' => 'maria@example.com',
      ],
      [
        'username' => 'usuario3',
        'name' => 'Carlos',
        'surname1' => 'Rodríguez',
        'surname2' => 'Sánchez',
        'email' => 'carlos@example.com',
      ],
      [
        'username' => 'usuario4',
        'name' => 'Ana',
        'surname1' => 'Fernández',
        'surname2' => 'González',
        'email' => 'ana@example.com',
      ],
      [
        'username' => 'usuario5',
        'name' => 'Pedro',
        'surname1' => 'Martínez',
        'surname2' => 'López',
        'email' => 'pedro@example.com',
      ],
      [
        'username' => 'usuario6',
        'name' => 'Laura',
        'surname1' => 'Sánchez',
        'surname2' => 'Rodríguez',
        'email' => 'laura@example.com',
      ],
      [
        'username' => 'usuario7',
        'name' => 'Miguel',
        'surname1' => 'González',
        'surname2' => 'Fernández',
        'email' => 'miguel@example.com',
      ],
      [
        'username' => 'usuario8',
        'name' => 'Sara',
        'surname1' => 'López',
        'surname2' => 'Martínez',
        'email' => 'sara@example.com',
      ],
      [
        'username' => 'usuario9',
        'name' => 'David',
        'surname1' => 'Martínez',
        'surname2' => 'Sánchez',
        'email' => 'david@example.com',
      ],
      [
        'username' => 'usuario10',
        'name' => 'Elena',
        'surname1' => 'Rodríguez',
        'surname2' => 'González',
        'email' => 'elena@example.com',
      ],
      // Añadir más usuarios para asegurar la paginación
      [
        'username' => 'usuario11',
        'name' => 'Javier',
        'surname1' => 'Gómez',
        'surname2' => 'Ruiz',
        'email' => 'javier@example.com',
      ],
      [
        'username' => 'usuario12',
        'name' => 'Lucía',
        'surname1' => 'Torres',
        'surname2' => 'Vega',
        'email' => 'lucia@example.com',
      ],
      [
        'username' => 'usuario13',
        'name' => 'Roberto',
        'surname1' => 'Sanz',
        'surname2' => 'Jiménez',
        'email' => 'roberto@example.com',
      ],
      [
        'username' => 'usuario14',
        'name' => 'Carmen',
        'surname1' => 'Díaz',
        'surname2' => 'Moreno',
        'email' => 'carmen@example.com',
      ],
      [
        'username' => 'usuario15',
        'name' => 'Alberto',
        'surname1' => 'Romero',
        'surname2' => 'Navarro',
        'email' => 'alberto@example.com',
      ],
    ];
    
    // Filtrar usuarios según los criterios de búsqueda
    return array_filter($users, function($user) use ($search_name, $search_surname, $search_email) {
      $name_match = empty($search_name) || stripos($user['name'], $search_name) !== FALSE;
      $surname_match = empty($search_surname) || 
                      stripos($user['surname1'], $search_surname) !== FALSE ||
                      stripos($user['surname2'], $search_surname) !== FALSE;
      $email_match = empty($search_email) || stripos($user['email'], $search_email) !== FALSE;
      
      return $name_match && $surname_match && $email_match;
    });
  }
}
