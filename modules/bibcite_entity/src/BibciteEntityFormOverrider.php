<?php

namespace Drupal\bibcite_entity;

use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Render\Element;
use Drupal\Core\StringTranslation\StringTranslationTrait;

/**
 * Collections of methods for overriding bibcite_reference entity form.
 *
 * @todo In the next major versions this method should be replaced by default field configuration mechanism.
 */
class BibciteEntityFormOverrider {

  use StringTranslationTrait;

  /**
   * Entity type manager service.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * Construct a new BibciteEntityFormOverrider object.
   */
  public function __construct() {
    $this->entityTypeManager = \Drupal::entityTypeManager();
  }

  /**
   * Process function for entity form.
   */
  public static function staticReferenceFieldsOverride(array $element, FormStateInterface $form_state) {
    /** @var \Drupal\Core\Entity\EntityFormInterface $form_object */
    $form_object = $form_state->getFormObject();
    $entity = $form_object->getEntity();

    $overrider = new static();
    $overrider->referenceFieldsOverride($element, $entity->bundle());
    return $element;
  }

  /**
   * Process function for entity form.
   */
  public static function staticReferenceRestructure(array $element, FormStateInterface $form_state) {
    $overrider = new static();
    $overrider->referenceFormTabsRestructure($element);
    return $element;
  }

  /**
   * Override elements attributes based on bundle configuration.
   *
   * @see \Drupal\bibcite_entity\Form\ReferenceForm::form()
   */
  public function referenceFieldsOverride(array &$element, $entity_bundle) {
    /** @var \Drupal\bibcite_entity\Entity\ReferenceTypeInterface $bundle_entity */
    $bundle_entity = $this->getEntityBundleObject($entity_bundle);
    if ($bundle_entity) {
      foreach ($bundle_entity->getFields() as $field_name => $field_config) {
        if (isset($element[$field_name])) {
          if (!$field_config['visible']) {
            $element[$field_name]['#access'] = FALSE;
          }

          if (!empty($field_config['label'])) {
            $this->setFormElementParameter($element[$field_name], '#title', $field_config['label']);
          }

          if (!empty($field_config['hint'])) {
            $this->setFormElementParameter($element[$field_name], '#description', $field_config['hint']);
          }

          if ($field_config['required']) {
            $this->setFormElementParameter($element[$field_name], '#required', $field_config['required']);
          }
        }
      }
    }
  }

  /**
   * Restructure form elements to the vertical tabs view.
   */
  public function referenceFormTabsRestructure(array &$element) {
    $field_groups = $this->getGroupedFields();

    // Place tabs under the title.
    $weight = $element['title']['#weight'];

    $element['tabs'] = [
      '#type' => 'vertical_tabs',
      '#weight' => ++$weight,
    ];

    foreach ($field_groups as $group_id => $group) {
      foreach ($group['elements'] as $field_id) {
        if (isset($element[$field_id]) && $element[$field_id]['#access']) {
          if (!isset($element[$group_id])) {
            $element[$group_id] = [
              '#type' => 'details',
              '#title' => $group['title'],
              '#group' => 'tabs',
            ];
          }

          $element[$field_id]['#group'] = $group_id;
        }
      }
    }
  }

  /**
   * Restructure form elements to the fieldset view.
   */
  public function referenceFormFieldsetRestructure(array &$element) {
    $field_groups = $this->getGroupedFields();

    // Place all fieldset's under the title.
    $weight = $element['title']['#weight'];

    foreach ($field_groups as $group_id => $group) {
      foreach ($group['elements'] as $field_id) {
        if (isset($element[$field_id]) && $element[$field_id]['#access']) {
          if (!isset($element[$group_id])) {
            $element[$group_id] = [
              '#type' => 'details',
              '#title' => $group['title'],
              '#weight' => ++$weight,
            ];
          }

          $element[$group_id][$field_id] = $element[$field_id];

          unset($element[$field_id]);
        }
      }
    }
  }

