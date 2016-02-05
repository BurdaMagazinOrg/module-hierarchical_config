<?php

/**
 * @file
 * Contains \Drupal\hierarchical_config\ConfigurationBundleForm.
 */

namespace Drupal\hierarchical_config;

use Drupal\Core\Entity\EntityForm;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Render\Element;
use Drupal\field\Entity\FieldConfig;
use Drupal\media_entity\MediaTypeManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Form controller for node type forms.
 */
class ConfigurationBundleForm extends EntityForm {

  /**
   * The instantiated plugin instances that have configuration forms.
   *
   * @var \Drupal\Core\Plugin\PluginFormInterface[]
   */
  protected $configurableInstances = [];

  /**
   * Manager for media entity type plugins.
   *
   * @var \Drupal\hierarchical_config\ConfigurationTypeManager
   */
  protected $configurationTypeManager;

  /**
   * Constructs a new class instance.
   *
   * @param \Drupal\hierarchical_config\ConfigurationTypeManager $configurationTypeManager
   */
  public function __construct(ConfigurationTypeManager $configurationTypeManager) {
    $this->configurationTypeManager = $configurationTypeManager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('plugin.manager.hierarchical_config.type')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function form(array $form, FormStateInterface $form_state) {
    $form = parent::form($form, $form_state);

    /** @var \Drupal\media_entity\MediaBundleInterface $bundle */
    $form['#entity'] = $bundle = $this->entity;
    $form_state->set('bundle', $bundle->id());
    $form['#title'] = $this->t('Edit %label configuration bundle', array('%label' => $bundle->label()));

    $form['label'] = array(
      '#title' => t('Label'),
      '#type' => 'textfield',
      '#default_value' => $bundle->label(),
      '#description' => t('The human-readable name of this configuration bundle.'),
      '#required' => TRUE,
      '#disabled' => TRUE,
      '#size' => 30,
    );

    $form['id'] = array(
      '#type' => 'machine_name',
      '#default_value' => $bundle->id(),
      '#maxlength' => 32,
      '#disabled' => TRUE,
      '#machine_name' => array(
        'exists' => array('\Drupal\media_entity\Entity\MediaBundle', 'exists'),
        'source' => array('label'),
      ),
      '#description' => t('A unique machine-readable name for this media bundle.'),
    );


    $plugins = $this->configurationTypeManager->getDefinitions();
    $options = array();
    foreach ($plugins as $plugin => $definition) {
      $options[$plugin] = $definition['label'];
    }

    $form['type'] = array(
      '#type' => 'select',
      '#title' => t('Type provider'),
      '#default_value' => $bundle->getType()->getPluginId(),
      '#options' => $options,
      '#description' => t('Configuration type provider plugin that is responsible for additional logic related to this configuration.'),
      '#disabled' => TRUE
    );

    $form['type_configuration'] = array(
      '#type' => 'fieldset',
      '#title' => t('Provider default configuration'),
      '#tree' => TRUE,
    );

    foreach ($plugins as $plugin => $definition) {
      $plugin_configuration = $bundle->getType()
        ->getPluginId() == $plugin ? $bundle->type_configuration : array();
      $form['type_configuration'][$plugin] = array(
        '#type' => 'container',
        '#states' => array(
          'visible' => array(
            ':input[name="type"]' => array('value' => $plugin),
          ),
        ),
      );
      /** @var \Drupal\media_entity\MediaTypeBase $instance */
      $instance = $this->configurationTypeManager->createInstance($plugin, $plugin_configuration);

      $typeForm = $instance->buildConfigurationForm([], $form_state);
      $typeConfig = $instance->getConfiguration();

      $typeFormOverride = [];
      foreach ($typeForm as $key => $element) {

        $typeFormOverride[$key] = $element;
        $typeFormOverride["${key}_override"] = [
          '#type' => 'checkbox',
          '#title' => $element['#title'] . t(' is overrideable'),
          '#default_value' => (isset($typeConfig["${key}_override"]) ? $typeConfig["${key}_override"] : 0),
        ];

      }

      $form['type_configuration'][$plugin] += $typeFormOverride;
      // Store the instance for validate and submit handlers.
      $this->configurableInstances[$plugin] = $instance;
    }

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    parent::validateForm($form, $form_state);

    // Let the selected plugin validate its settings.
    $plugin = $this->entity->getType()->getPluginId();
    $this->configurableInstances[$plugin]->validateConfigurationForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    parent::submitForm($form, $form_state);

    // Let the selected plugin save its settings.
    $plugin = $this->entity->getType()->getPluginId();
    $this->configurableInstances[$plugin]->submitConfigurationForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  protected function actions(array $form, FormStateInterface $form_state) {
    $actions = parent::actions($form, $form_state);
    $actions['submit']['#value'] = t('Save configuration bundle');
    return $actions;
  }

  /**
   * {@inheritdoc}
   */
  protected function copyFormValuesToEntity(EntityInterface $entity, array $form, FormStateInterface $form_state) {
    /** @var \Drupal\media_entity\MediaBundleInterface $entity */
    parent::copyFormValuesToEntity($entity, $form, $form_state);

    // Use type configuration for the plugin that was chosen.
    $configuration = $form_state->getValue('type_configuration');
    $configuration = empty($configuration[$entity->getType()
      ->getPluginId()]) ? [] : $configuration[$entity->getType()
      ->getPluginId()];

    // Copy default values to field default values
    foreach ($configuration as $fieldName => $fieldValue) {

      /** @var FieldConfig $field */
      $field = \Drupal::entityTypeManager()
        ->getStorage('field_config')
        ->load('configuration' . '.' . $entity->id() . '.' . $fieldName);

      if ($field) {

        $field->setDefaultValue($fieldValue);
        $field->save();
      }
      else {

        $fieldName = substr($fieldName, 0, strpos($fieldName, '_override'));

        if ($fieldValue) {
          entity_get_form_display('configuration', $entity->id(), 'default')
            ->setComponent($fieldName, array(
              'type' => 'string_textfield',
            ))
            ->save();
        }
        else {
          entity_get_form_display('configuration', $entity->id(), 'default')
            ->removeComponent($fieldName)
            ->save();
        }
      }
    }

    $entity->set('type_configuration', $configuration);
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    /** @var  \Drupal\media_entity\MediaBundleInterface $bundle */
    $bundle = $this->entity;
    $status = $bundle->save();

    $t_args = array('%name' => $bundle->label());
    if ($status == SAVED_UPDATED) {
      drupal_set_message(t('The configuration bundle %name has been updated.', $t_args));
    }

    $form_state->setRedirectUrl($bundle->urlInfo('collection'));
  }

}
