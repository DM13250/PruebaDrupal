<?php

namespace Drupal\usuarios_diego\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\ReplaceCommand;
use Symfony\Component\HttpFoundation\Request;

/**
 * Formulario para buscar usuarios.
 *
 * Este formulario permite filtrar el listado de usuarios por nombre,
 * apellidos y correo electronico, actualizando los resultados via AJAX.
 */
class UserSearchForm extends FormBase {

  /**
   * {@inheritdoc}
   *
   * Devuelve un identificador unico para este formulario.
   */
  public function getFormId() {
    return 'user_search_form';
  }

  /**
   * {@inheritdoc}
   *
   * Construye el formulario de busqueda con campos para filtrar usuarios.
   *
   * @param array $form
   *   El array base del formulario.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   El estado actual del formulario.
   *
   * @return array
   *   El array completo del formulario listo para renderizar.
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    // Contenedor para estilos CSS
    $form['#prefix'] = '<div class="usuario-search-form">';
    $form['#suffix'] = '</div>';
    
    // Campo para filtrar por nombre
    $form['search_name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Nombre'),
    ];

    // Campo para filtrar por apellidos
    $form['search_surname'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Apellidos'),
    ];

    // Campo para filtrar por correo electronico
    $form['search_email'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Correo Electronico'),
    ];

    // Boton de busqueda
    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Buscar'),
      '#ajax' => [
        'callback' => '::ajaxSubmitHandler', // Metodo a llamar al hacer clic
        'wrapper' => 'user-list-wrapper',  // Elemento a reemplazar
        'effect' => 'fade', // Efecto visual
      ],
    ];
    
    // Biblioteca JS
    $form['#attached']['library'][] = 'usuarios_diego/usuario_list';
    
    return $form;
  }

  /**
   * {@inheritdoc}
   *
   * Este metodo es obligatorio pero no se usa porque todo se maneja via AJAX.
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    // No se necesita aqui ya que usamos AJAX.
  }

  /**
   * Manejador de envio AJAX para el formulario.
   *
   * Este metodo se llama cuando el usuario hace clic en el boton Buscar.
   * Recoge los valores del formulario, llama al controlador para obtener 
   * los resultados filtrados y actualiza la tabla via AJAX.
   *
   * @param array $form
   *   El array del formulario.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   El estado del formulario con los valores introducidos.
   *
   * @return \Drupal\Core\Ajax\AjaxResponse
   *   La respuesta AJAX que reemplazara el contenido en la pagina.
   */
  public function ajaxSubmitHandler(array &$form, FormStateInterface $form_state) {
    // Crear una nueva respuesta AJAX
    $response = new AjaxResponse();
    
    // Obtener los valores introducidos en el formulario
    $searchParams = [
      'search_name' => $form_state->getValue('search_name'),
      'search_surname' => $form_state->getValue('search_surname'),
      'search_email' => $form_state->getValue('search_email'),
    ];

    // Obtener el controlador de listado desde el contenedor de servicios
    $controller = \Drupal::service('usuarios_diego.user_list_controller');
    
    // Crear una nueva solicitud con los parametros de busqueda
    $request = new Request();
    $request->query->add($searchParams);
    
    // Llamar al controlador para renderizar el listado filtrado
    $list = $controller->renderList($request);

    // Reemplazar contenido
    $response->addCommand(new ReplaceCommand('#user-list-wrapper', $list));
    
    return $response;
  }
}
