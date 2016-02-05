<?php

/**
 * @file
 * Contains \Drupal\hierarchical_config\RegisterService.
 */

namespace Drupal\hierarchical_config;

use Drupal\Core\Form\FormState;
use Drupal\hierarchical_config\Entity\ConfigurationBundle;


/**
 * Class RegisterService.
 *
 * @package Drupal\hierarchical_config
 */
class RegisterService implements RegisterServiceInterface {

  public function register($bundleName, $bundleLabel, $configurationType) {

    $configuration_entity_type = ConfigurationBundle::create([
      'id' => $bundleName,
      'label' => $bundleLabel,
      'type' => $configurationType,
    ]);

    /** @var $configuration_entity_type ConfigurationBundle */
    $configuration_entity_type->save();

    $form = $configuration_entity_type->getType()
      ->buildConfigurationForm([], new FormState());

    foreach ($configuration_entity_type->getType()
               ->providedFields() as $field_name) {

      $fieldStorage = \Drupal::entityTypeManager()
        ->getStorage('field_storage_config')
        ->load('configuration.' . $field_name);

      if (empty($fieldStorage)) {
        $fieldStorageDefinition = array(
          'field_name' => $field_name,
          'entity_type' => 'configuration',
          'type' => 'string',

        );
        $fieldStorage = \Drupal::entityTypeManager()
          ->getStorage('field_storage_config')
          ->create($fieldStorageDefinition);
        $fieldStorage->save();
      }

      $fieldDefinition = array(
        'label' => $form[$field_name]['#title'],
        'description' => $form[$field_name]['#description'],
        'field_name' => $fieldStorage->getName(),
        'entity_type' => 'configuration',
        'bundle' => $bundleName,
        'settings' => [
          'display_default' => '1',
          'display_field' => '1',
        ]
      );

      $field = entity_create('field_config', $fieldDefinition);
      $field->save();


      entity_get_form_display('configuration', $bundleName, 'default')
        ->setComponent($field_name, array(
          'type' => 'string_textfield',
        ))
        ->save();
    }
  }
}
