<?php

/**
 * Este script limpia la caché de Drupal
 */

// Cargar el ambiente de Drupal
$autoloader = require_once 'autoload.php';
require_once 'core/includes/bootstrap.inc';

// Inicializar Drupal
drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);

// Limpiar todas las cachés
drupal_flush_all_caches();

echo "Caché limpiada correctamente\n"; 