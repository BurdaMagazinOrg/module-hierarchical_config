<?php

/**
 * @file
 * Contains \Drupal\hierarchical_config\ConfigurationInterface.
 */

namespace Drupal\hierarchical_config;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Configuration entities.
 *
 * @ingroup hierarchical_config
 */
interface ConfigurationInterface extends ContentEntityInterface, EntityChangedInterface, EntityOwnerInterface {
  // Add get/set methods for your configuration properties here.

  /**
   * Gets the Configuration type.
   *
   * @return string
   *   The Configuration type.
   */
  public function getType();

  /**
   * Gets the Configuration name.
   *
   * @return string
   *   Name of the Configuration.
   */
  public function getName();

  /**
   * Sets the Configuration name.
   *
   * @param string $name
   *   The Configuration name.
   *
   * @return \Drupal\hierarchical_config\ConfigurationInterface
   *   The called Configuration entity.
   */
  public function setName($name);

  /**
   * Gets the Configuration creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Configuration.
   */
  public function getCreatedTime();

  /**
   * Sets the Configuration creation timestamp.
   *
   * @param int $timestamp
   *   The Configuration creation timestamp.
   *
   * @return \Drupal\hierarchical_config\ConfigurationInterface
   *   The called Configuration entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Returns the Configuration published status indicator.
   *
   * Unpublished Configuration are only visible to restricted users.
   *
   * @return bool
   *   TRUE if the Configuration is published.
   */
  public function isPublished();

  /**
   * Sets the published status of a Configuration.
   *
   * @param bool $published
   *   TRUE to set this Configuration to published, FALSE to set it to unpublished.
   *
   * @return \Drupal\hierarchical_config\ConfigurationInterface
   *   The called Configuration entity.
   */
  public function setPublished($published);

}
