<?php

namespace Drupal\bibcite_entity\Entity;

use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityTypeInterface;

/**
 * Defines the Contributor entity.
 *
 * @ingroup bibcite_entity
 *
 * @ContentEntityType(
 *   id = "bibcite_contributor",
 *   label = @Translation("Contributor"),
 *   handlers = {
 *     "storage" = "Drupal\bibcite_entity\ContributorStorage",
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\bibcite_entity\ContributorListBuilder",
 *     "views_data" = "Drupal\bibcite_entity\ContributorViewsData",
 *
 *     "form" = {
 *       "default" = "Drupal\bibcite_entity\Form\ContributorForm",
 *       "add" = "Drupal\bibcite_entity\Form\ContributorForm",
 *       "edit" = "Drupal\bibcite_entity\Form\ContributorForm",
 *       "delete" = "Drupal\bibcite_entity\Form\ContributorDeleteForm",
 *     },
 *     "access" = "Drupal\bibcite_entity\ContributorAccessControlHandler",
 *     "route_provider" = {
 *       "html" = "Drupal\bibcite_entity\ContributorHtmlRouteProvider",
 *       "delete-multiple" = "Drupal\bibcite_entity\DeleteMultipleRouterProvider",
 *       "merge" = "Drupal\bibcite_entity\MergeRouteProvider",
 *     },
 *   },
 *   base_table = "bibcite_contributor",
 *   admin_permission = "administer contributor entities",
 *   fieldable = FALSE,
 *   entity_keys = {
 *     "id" = "id",
 *     "uuid" = "uuid",
 *     "langcode" = "langcode",
 *   },
 *   links = {
 *     "canonical" = "/bibcite/contributor/{bibcite_contributor}",
 *     "edit-form" = "/bibcite/contributor/{bibcite_contributor}/edit",
 *     "delete-form" = "/bibcite/contributor/{bibcite_contributor}/delete",
 *     "bibcite-merge-form" = "/bibcite/contributor/{bibcite_contributor}/merge",
 *     "add-form" = "/admin/content/bibcite/contributor/add",
 *     "bibcite-merge-multiple-form" = "/admin/content/bibcite/contributor/merge",
 *     "delete-multiple-form" = "/admin/content/bibcite/contributor/delete",
 *     "collection" = "/admin/content/bibcite/contributor",
 *   },
 * )
 */
class Contributor extends ContentEntityBase implements ContributorInterface {

  use EntityChangedTrait;

  /**
   * {@inheritdoc}
   */
  public function label() {
    return $this->getName();
  }

  /**
   * {@inheritdoc}
   */
  public function getName() {
    return $this->get('name')->getValue();
  }

  /**
   * {@inheritdoc}
   */
  public function getFirstName() {
    return $this->get('first_name')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function getLastName() {
    return $this->get('last_name')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function getSuffix() {
    return $this->get('suffix')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function getPrefix() {
    return $this->get('prefix')->value;
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
  public function setName($name) {
    $this->set('name', $name);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function setFirstName($first_name) {
    $this->set('first_name', $first_name);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function setLastName($last_name) {
    $this->set('last_name', $last_name);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function setSuffix($suffix) {
    $this->set('suffix', $suffix);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function setPrefix($prefix) {
    $this->set('prefix', $prefix);
    return $this;
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
      ->setComputed(TRUE)
      ->setReadOnly(FALSE)
      ->setQueryable(FALSE)
      ->setClass('\Drupal\bibcite_entity\ContributorName');

    $fields['prefix'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Prefix'))
      ->setDefaultValue('')
      ->setDisplayOptions('form', array(
        'type' => 'string_textfield',
        'weight' => 1,
      ))
      ->setDisplayOptions('view', [
        'label' => 'inline',
        'type' => 'string',
        'weight' => 2,
      ]);

    $fields['first_name'] = BaseFieldDefinition::create('string')
      ->setLabel(t('First name'))
      ->setDefaultValue('')
      ->setDisplayOptions('form', array(
        'type' => 'string_textfield',
        'weight' => 2,
      ))
      ->setDisplayOptions('view', [
        'label' => 'inline',
        'type' => 'string',
        'weight' => 3,
      ]);

    $fields['last_name'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Last name'))
      ->setDefaultValue('')
      ->setRequired(TRUE)
      ->setDisplayOptions('form', array(
        'type' => 'string_textfield',
        'weight' => 3,
      ))
      ->setDisplayOptions('view', [
        'label' => 'inline',
        'type' => 'string',
        'weight' => 4,
      ]);

    $fields['suffix'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Suffix'))
      ->setDefaultValue('')
      ->setDisplayOptions('form', array(
        'type' => 'string_textfield',
        'weight' => 4,
      ))
      ->setDisplayOptions('view', [
        'label' => 'inline',
        'type' => 'string',
        'weight' => 5,
      ]);

    $fields['created'] = BaseFieldDefinition::create('created')
      ->setLabel(t('Created'))
      ->setDescription(t('The time that the entity was created.'));

    $fields['changed'] = BaseFieldDefinition::create('changed')
      ->setLabel(t('Changed'))
      ->setDescription(t('The time that the entity was last edited.'));

    return $fields;
  }

}
