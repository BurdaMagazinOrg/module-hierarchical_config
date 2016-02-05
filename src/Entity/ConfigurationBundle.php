<?php

/**
 * @file
 * Contains \Drupal\hierarchical_config\Entity\ConfigurationType.
 */

namespace Drupal\hierarchical_config\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBundleBase;
use Drupal\Core\Plugin\DefaultSingleLazyPluginCollection;
use Drupal\Core\Session\AccountInterface;
use Drupal\hierarchical_config\ConfigurationBundleInterface;
use Drupal\hierarchical_config\ConfigurationInterface;

/**
 * Defines the Configuration type entity.
 *
 * @ConfigEntityType(
 *   id = "configuration_bundle",
 *   label = @Translation("Configuration bundle"),
 *   config_prefix = "configuration_bundle",
 *   admin_permission = "administer site configuration",
 *   bundle_of = "configuration",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label",
 *     "uuid" = "uuid"
 *   },
 *   handlers = {
 *     "form" = {
 *       "edit" = "Drupal\hierarchical_config\ConfigurationBundleForm",
 *     },
 *     "list_builder" = "Drupal\hierarchical_config\ConfigurationBundleListBuilder",
 *   },
 *   config_export = {
 *     "id",
 *     "label",
 *     "description",
 *     "type",
 *     "type_configuration",
 *     "field_map",
 *   },
 *   links = {
 *     "edit-form" = "/admin/structure/hierarchical_config/manage/{configuration_bundle}",
 *     "collection" = "/admin/structure/hierarchical_config",
 *   }
 * )
 */
class ConfigurationBundle extends ConfigEntityBundleBase implements ConfigurationBundleInterface {

  /**
   * The Configuration type ID.
   *
   * @var string
   */
  protected $id;

  /**
   * The Configuration type label.
   *
   * @var string
   */
  protected $label;

  /**
   * The type plugin id.
   *
   * @var string
   */
  public $type = 'generic';

  /**
   * The type plugin configuration.
   *
   * @var array
   */
  public $type_configuration = array();

  /**
   * Type lazy plugin collection.
   *
   * @var \Drupal\Core\Plugin\DefaultSingleLazyPluginCollection
   */
  protected $typePluginCollection;

  /**
   * Field map. Fields provided by type plugin to be stored as entity fields.
   *
   * @var array
   */
  public $field_map = array();

  /**
   * {@inheritdoc}
   */
  public function id() {
    return $this->id;
  }

  /**
   * {@inheritdoc}
   */
  public static function getLabel(ConfigurationInterface $configuration) {
    $bundle = entity_load('configuration_bundle', $configuration->bundle());
    return $bundle ? $bundle->label() : FALSE;
  }

  /**
   * {@inheritdoc}
   */
  public static function exists($id) {
    return (bool) static::load($id);
  }

  /**
   * {@inheritdoc}
   */
  public function getPluginCollections() {
    return array(
      'type_configuration' => $this->typePluginCollection(),
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getTypeConfiguration() {
    return $this->type_configuration;
  }

  /**
   * {@inheritdoc}
   */
  public function setTypeConfiguration($configuration) {
    $this->type_configuration = $configuration;
    $this->typePluginCollection = NULL;
  }

  /**
   * {@inheritdoc}
   */
  public function getType() {
    return $this->typePluginCollection()->get($this->type);
  }

  /**
   * Returns type lazy plugin collection.
   *
   * @return \Drupal\Core\Plugin\DefaultSingleLazyPluginCollection
   *   The tag plugin collection.
   */
  protected function typePluginCollection() {
    if (!$this->typePluginCollection) {
      $this->typePluginCollection = new DefaultSingleLazyPluginCollection(\Drupal::service('plugin.manager.hierarchical_config.type'), $this->type, $this->type_configuration);
    }
    return $this->typePluginCollection;
  }


}
