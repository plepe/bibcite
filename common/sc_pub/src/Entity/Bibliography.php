<?php

namespace Drupal\sc_pub\Entity;

use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Field\FieldStorageDefinitionInterface;
use Drupal\user\UserInterface;

/**
 * Defines the Bibliography entity.
 *
 * @ingroup sc_pub
 *
 * @ContentEntityType(
 *   id = "bibliography",
 *   label = @Translation("Bibliography"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\sc_pub\BibliographyListBuilder",
 *
 *     "form" = {
 *       "default" = "Drupal\sc_pub\Form\BibliographyForm",
 *       "delete" = "Drupal\sc_pub\Form\BibliographyDeleteForm",
 *     },
 *     "access" = "Drupal\sc_pub\BibliographyAccessControlHandler",
 *     "route_provider" = {
 *       "html" = "Drupal\sc_pub\BibliographyHtmlRouteProvider",
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
 *     "canonical" = "/admin/structure/bibliography/{bibliography}",
 *     "add-form" = "/admin/structure/bibliography/add",
 *     "edit-form" = "/admin/structure/bibliography/{bibliography}/edit",
 *     "delete-form" = "/admin/structure/bibliography/{bibliography}/delete",
 *     "collection" = "/admin/structure/bibliography",
 *   },
 * )
 */
class Bibliography extends ContentEntityBase implements BibliographyInterface {

  use EntityChangedTrait;

  /**
   * {@inheritdoc}
   */
  public function getName() {
    return $this->get('name')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setName($name) {
    $this->set('name', $name);
    return $this;
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

    $fields['author'] = BaseFieldDefinition::create('sc_pub_contributor')
      ->setLabel(t('Author'))
      ->setCardinality(FieldStorageDefinitionInterface::CARDINALITY_UNLIMITED)
      ->setDisplayOptions('form', [
        'type' => 'sc_pub_contributor_widget',
        'weight' => 2,
      ]);

    $fields['keywords'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Keywords'))
      ->setSetting('target_type', 'sc_pub_keyword')
      ->setCardinality(FieldStorageDefinitionInterface::CARDINALITY_UNLIMITED)
      ->setDisplayOptions('form', array(
        'type' => 'entity_reference_autocomplete_tags',
        'weight' => 3,
        'settings' => array(
          'match_operator' => 'CONTAINS',
          'size' => '60',
          //'autocomplete_type' => 'tags',
          'placeholder' => '',
        ),
      ));

    $fields['created'] = BaseFieldDefinition::create('created')
      ->setLabel(t('Created'))
      ->setDescription(t('The time that the entity was created.'));

    $fields['changed'] = BaseFieldDefinition::create('changed')
      ->setLabel(t('Changed'))
      ->setDescription(t('The time that the entity was last edited.'));

    return $fields;
  }

}
