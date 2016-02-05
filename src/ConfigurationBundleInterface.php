<?php

/**
 * @file
 * Contains \Drupal\hierarchical_config\ConfigurationTypeInterface.
 */

namespace Drupal\hierarchical_config;

use Drupal\Core\Config\Entity\ConfigEntityInterface;

/**
 * Provides an interface for defining Configuration type entities.
 */
interface ConfigurationBundleInterface extends ConfigEntityInterface {

  /**
   * Returns the label.
   *
   * @param \Drupal\hierarchical_config\ConfigurationInterface $media
   *   The Media entity.
   *
   * @return string|bool
   *   Returns the label of the bundle that entity belongs to.
   */
  public static function getLabel(ConfigurationInterface $media);

  /**
   * Returns the media bundle ID.
   *
   * @param int $id
   *   The Media bundle ID.
   *
   * @return bool
   *   Returns the media bundle ID.
   */
  public static function exists($id);

  /**
   * Returns the media type plugin.
   *
   * @return \Drupal\hierarchical_config\ConfigurationTypeInterface
   *   The type.
   */
  public function getType();

  /**
   * Returns the media type configuration.
   *
   * @return array
   *   The type configuration.
   */
  public function getTypeConfiguration();

  /**
   * Sets the media type configuration.
   *
   * @param array $configuration
   *   The type configuration.
   */
  public function setTypeConfiguration($configuration);

}




