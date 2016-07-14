<?php

namespace Drupal\bibcite_entity\Entity;

use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Field\FieldStorageDefinitionInterface;
use Drupal\datetime\Plugin\Field\FieldType\DateTimeItem;
use Drupal\bibcite\CslKeyConverter;

/**
 * Defines the Bibliography entity.
 *
 * @ingroup bibcite_entity
 *
 * @ContentEntityType(
 *   id = "bibliography",
 *   label = @Translation("Bibliography"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\bibcite_entity\BibliographyListBuilder",
 *     "views_data" = "Drupal\views\EntityViewsData",
 *
 *     "form" = {
 *       "default" = "Drupal\bibcite_entity\Form\BibliographyForm",
 *       "delete" = "Drupal\bibcite_entity\Form\BibliographyDeleteForm",
 *     },
 *     "access" = "Drupal\bibcite_entity\BibliographyAccessControlHandler",
 *     "route_provider" = {
 *       "html" = "Drupal\bibcite_entity\BibliographyHtmlRouteProvider",
 *     },
 *   },
 *   base_table = "bibliography",
 *   admin_permission = "administer bibliography entities",
 *   fieldable = FALSE,
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "title",
 *     "uuid" = "uuid",
 *     "langcode" = "langcode",
 *   },
 *   links = {
 *     "canonical" = "/admin/content/bibliography/{bibliography}",
 *     "add-form" = "/admin/content/bibliography/add",
 *     "edit-form" = "/admin/content/bibliography/{bibliography}/edit",
 *     "delete-form" = "/admin/content/bibliography/{bibliography}/delete",
 *     "collection" = "/admin/content/bibliography",
 *   },
 * )
 */
class Bibliography extends ContentEntityBase implements BibliographyInterface {

  use EntityChangedTrait;

  /**
   * Styler service.
   *
   * @var \Drupal\bibcite\StylerInterface
   */
  protected $styler;

  /**
   * Serializer service.
   *
   * @var \Symfony\Component\Serializer\SerializerInterface
   */
  protected $serializer;

  /**
   * {@inheritdoc}
   */
  public function __construct(array $values, $entity_type, $bundle, array $translations) {
    parent::__construct($values, $entity_type, $bundle, $translations);

    // @todo Make a better dependency injection.
    $this->styler = \Drupal::service('bibcite.styler');
    $this->serializer = \Drupal::service('serializer');
  }

  /**
   * {@inheritdoc}
   */
  public function cite($style = NULL) {
    $data = $this->serializer->normalize($this, 'csl');
    return $this->styler->render($data, $style);
  }

  /**
   * {@inheritdoc}
   */
  public function getCreatedTime() {
    return $this->get('created')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setCreatedTime($timestamp) {
    $this->set('created', $timestamp);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {
    $fields = parent::baseFieldDefinitions($entity_type);

    /** @var \Drupal\bibcite\CslDataProviderInterface $csl_data_provider */
    // Get info about CSL fields from CSL data provider service.
    $csl_data_provider = \Drupal::service('bibcite.csl_data_provider');
    $csl_fields = $csl_data_provider->getFields();

    // Except this three.
    unset($csl_fields['author']);
    unset($csl_fields['title']);
    unset($csl_fields['keywords']);

    /*
     * Main attributes.
     */

    $fields['type'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Type'))
      ->setDescription(t('The type of publication for the Bibliography'))
      ->setSettings([
        'text_processing' => 0,
      ])
      ->setDefaultValue('')
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => 1,
      ])
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'string',
        'weight' => 1,
      ]);

    $fields['title'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Title'))
      ->setDescription(t('The title of the Bibliography.'))
      ->setSettings([
        'text_processing' => 0,
      ])
      ->setDefaultValue('')
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => 2,
      ]);

    $fields['author'] = BaseFieldDefinition::create('bibcite_contributor')
      ->setLabel(t('Author'))
      ->setCardinality(FieldStorageDefinitionInterface::CARDINALITY_UNLIMITED)
      ->setDisplayOptions('form', [
        'type' => 'bibcite_contributor_widget',
        'weight' => 3,
      ])
      ->setDisplayOptions('view', [
        'type' => 'bibcite_contributor_label',
        'weight' => 3,
      ]);

    $fields['keywords'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Keywords'))
      ->setSetting('target_type', 'bibcite_keyword')
      ->setCardinality(FieldStorageDefinitionInterface::CARDINALITY_UNLIMITED)
      ->setDisplayOptions('form', [
        'type' => 'entity_reference_autocomplete_tags',
        'weight' => 4,
        'settings' => [
          'match_operator' => 'CONTAINS',
          'size' => '60',
          'placeholder' => '',
        ],
      ]
      )
      ->setDisplayOptions('view', [
        'type' => 'entity_reference_label',
        'weight' => 4,
      ]);

    /*
     * CSL fields.
     */

    $weight = 5;

    foreach ($csl_fields as $csl_key => $csl_field_info) {
      $schema_field = $csl_field_info['schema_field'];
      $schema_type = $csl_field_info['type'];
      $label = t($csl_field_info['label']);

      $definition = BaseFieldDefinition::create($schema_type)
        ->setLabel($label);

      switch ($schema_type) {
        case 'datetime':
          $definition->setDefaultValue(NULL)
            ->setDisplayOptions('form', [
              'type' => 'date',
              'datetime_type' => DateTimeItem::DATETIME_TYPE_DATE,
              'weight' => $weight,
            ])
            ->setDisplayOptions('view', [
              'type' => 'datetime_default',
              'weight' => $weight,
            ]);
          break;

        case 'string':
          $definition->setDefaultValue('')
            ->setDisplayOptions('view', [
              'label' => 'above',
              'type' => 'string',
              'weight' => $weight,
            ])
            ->setDisplayOptions('form', [
              'type' => 'string_textfield',
              'weight' => $weight,
            ]);
          break;

        case 'integer':
          $definition->setDefaultValue(NULL)
            ->setDisplayOptions('form', [
              'type' => 'number',
              'weight' => $weight,
            ])
            ->setDisplayOptions('view', [
              'type' => 'number_integer',
              'weight' => $weight,
            ]);
          break;
      }

      $fields[$schema_field] = $definition;
      $weight++;
    }

    /*
     * Entity dates.
     */

    $fields['created'] = BaseFieldDefinition::create('created')
      ->setLabel(t('Created'))
      ->setDescription(t('The time that the entity was created.'));

    $fields['changed'] = BaseFieldDefinition::create('changed')
      ->setLabel(t('Changed'))
      ->setDescription(t('The time that the entity was last edited.'));

    return $fields;
  }

}
