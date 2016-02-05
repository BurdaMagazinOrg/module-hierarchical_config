<?php

/**
 * @file
 * Contains Drupal\hierarchical_config\MediaForm.
 */

namespace Drupal\hierarchical_config;

use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Entity\EntityManager;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Form controller for the media edit forms.
 */
class ConfigurationForm extends ContentEntityForm {

  /**
   * Default settings for this media bundle.
   *
   * @var array
   */
  protected $settings;

  /**
   * The entity being used by this form.
   *
   * @var \Drupal\hierarchical_config\Entity\Configuration
   */
  protected $entity;


  /**
   * Manager for media entity type plugins.
   *
   * @var \Drupal\hierarchical_config\ConfigurationTypeManager
   */
  protected $configurationTypeManager;

  /**
   * Constructs a new class instance.
   *
   * @param EntityManager $entityManager
   * @param \Drupal\hierarchical_config\ConfigurationTypeManager $configurationTypeManager
   */
  public function __construct(EntityManager $entityManager, ConfigurationTypeManager $configurationTypeManager) {

    parent::__construct($entityManager);

    $this->configurationTypeManager = $configurationTypeManager;

  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('entity.manager'),
      $container->get('plugin.manager.hierarchical_config.type')
    );
  }


  /**
   * {@inheritdoc}
   */
  protected function prepareEntity() {
    $configuration = $this->entity;

    // If this is a new media, fill in the default values.
    if ($configuration->isNew()) {
      $configuration->setPublished(TRUE);
      $configuration->setPublisherId($this->currentUser()->id());
      $configuration->setCreatedTime(REQUEST_TIME);
    }

  }

  /**
   * {@inheritdoc}
   */
  public function form(array $form, FormStateInterface $form_state) {
    $configuration = $this->entity;

    return parent::form($form, $form_state);
    return $configuration->bundle->entity->getType()
      ->buildConfigurationForm($form, $form_state);

  }


  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $entity = $this->entity;
    $status = parent::save($form, $form_state);

    switch ($status) {
      case SAVED_NEW:
        drupal_set_message($this->t('Created the %label Configuration.', [
          '%label' => $entity->label(),
        ]));
        break;

      default:
        drupal_set_message($this->t('Saved the %label Configuration.', [
          '%label' => $entity->label(),
        ]));
    }
    $form_state->setRedirect('entity.configuration.canonical', ['configuration' => $entity->id()]);
  }
}
