<?php

/**
 * @file
 * Contains \Drupal\hierarchical_config\ConfigurationAccessControlHandler.
 */

namespace Drupal\hierarchical_config;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the Configuration entity.
 *
 * @see \Drupal\hierarchical_config\Entity\Configuration.
 */
class ConfigurationAccessControlHandler extends EntityAccessControlHandler {
  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\hierarchical_config\ConfigurationInterface $entity */
    switch ($operation) {
      case 'view':
        if (!$entity->isPublished()) {
          return AccessResult::allowedIfHasPermission($account, 'view unpublished configuration entities');
        }
        return AccessResult::allowedIfHasPermission($account, 'view published configuration entities');

      case 'update':
        return AccessResult::allowedIfHasPermission($account, 'edit configuration entities');

      case 'delete':
        return AccessResult::allowedIfHasPermission($account, 'delete configuration entities');
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add configuration entities');
  }

}
