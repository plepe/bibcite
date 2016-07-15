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
 *     "view_builder" = "Drupal\bibcite_entity\Entity\BibliographyViewBuilder",
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

    $weight = 5;

    $default_string = function($label) use (&$weight) {
      $weight++;
      return BaseFieldDefinition::create('string')
        ->setLabel($label)
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
    };

    $default_datetime = function($label) use (&$weight) {
      $weight++;
      return BaseFieldDefinition::create('datetime')
        ->setLabel($label)
        ->setDefaultValue(NULL)
        ->setSettings([
          'datetime_type' => DateTimeItem::DATETIME_TYPE_DATE,
        ])
        ->setDisplayOptions('form', [
          'type' => 'date',
          'weight' => $weight,
        ])
        ->setDisplayOptions('view', [
          'type' => 'datetime_default',
          'settings' => [
            'format_type' => 'html_date',
          ],
          'weight' => $weight,
        ]);
    };

    $default_integer = function ($label) use (&$weight) {
      $weight++;
      return BaseFieldDefinition::create('integer')
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
    };

    /*
     * Date fields.
     */
    $fields['bibcite_accessed'] = $default_datetime(t('Accessed'));
    $fields['bibcite_container'] = $default_datetime(t('Container'));
    $fields['bibcite_event_date'] = $default_datetime(t('Event Date'));
    $fields['bibcite_issued'] = $default_datetime(t('Issued'));
    $fields['bibcite_original_date'] = $default_datetime(t('Original Date'));
    $fields['bibcite_submitted'] = $default_datetime(t('Submitted'));

    /*
     * Number fields.
     */
    $fields['bibcite_chapter_number'] = $default_integer(t('Chapter Number'));
    $fields['bibcite_collection_number'] = $default_integer(t('Collection Number'));
    $fields['bibcite_edition'] = $default_integer(t('Edition'));
    $fields['bibcite_issue'] = $default_integer(t('Issue'));
    $fields['bibcite_number'] = $default_integer(t('Number'));
    $fields['bibcite_number_of_pages'] = $default_integer(t('Number of Pages'));
    $fields['bibcite_number_of_volumes'] = $default_integer(t('Number of Volumes'));
    $fields['bibcite_volume'] = $default_integer(t('Volume'));

    /*
     * String fields.
     */
    $fields['bibcite_collection_editor'] = $default_string(t('Collection Editor'));
    $fields['bibcite_composer'] = $default_string(t('Composer'));
    $fields['bibcite_container_author'] = $default_string(t('Container Author'));
    $fields['bibcite_director'] = $default_string(t('Director'));
    $fields['bibcite_editor'] = $default_string(t('Editor'));
    $fields['bibcite_editorial_director'] = $default_string(t('Editorial Director'));
    $fields['bibcite_illustrator'] = $default_string(t('Illustrator'));
    $fields['bibcite_interviewer'] = $default_string(t('Interviewer'));
    $fields['bibcite_original_author'] = $default_string(t('Original Author'));
    $fields['bibcite_recipient'] = $default_string(t('Recipient'));
    $fields['bibcite_reviewed_author'] = $default_string(t('Reviewed Author'));
    $fields['bibcite_translator'] = $default_string(t('Translator'));
    $fields['bibcite_abstract'] = $default_string(t('Abstract'));
    $fields['bibcite_annote'] = $default_string(t('Annote'));
    $fields['bibcite_archive'] = $default_string(t('Archive'));
    $fields['bibcite_archive_location'] = $default_string(t('Archive Location'));
    $fields['bibcite_archive_place'] = $default_string(t('Archive Place'));
    $fields['bibcite_authority'] = $default_string(t('Authority'));
    $fields['bibcite_call_number'] = $default_string(t('Call Number'));
    $fields['bibcite_citation_label'] = $default_string(t('Citation Label'));
    $fields['bibcite_citation_number'] = $default_string(t('Citation Number'));
    $fields['bibcite_collection_title'] = $default_string(t('Collection Title'));
    $fields['bibcite_container_title'] = $default_string(t('Container Title'));
    $fields['bibcite_container_title_short'] = $default_string(t('Container Title Short'));
    $fields['bibcite_dimensions'] = $default_string(t('Dimensions'));
    $fields['bibcite_doi'] = $default_string(t('DOI'));
    $fields['bibcite_event'] = $default_string(t('Event'));
    $fields['bibcite_event_place'] = $default_string(t('Event Place'));
    $fields['bibcite_first_reference_note_number'] = $default_string(t('First Reference Note Number'));
    $fields['bibcite_genre'] = $default_string(t('Genre'));
    $fields['bibcite_isbn'] = $default_string(t('ISBN'));
    $fields['bibcite_issn'] = $default_string(t('ISSN'));
    $fields['bibcite_jurisdiction'] = $default_string(t('Jurisdiction'));
    $fields['bibcite_locator'] = $default_string(t('Locator'));
    $fields['bibcite_medium'] = $default_string(t('Medium'));
    $fields['bibcite_note'] = $default_string(t('Note'));
    $fields['bibcite_original_publisher'] = $default_string(t('Original Publisher'));
    $fields['bibcite_original_publisher_place'] = $default_string(t('Original Publisher Place'));
    $fields['bibcite_original_title'] = $default_string(t('Original Title'));
    $fields['bibcite_page'] = $default_string(t('Page'));
    $fields['bibcite_page_first'] = $default_string(t('Page First'));
    $fields['bibcite_pmid'] = $default_string(t('PMID'));
    $fields['bibcite_pmcid'] = $default_string(t('PMCID'));
    $fields['bibcite_publisher'] = $default_string(t('Publisher'));
    $fields['bibcite_publisher_place'] = $default_string(t('Publisher Place'));
    $fields['bibcite_references'] = $default_string(t('References'));
    $fields['bibcite_reviewed_title'] = $default_string(t('Reviewed Title'));
    $fields['bibcite_scale'] = $default_string(t('Scale'));
    $fields['bibcite_section'] = $default_string(t('Section'));
    $fields['bibcite_source'] = $default_string(t('Source'));
    $fields['bibcite_status'] = $default_string(t('Status'));
    $fields['bibcite_title_short'] = $default_string(t('Title Short'));
    $fields['bibcite_url'] = $default_string(t('URL'));
    $fields['bibcite_version'] = $default_string(t('Version'));
    $fields['bibcite_year_suffix'] = $default_string(t('Year Suffix'));

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
