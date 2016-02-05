<?php

/**
 * Contains \Drupal\hierarchical_config\Plugin\ConfigurationEntity\Type\Generic.
 */

namespace Drupal\hierarchical_config\Plugin\ConfigurationEntity\Type;

use Drupal\Core\Form\FormStateInterface;
use Drupal\hierarchical_config\Annotation\ConfigurationType;
use Drupal\hierarchical_config\ConfigurationInterface;
use Drupal\hierarchical_config\ConfigurationTypeBase;

/**
 * Provides generic media type.
 *
 * @ConfigurationType(
 *   id = "generic",
 *   label = @Translation("Generic config"),
 *   description = @Translation("Generic config type.")
 * )
 */
class Generic extends ConfigurationTypeBase {

  /**
   * {@inheritdoc}
   */
  public function providedFields() {
    return [
      'foo',
      'bar',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getField(ConfigurationInterface $configuration, $name) {
    return FALSE;
  }

  public function buildConfigurationForm(array $form, FormStateInterface $form_state) {
    $form['foo'] = array(
      '#title' => t('Foo'),
      '#type' => 'textfield',
      '#default_value' => empty($this->configuration['foo']) ? NULL : $this->configuration['foo'],
      '#description' => t('The human-readable name of this configuration bundle.'),
      '#required' => TRUE,
      '#size' => 30,
    );

    $form['bar'] = array(
      '#title' => t('Bar'),
      '#type' => 'textfield',
      '#default_value' => empty($this->configuration['bar']) ? NULL : $this->configuration['bar'],
      '#description' => t('The human-readable name of this configuration bundle.'),
      '#required' => TRUE,
      '#size' => 30,
    );

    return $form;
  }


}
