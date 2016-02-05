<?php

/**
 * @file
 * Contains \Drupal\hierarchical_config\ConfigurationTypeManager.
 */

namespace Drupal\hierarchical_config;

use Drupal\Core\Cache\CacheBackendInterface;
use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\Core\Plugin\DefaultPluginManager;

/**
 * Manages media entity type plugins.
 */
class ConfigurationTypeManager extends DefaultPluginManager {

  /**
   * Constructs a new MediaTypeManager.
   *
   * @param \Traversable $namespaces
   *   An object that implements \Traversable which contains the root paths
   *   keyed by the corresponding namespace to look for plugin implementations.
   * @param \Drupal\Core\Cache\CacheBackendInterface $cache_backend
   *   Cache backend instance to use.
   * @param \Drupal\Core\Extension\ModuleHandlerInterface $module_handler
   *   The module handler.
   */
  public function __construct(\Traversable $namespaces, CacheBackendInterface $cache_backend, ModuleHandlerInterface $module_handler) {
    parent::__construct('Plugin/ConfigurationEntity/Type', $namespaces, $module_handler, 'Drupal\hierarchical_config\ConfigurationTypeInterface', 'Drupal\hierarchical_config\Annotation\ConfigurationType');

    $this->alterInfo('hierarchical_config_type_info');
    $this->setCacheBackend($cache_backend, 'hierarchical_config_type_plugins');
  }

}
