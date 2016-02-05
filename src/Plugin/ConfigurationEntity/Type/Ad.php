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
 *   id = "ad",
 *   label = @Translation("Ad config"),
 *   description = @Translation("Ad config type.")
 * )
 */
class ad extends ConfigurationTypeBase {

  /**
   * {@inheritdoc}
   */
  public function providedFields() {
    return [
      'adsc_unit1_default',
      'adsc_unit2_default',
      'adsc_unit3_default',
      'adsc_mode_default',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getField(ConfigurationInterface $configuration, $name) {
    return FALSE;
  }

  public function buildConfigurationForm(array $form, FormStateInterface $form_state) {


    $form['adsc_unit1_default'] = [
      '#title' => t('Ad level 1'),
      '#type' => 'textfield',
      '#default_value' => $this->configuration['adsc_unit1_default'],
      '#description' => t('First hierarchical level. This is the name of the Website')
    ];


    $form['adsc_unit2_default'] = [
      '#title' => t('Ad level 2'),
      '#default_value' => $this->configuration['adsc_unit2_default'],
      '#description' => t('Second hierarchical level')
    ];

    if(empty($adsc_unit2_values)) {
      $form['adsc_unit2_default']['#type'] = 'textfield';
    } else {
      $form['adsc_unit2_default']['#type'] = 'select';
      $form['adsc_unit2_default']['#options'] =  array_combine($adsc_unit2_values, $adsc_unit2_values);
    }



    $form['adsc_unit3_default'] = [
      '#title' => t('Ad level 3'),
      '#default_value' => $this->configuration['adsc_unit3_default'],
      '#description' => t('Third hierarchical level')
    ];

    if(empty($adsc_unit3_values)) {
      $form['adsc_unit3_default']['#type'] = 'textfield';
    } else {
      $form['adsc_unit3_default']['#type'] = 'select';
      $form['adsc_unit3_default']['#options'] =  array_combine($adsc_unit3_values, $adsc_unit3_values);
    }



    $modes = ['full' => 'full', 'infinite' => 'infinite'];
    $form['adsc_mode_default'] = [
      '#title' => t('adsc_mode'),
      '#type' => 'select',
      '#options' => $modes,
      '#default_value' => $this->configuration['adsc_mode_default'],
      '#description' => t('Adsc mode')
    ];




    return $form;
  }


}
