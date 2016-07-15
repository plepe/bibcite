<?php

namespace Drupal\bibcite_entity\Entity;

use Drupal\Core\Entity\EntityStorageInterface;
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
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\bibcite_entity\ContributorListBuilder",
 *     "views_data" = "Drupal\views\EntityViewsData",
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
 *     },
 *   },
 *   base_table = "bibcite_contributor",
 *   admin_permission = "administer contributor entities",
 *   fieldable = FALSE,
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "name",
 *     "uuid" = "uuid",
 *     "langcode" = "langcode",
 *   },
 *   links = {
 *     "canonical" = "/admin/content/bibliography/contributor/{bibcite_contributor}",
 *     "add-form" = "/admin/content/bibliography/contributor/add",
 *     "edit-form" = "/admin/content/bibliography/contributor/{bibcite_contributor}/edit",
 *     "delete-form" = "/admin/content/bibliography/contributor/{bibcite_contributor}/delete",
 *     "collection" = "/admin/content/bibliography/contributor",
 *   },
 * )
 */
class Contributor extends ContentEntityBase implements ContributorInterface {

  use EntityChangedTrait;

  /**
   * {@inheritdoc}
   */
  public function preSave(EntityStorageInterface $storage) {
    $this->generateName();

    parent::preSave($storage);
  }

  /**
   * {@inheritdoc}
   */
  public function getName() {
    return $this->get('name')->value;
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
  public function getPostfix() {
    return $this->get('postfix')->value;
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
  public function setPostfix($postfix) {
    $this->set('postfix', $postfix);
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
      ->setDescription(t('Can be automatically created from another fields.'))
      ->setDefaultValue('')
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'string',
        'weight' => 1,
      ]);

    $fields['suffix'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Suffix'))
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

    $fields['postfix'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Postfix'))
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

  /**
   * Generate name from another fields.
   */
  protected function generateName() {
    $last_name = $this->getLastName();
    $first_name = $this->getFirstName();
    $suffix = $this->getSuffix();
    $prefix = $this->getPostfix();

    $name = $last_name;

    if ($prefix && $last_name && $first_name && $suffix) {
      $name = vsprintf('%s %s %s %s', [
        $prefix, $last_name, $first_name, $suffix,
      ]);
    }
    elseif ($prefix && $last_name && $first_name) {
      $name = vsprintf('%s %s %s', [
        $prefix, $last_name, $first_name,
      ]);
    }
    elseif ($last_name && $first_name && $suffix) {
      $name = vsprintf('%s %s %s', [
        $last_name, $first_name, $suffix,
      ]);
    }
    elseif ($prefix && $last_name) {
      $name = vsprintf('%s %s', [
        $prefix, $last_name,
      ]);
    }
    elseif ($last_name && $suffix) {
      $name = vsprintf('%s %s', [
        $last_name, $suffix,
      ]);
    }
    elseif ($last_name && $first_name) {
      $name = vsprintf('%s %s', [
        $last_name, $first_name,
      ]);
    }

    $this->set('name', $name);
  }

}
