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
   * {@inheritdoc}
   */
  public function cite($style = NULL) {
    // @todo Make a better dependency injection.
    $styler = \Drupal::service('bibcite.styler');
    $serializer = \Drupal::service('serializer');

    $data = $serializer->normalize($this, 'csl');
    return $styler->render($data, $style);
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

    $csl_fields = [
      'bibcite_accessed' => [
        'type' => 'datetime',
        'label' => t('Accessed'),
      ],
      'bibcite_container' => [
        'type' => 'datetime',
        'label' => t('Container'),
      ],
      'bibcite_event_date' => [
        'type' => 'datetime',
        'label' => t('Event Date'),
      ],
      'bibcite_issued' => [
        'type' => 'datetime',
        'label' => t('Issued'),
      ],
      'bibcite_original_date' => [
        'type' => 'datetime',
        'label' => t('Original Date'),
      ],
      'bibcite_submitted' => [
        'type' => 'datetime',
        'label' => t('Submitted'),
      ],
      'bibcite_chapter_number' => [
        'type' => 'integer',
        'label' => t('Chapter Number'),
      ],
      'bibcite_collection_number' => [
        'type' => 'integer',
        'label' => t('Collection Number'),
      ],
      'bibcite_edition' => [
        'type' => 'integer',
        'label' => t('Edition'),
      ],
      'bibcite_issue' => [
        'type' => 'integer',
        'label' => t('Issue'),
      ],
      'bibcite_number' => [
        'type' => 'integer',
        'label' => t('Number'),
      ],
      'bibcite_number_of_pages' => [
        'type' => 'integer',
        'label' => t('Number of Pages'),
      ],
      'bibcite_number_of_volumes' => [
        'type' => 'integer',
        'label' => t('Number of Volumes'),
      ],
      'bibcite_volume' => [
        'type' => 'integer',
        'label' => t('Volume'),
      ],
      'bibcite_collection_editor' => [
        'type' => 'string',
        'label' => t('Collection Editor'),
      ],
      'bibcite_composer' => [
        'type' => 'string',
        'label' => t('Composer'),
      ],
      'bibcite_container_author' => [
        'type' => 'string',
        'label' => t('Container Author'),
      ],
      'bibcite_director' => [
        'type' => 'string',
        'label' => t('Director'),
      ],
      'bibcite_editor' => [
        'type' => 'string',
        'label' => t('Editor'),
      ],
      'bibcite_editorial_director' => [
        'type' => 'string',
        'label' => t('Editorial Director'),
      ],
      'bibcite_illustrator' => [
        'type' => 'string',
        'label' => t('Illustrator'),
      ],
      'bibcite_interviewer' => [
        'type' => 'string',
        'label' => t('Interviewer'),
      ],
      'bibcite_original_author' => [
        'type' => 'string',
        'label' => t('Original Author'),
      ],
      'bibcite_recipient' => [
        'type' => 'string',
        'label' => t('Recipient'),
      ],
      'bibcite_reviewed_author' => [
        'type' => 'string',
        'label' => t('Reviewed Author'),
      ],
      'bibcite_translator' => [
        'type' => 'string',
        'label' => t('Translator'),
      ],
      'bibcite_abstract' => [
        'type' => 'string',
        'label' => t('Abstract'),
      ],
      'bibcite_annote' => [
        'type' => 'string',
        'label' => t('Annote'),
      ],
      'bibcite_archive' => [
        'type' => 'string',
        'label' => t('Archive'),
      ],
      'bibcite_archive_location' => [
        'type' => 'string',
        'label' => t('Archive Location'),
      ],
      'bibcite_archive_place' => [
        'type' => 'string',
        'label' => t('Archive Place'),
      ],
      'bibcite_authority' => [
        'type' => 'string',
        'label' => t('Authority'),
      ],
      'bibcite_call_number' => [
        'type' => 'string',
        'label' => t('Call Number'),
      ],
      'bibcite_citation_label' => [
        'type' => 'string',
        'label' => t('Citation Label'),
      ],
      'bibcite_citation_number' => [
        'type' => 'string',
        'label' => t('Citation Number'),
      ],
      'bibcite_collection_title' => [
        'type' => 'string',
        'label' => t('Collection Title'),
      ],
      'bibcite_container_title' => [
        'type' => 'string',
        'label' => t('Container Title'),
      ],
      'bibcite_container_title_short' => [
        'type' => 'string',
        'label' => t('Container Title Short'),
      ],
      'bibcite_dimensions' => [
        'type' => 'string',
        'label' => t('Dimensions'),
      ],
      'bibcite_doi' => [
        'type' => 'string',
        'label' => t('DOI'),
      ],
      'bibcite_event' => [
        'type' => 'string',
        'label' => t('Event'),
      ],
      'bibcite_event_place' => [
        'type' => 'string',
        'label' => t('Event Place'),
      ],
      'bibcite_first_reference_note_number' => [
        'type' => 'string',
        'label' => t('First Reference Note Number'),
      ],
      'bibcite_genre' => [
        'type' => 'string',
        'label' => t('Genre'),
      ],
      'bibcite_isbn' => [
        'type' => 'string',
        'label' => t('ISBN'),
      ],
      'bibcite_issn' => [
        'type' => 'string',
        'label' => t('ISSN'),
      ],
      'bibcite_jurisdiction' => [
        'type' => 'string',
        'label' => t('Jurisdiction'),
      ],
      'bibcite_locator' => [
        'type' => 'string',
        'label' => t('Locator'),
      ],
      'bibcite_medium' => [
        'type' => 'string',
        'label' => t('Medium'),
      ],
      'bibcite_note' => [
        'type' => 'string',
        'label' => t('Note'),
      ],
      'bibcite_original_publisher' => [
        'type' => 'string',
        'label' => t('Original Publisher'),
      ],
      'bibcite_original_publisher_place' => [
        'type' => 'string',
        'label' => t('Original Publisher Place'),
      ],
      'bibcite_original_title' => [
        'type' => 'string',
        'label' => t('Original Title'),
      ],
      'bibcite_page' => [
        'type' => 'string',
        'label' => t('Page'),
      ],
      'bibcite_page_first' => [
        'type' => 'string',
        'label' => t('Page First'),
      ],
      'bibcite_pmid' => [
        'type' => 'string',
        'label' => t('PMID'),
      ],
      'bibcite_pmcid' => [
        'type' => 'string',
        'label' => t('PMCID'),
      ],
      'bibcite_publisher' => [
        'type' => 'string',
        'label' => t('Publisher'),
      ],
      'bibcite_publisher_place' => [
        'type' => 'string',
        'label' => t('Publisher Place'),
      ],
      'bibcite_references' => [
        'type' => 'string',
        'label' => t('References'),
      ],
      'bibcite_reviewed_title' => [
        'type' => 'string',
        'label' => t('Reviewed Title'),
      ],
      'bibcite_scale' => [
        'type' => 'string',
        'label' => t('Scale'),
      ],
      'bibcite_section' => [
        'type' => 'string',
        'label' => t('Section'),
      ],
      'bibcite_source' => [
        'type' => 'string',
        'label' => t('Source'),
      ],
      'bibcite_status' => [
        'type' => 'string',
        'label' => t('Status'),
      ],
      'bibcite_title_short' => [
        'type' => 'string',
        'label' => t('Title Short'),
      ],
      'bibcite_url' => [
        'type' => 'string',
        'label' => t('URL'),
      ],
      'bibcite_version' => [
        'type' => 'string',
        'label' => t('Version'),
      ],
      'bibcite_year_suffix' => [
        'type' => 'string',
        'label' => t('Year Suffix'),
      ],
    ];

    $weight = 5;

    foreach ($csl_fields as $field_name => $field_info) {
      $schema_type = $field_info['type'];
      $label = $field_info['label'];

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

      $fields[$field_name] = $definition;
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
