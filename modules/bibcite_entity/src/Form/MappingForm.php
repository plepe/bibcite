<?php

namespace Drupal\bibcite_entity\Form;


use Drupal\bibcite\Plugin\BibciteFormatInterface;
use Drupal\Core\Entity\EntityFieldManagerInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Format mapping form.
 */
class MappingForm extends FormBase {

  /**
   * Configuration object.
   *
   * @var \Drupal\Core\Config\Config
   */
  protected $config;

  /**
   * Entity field manager service.
   *
   * @var \Drupal\Core\Entity\EntityFieldManagerInterface
   */
  protected $entityFieldManager;

  /**
   * Entity type manager service.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * Mapping form constructor.
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   Entity type manager service.
   * @param \Drupal\Core\Entity\EntityFieldManagerInterface $entity_field_manager
   *   Entity field manager service.
   */
  public function __construct(EntityTypeManagerInterface $entity_type_manager, EntityFieldManagerInterface $entity_field_manager) {
    $this->entityFieldManager = $entity_field_manager;
    $this->entityTypeManager = $entity_type_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('entity_type.manager'),
      $container->get('entity_field.manager')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'bibcite_entity_mapping';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, $bibcite_format = NULL) {
    /** @var \Drupal\bibcite\Plugin\BibciteFormatInterface $bibcite_format */

    if (!$this->config) {
      $this->initConfiguration($bibcite_format);
    }

    $form['types'] = [
      '#type' => 'table',
      '#caption' => $this->t('Formats mapping'),
      '#header' => [$this->t('Format type'), $this->t('Bibliography type')],
    ];

    $type_options = $this->getBibliographyTypesOptions();
    $type_defaults = $this->config->get('types');
    foreach ($bibcite_format->getTypes() as $type) {
      $form['types'][$type]['format'] = [
        '#type' => 'item',
        '#markup' => $type,
        '#value' => $type,
      ];
      $form['types'][$type]['entity'] = [
        '#type' => 'select',
        '#options' => $type_options,
        '#empty_option' => $this->t('- Select -'),
        '#default_value' => isset($type_defaults[$type]) ? $type_defaults[$type] : NULL,
      ];
    }

    $form['fields'] = [
      '#type' => 'table',
      '#caption' => $this->t('Fields mapping'),
      '#header' => [$this->t('Format field'), $this->t('Bibliography field')],
    ];

    $field_options = $this->getBibliographyFieldOptions();
    $field_defaults = $this->config->get('fields');
    foreach ($bibcite_format->getFields() as $field) {
      $form['fields'][$field]['format'] = [
        '#type' => 'item',
        '#markup' => $field,
        '#value' => $field,
      ];
      $form['fields'][$field]['entity'] = [
        '#type' => 'select',
        '#options' => $field_options,
        '#empty_option' => $this->t('- Select -'),
        '#default_value' => isset($field_defaults[$field]) ? $field_defaults[$field] : NULL,
      ];
    }

    $form['actions'] = ['#type' => 'actions'];
    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Save configuration'),
      '#button_type' => 'primary',
    ];

    return $form;
  }

  /**
   * Get array of bibliography field options.
   *
   * @return array
   *   Array of fields options.
   */
  protected function getBibliographyFieldOptions() {
    $fields = $this->entityFieldManager->getFieldDefinitions('bibliography', 'bibliography');

    $excluded_fields = [
      'id',
      'uuid',
      'langcode',
      'created',
      'changed',
    ];

    return array_map(function($field) {
      /** @var \Drupal\Core\Field\FieldDefinitionInterface $field */
      return $field->getLabel();
    }, array_diff_key($fields, array_flip($excluded_fields)));
  }

  /**
   * Get array of bibliography types options.
   *
   * @return array
   *   Array of types options.
   */
  protected function getBibliographyTypesOptions() {
    $storage = $this->entityTypeManager->getStorage('bibliography_type');
    $entities = $storage->loadMultiple();

    return array_map(function($entity) {
      /** @var \Drupal\bibcite_entity\Entity\BibliographyInterface $entity */
      return $entity->label();
    }, $entities);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $types = array_map(function($type_values) {
      return $type_values['entity'];
    }, $form_state->getValue('types'));
    $this->config->set('types', $types);

    $fields = array_map(function($field_values) {
      return $field_values['entity'];
    }, $form_state->getValue('fields'));
    $this->config->set('fields', $fields);

    $this->config->save();

    drupal_set_message($this->t('Your mapping has been saved.'));
  }

  /**
   * Init mapping configuration object.
   *
   * @param \Drupal\bibcite\Plugin\BibciteFormatInterface $bibcite_format
   *   Format plugin instance.
   */
  protected function initConfiguration(BibciteFormatInterface $bibcite_format) {
    $config_name = sprintf('bibcite_entity_mapping.%s', $bibcite_format->getPluginId());
    $this->config = $this->configFactory()->getEditable($config_name);
  }

  /**
   * Mapping page title callback.
   *
   * @param \Drupal\bibcite\Plugin\BibciteFormatInterface $bibcite_format
   *   Format plugin.
   *
   * @return \Drupal\Core\StringTranslation\TranslatableMarkup
   *   Translatable title.
   */
  public static function formTitle(BibciteFormatInterface $bibcite_format) {
    return t('Mapping for @format format', ['@format' => $bibcite_format->getLabel()]);
  }

}
