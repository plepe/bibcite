<?php

namespace Drupal\sc_pub_entity\Entity;

use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Field\FieldStorageDefinitionInterface;
use Drupal\datetime\Plugin\Field\FieldType\DateTimeItem;
use Drupal\sc_pub\CslKeyConverter;

/**
 * Defines the Bibliography entity.
 *
 * @ingroup sc_pub_entity
 *
 * @ContentEntityType(
 *   id = "bibliography",
 *   label = @Translation("Bibliography"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\sc_pub_entity\BibliographyListBuilder",
 *     "views_data" = "Drupal\views\EntityViewsData",
 *
 *     "form" = {
 *       "default" = "Drupal\sc_pub_entity\Form\BibliographyForm",
 *       "delete" = "Drupal\sc_pub_entity\Form\BibliographyDeleteForm",
 *     },
 *     "access" = "Drupal\sc_pub_entity\BibliographyAccessControlHandler",
 *     "route_provider" = {
 *       "html" = "Drupal\sc_pub_entity\BibliographyHtmlRouteProvider",
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

    $fields['title'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Title'))
      ->setDescription(t('The title of the Bibliography.'))
      ->setSettings(array(
        'text_processing' => 0,
      ))
      ->setDefaultValue('')
      ->setDisplayOptions('form', array(
        'type' => 'string_textfield',
        'weight' => 1,
      ));

    $fields[CslKeyConverter::normalizeKey('author')] = BaseFieldDefinition::create('sc_pub_contributor')
      ->setLabel(t('Author'))
      ->setCardinality(FieldStorageDefinitionInterface::CARDINALITY_UNLIMITED)
      ->setDisplayOptions('form', [
        'type' => 'sc_pub_contributor_widget',
        'weight' => 2,
      ])
      ->setDisplayOptions('view', [
        'type' => 'sc_pub_contributor_label',
        'weight' => 2,
      ]);

    $fields[CslKeyConverter::normalizeKey('keywords')] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Keywords'))
      ->setSetting('target_type', 'sc_pub_keyword')
      ->setCardinality(FieldStorageDefinitionInterface::CARDINALITY_UNLIMITED)
      ->setDisplayOptions('form', array(
        'type' => 'entity_reference_autocomplete_tags',
        'weight' => 3,
        'settings' => array(
          'match_operator' => 'CONTAINS',
          'size' => '60',
          'placeholder' => '',
        ),
      ))
      ->setDisplayOptions('view', [
        'type' => 'entity_reference_label',
        'weight' => 3,
      ]);

    /*
     * CSL fields.
     */

    $weight = 5;

    $date_fields = [
      'accessed' => 'Accessed',
      'container' => 'Container',
      'event-date' => 'Event Date',
      'issued' => 'Issued',
      'original-date' => 'Original Date',
      'submitted' => 'Submitted',
    ];

    foreach ($date_fields as $key => $label) {
      $fields[CslKeyConverter::normalizeKey($key)] = BaseFieldDefinition::create('datetime')
        ->setLabel($label)
        ->setDefaultValue(NULL)
        ->setSetting('datetime_type', DateTimeItem::DATETIME_TYPE_DATE)
        ->setDisplayOptions('form', [
          'type' => 'date',
          'datetime_type' => DateTimeItem::DATETIME_TYPE_DATE,
          'weight' => $weight,
        ])
        ->setDisplayOptions('view', [
          'type' => 'datetime_default',
          'weight' => $weight,
        ]);
      $weight++;
    }

    $number_fields = [
      'chapter-number' => 'Chapter Number',
      'collection-number' => 'Collection Number',
      'edition' => 'Edition',
      'issue' => 'Issue',
      'number' => 'Number',
      'number-of-pages' => 'Number of Pages',
      'number-of-volumes' => 'Number of Volumes',
      'volume' => 'Volume',
    ];

    foreach ($number_fields as $key => $label) {
      $fields[CslKeyConverter::normalizeKey($key)] = BaseFieldDefinition::create('integer')
        ->setName($key)
        ->setLabel($label)
        ->setDefaultValue(NULL)
        ->setDisplayOptions('form', [
          'type' => 'number',
          'weight' => $weight,
        ])
        ->setDisplayOptions('view', [
          'type' => 'number_integer',
          'weight' => $weight,
        ]);
      $weight++;
    }

    $string_fields = [
      'collection-editor' => 'Collection Editor',
      'composer' => 'Composer',
      'container-author' => 'Container Author',
      'director' => 'Director',
      'editor' => 'Editor',
      'editorial-director' => 'Editorial Director',
      'illustrator' => 'Illustrator',
      'interviewer' => 'Interviewer',
      'original-author' => 'Original Author',
      'recipient' => 'Recipient',
      'reviewed-author' => 'Reviewed Author',
      'translator' => 'Translator',
      'abstract' => 'Abstract',
      'annote' => 'Annote',
      'archive' => 'Archive',
      'archive_location' => 'Archive Location',
      'archive-place' => 'Archive Place',
      'authority' => 'Authority',
      'call-number' => 'Call Number',
      'citation-label' => 'Citation Label',
      'citation-number' => 'Citation Number',
      'collection-title' => 'Collection Title',
      'container-title' => 'Container Title',
      'container-title-short' => 'Container Title Short',
      'dimensions' => 'Dimensions',
      'DOI' => 'DOI',
      'event' => 'Event',
      'event-place' => 'Event Place',
      'first-reference-note-number' => 'First Reference Note Number',
      'genre' => 'Genre',
      'ISBN' => 'ISBN',
      'ISSN' => 'ISSN',
      'jurisdiction' => 'Jurisdiction',
      'locator' => 'Locator',
      'medium' => 'Medium',
      'note' => 'Note',
      'original-publisher' => 'Original Publisher',
      'original-publisher-place' => 'Original Publisher Place',
      'original-title' => 'Original Title',
      'page' => 'Page',
      'page-first' => 'Page First',
      'PMID' => 'PMID',
      'PMCID' => 'PMCID',
      'publisher' => 'Publisher',
      'publisher-place' => 'Publisher Place',
      'references' => 'References',
      'reviewed-title' => 'Reviewed Title',
      'scale' => 'Scale',
      'section' => 'Section',
      'source' => 'Source',
      'status' => 'Status',
      'title-short' => 'Title Short',
      'URL' => 'URL',
      'version' => 'Version',
      'year-suffix' => 'Year Suffix',
    ];

    foreach ($string_fields as $key => $label) {
      $fields[CslKeyConverter::normalizeKey($key)] = BaseFieldDefinition::create('string')
        ->setLabel($label)
        ->setSettings(array(
          'text_processing' => 0,
        ))
        ->setDefaultValue('')
        ->setDisplayOptions('view', [
          'label' => 'above',
          'type' => 'string',
          'weight' => $weight,
        ])
        ->setDisplayOptions('form', [
          'type' => 'string_textfield',
          'weight' => $weight,
        ]);
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
