<?php

namespace Drupal\sc_pub\Entity;

use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityTypeInterface;

/**
 * Defines the Contributor entity.
 *
 * @ingroup sc_pub
 *
 * @ContentEntityType(
 *   id = "sc_pub_contributor",
 *   label = @Translation("Contributor"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\sc_pub\ContributorListBuilder",
 *
 *     "form" = {
 *       "default" = "Drupal\sc_pub\Form\ContributorForm",
 *       "add" = "Drupal\sc_pub\Form\ContributorForm",
 *       "edit" = "Drupal\sc_pub\Form\ContributorForm",
 *       "delete" = "Drupal\sc_pub\Form\ContributorDeleteForm",
 *     },
 *     "access" = "Drupal\sc_pub\ContributorAccessControlHandler",
 *     "route_provider" = {
 *       "html" = "Drupal\sc_pub\ContributorHtmlRouteProvider",
 *     },
 *   },
 *   base_table = "sc_pub_contributor",
 *   admin_permission = "administer contributor entities",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "name",
 *     "uuid" = "uuid",
 *     "langcode" = "langcode",
 *   },
 *   links = {
 *     "canonical" = "/admin/structure/sc_pub_contributor/{sc_pub_contributor}",
 *     "add-form" = "/admin/structure/sc_pub_contributor/add",
 *     "edit-form" = "/admin/structure/sc_pub_contributor/{sc_pub_contributor}/edit",
 *     "delete-form" = "/admin/structure/sc_pub_contributor/{sc_pub_contributor}/delete",
 *     "collection" = "/admin/structure/sc_pub_contributor",
 *   },
 *   field_ui_base_route = "sc_pub_contributor.settings"
 * )
 */
class Contributor extends ContentEntityBase implements ContributorInterface {

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

    $fields['name'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Name'))
      ->setDefaultValue('');

    $fields['suffix'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Suffix'))
      ->setDefaultValue('');

    $fields['first_name'] = BaseFieldDefinition::create('string')
      ->setLabel(t('First name'))
      ->setDefaultValue('');

    $fields['last_name'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Last name'))
      ->setDefaultValue('');

    $fields['postfix'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Postfix'))
      ->setDefaultValue('');

    $fields['created'] = BaseFieldDefinition::create('created')
      ->setLabel(t('Created'))
      ->setDescription(t('The time that the entity was created.'));

    $fields['changed'] = BaseFieldDefinition::create('changed')
      ->setLabel(t('Changed'))
      ->setDescription(t('The time that the entity was last edited.'));

    return $fields;
  }

}
