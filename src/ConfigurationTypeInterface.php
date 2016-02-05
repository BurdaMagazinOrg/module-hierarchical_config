<?php

/**
 * @file
 * Contains \Drupal\hierarchical_config\ConfigurationTypeInterface.
 */

namespace Drupal\hierarchical_config;

use Drupal\Component\Plugin\PluginInspectionInterface;
use Drupal\Component\Plugin\ConfigurablePluginInterface;
use Drupal\Core\Plugin\PluginFormInterface;

/**
 * Defines the interface for media types.
 */
interface ConfigurationTypeInterface extends PluginInspectionInterface, ConfigurablePluginInterface, PluginFormInterface {

  /**
   * Returns the display label.
   *
   * @return string
   *   The display label.
   */
  public function label();

  /**
   * Gets list of fields provided by this plugin.
   *
   * @return array
   *   Associative array with field names as keys and descriptions as values.
   */
  public function providedFields();

  /**
   * Get's a media-related field/value.
   *
   * @param ConfigurationInterface $configuration
   *   Media object.
   * @param $name
   *   Name of field to fetch.
   *
   * @return mixed
   *   Field value or FALSE if data unavailable.
   */
  public function getField(ConfigurationInterface $configuration, $name);

  /**
   * Attaches type-specific constraints to media.
   *
   * @param ConfigurationInterface $media
   *   Media entity.
   */
  public function attachConstraints(ConfigurationInterface $media);

}