  /**
   * Override fields attributes for refrence view.
   *
   * @param array $element
   *   Element render array.
   * @param string $entity_bundle
   *   Entity bundle indentifier.
   */
  public function referenceViewFieldsOverride(array &$element, $entity_bundle) {
    /** @var \Drupal\bibcite_entity\Entity\ReferenceTypeInterface $bundle_entity */
    $bundle_entity = $this->getEntityBundleObject($entity_bundle);
    if ($bundle_entity) {
      foreach ($bundle_entity->getFields() as $field_name => $field_config) {
        if (isset($element[$field_name])) {
          if (!$field_config['visible']) {
            $element[$field_name]['#access'] = FALSE;
          }

          if (!empty($field_config['label'])) {
            $element[$field_name]['#title'] = $field_config['label'];
          }
        }
      }
    }
  }

  /**
   * Get array of grouped fields.
   */
  protected function getGroupedFields() {
    return [
      'authors' => [
        'title' => $this->t('Authors'),
        'elements' => [
          'author',
        ],
      ],
      'abstract' => [
        'title' => $this->t('Abstract'),
        'elements' => [
          'bibcite_abst_e',
        ],
      ],
      'publication' => [
        'title' => $this->t('Publication'),
        'elements' => [
          'bibcite_year',
          'bibcite_secondary_title',
          'bibcite_volume',
          'bibcite_edition',
          'bibcite_section',
          'bibcite_issue',
          'bibcite_number_of_volumes',
          'bibcite_number',
          'bibcite_pages',
          'bibcite_date',
          'bibcite_type_of_work',
          'bibcite_lang',
          'bibcite_reprint_edition',
        ],
      ],
      'publisher' => [
        'title' => $this->t('Publisher'),
        'elements' => [
          'bibcite_publisher',
          'bibcite_place_published',
        ],
      ],
      'identifiers' => [
        'title' => $this->t('Identifiers'),
        'elements' => [
          'bibcite_issn',
          'bibcite_isbn',
          'bibcite_accession_number',
          'bibcite_call_number',
          'bibcite_other_number',
          'bibcite_citekey',
          'bibcite_pmid',
        ],
      ],
      'locators' => [
        'title' => $this->t('Locators'),
        'elements' => [
          'bibcite_url',
          'bibcite_doi',
        ],
      ],
      'notes' => [
        'title' => $this->t('Notes'),
        'elements' => [
          'bibcite_notes',
          'bibcite_research_notes',
        ],
      ],
      'alternate_titles' => [
        'title' => $this->t('Alternative titles'),
        'elements' => [
          'bibcite_tertiary_title',
          'bibcite_short_title',
          'bibcite_alternate_title',
          'bibcite_translated_title',
          'bibcite_original_publication',
        ],
      ],
      'other' => [
        'title' => $this->t('Other'),
        'elements' => [
          'keywords',
          'bibcite_other_author_affiliations',
          'bibcite_abst_f',
          'bibcite_custom1',
          'bibcite_custom2',
          'bibcite_custom3',
          'bibcite_custom4',
          'bibcite_custom5',
          'bibcite_custom6',
          'bibcite_custom7',
          'bibcite_remote_db_name',
          'bibcite_remote_db_provider',
          'bibcite_auth_address',
          'bibcite_label',
          'bibcite_access_date',
          'bibcite_refereed',
        ],
      ],
    ];
  }

  /**
   * Find and load entity bundle object.
   *
   * @param string $bundle_id
   *   Entity bundle identifier.
   *
   * @return \Drupal\Core\Entity\EntityInterface|null
   *   Bundle object or NULL
   */
  protected function getEntityBundleObject($bundle_id) {
    $storage = $this->entityTypeManager->getStorage('bibcite_reference_type');

    $ids = $storage->getQuery()
      ->condition('id', $bundle_id)
      ->execute();

    $id = reset($ids);
    return $storage->load($id);
  }

  /**
   * Set a value of attribute for field element.
   *
   * @param array $element
   *   Field element array.
   * @param string $attribute
   *   Attribute name.
   * @param mixed $value
   *   Attribute value.
   */
  protected function setFormElementParameter(array &$element, $attribute, $value) {
    if (isset($element['widget']['target_id'])) {
      $element['widget']['target_id'][$attribute] = $value;
    }
    else {
      foreach (Element::children($element['widget']) as $element_value_key) {
        $element['widget'][$element_value_key]['value'][$attribute] = $value;
      }
    }
  }

}
