<?php
/**
 * @file
 * Contains
 */


function hierarchical_config_token_info() {

  $type = array(
    'name' => t('Configurations'),
    'description' => t('Tokens related to individual configuration entities.'),
  );

  $configuration = [];

  $bundles = \Drupal::entityManager()->getBundleInfo('configuration');

  foreach ($bundles as $bundle => $bundleInfo) {

    $fields = \Drupal::entityManager()
      ->getFieldDefinitions('configuration', $bundle);

    foreach ($fields as $field) {
      if ($field instanceof Drupal\field\Entity\FieldConfig) {

        $configuration[$field->get('field_name')] = array(
          'name' => $field->get('label'),
          'description' => $field->get('description'),
        );
      }
    }
  }

  return array(
    'types' => array('configuration' => $type),
    'tokens' => array('configuration' => $configuration),
  );
}
