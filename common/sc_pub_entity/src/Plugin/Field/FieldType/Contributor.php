<?php

namespace Drupal\sc_pub_entity\Plugin\Field\FieldType;

use Drupal\Core\Field\FieldStorageDefinitionInterface;
use Drupal\Core\Field\Plugin\Field\FieldType\EntityReferenceItem;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\StringTranslation\TranslatableMarkup;
use Drupal\Core\TypedData\DataDefinition;

/**
 * Plugin implementation of the 'sc_pub_contributor' field type.
 *
 * @FieldType(
 *   id = "sc_pub_contributor",
 *   label = @Translation("Contributor"),
 *   description = @Translation("Entity reference with label"),
 *   default_widget = "sc_pub_contributor_widget",
 *   list_class = "\Drupal\Core\Field\EntityReferenceFieldItemList",
 * )
 */
class Contributor extends EntityReferenceItem implements ContributorFieldInterface{

  /**
   * {@inheritdoc}
   */
  public static function defaultStorageSettings() {
    return array(
      'target_type' => 'sc_pub_contributor',
    ) + parent::defaultStorageSettings();
  }

  /**
   * {@inheritdoc}
   */
  public static function defaultFieldSettings() {
    return array(
      'handler' => 'default:sc_pub_contributor',
      'handler_settings' => array(),
    ) + parent::defaultFieldSettings();
  }

  /**
   * {@inheritdoc}
   */
  public static function propertyDefinitions(FieldStorageDefinitionInterface $field_definition) {
    $properties = parent::propertyDefinitions($field_definition);

    $properties['category'] = DataDefinition::create('string')
      ->setLabel(new TranslatableMarkup('Category'))
      ->setSetting('case_sensitive', TRUE);

    $properties['role'] = DataDefinition::create('string')
      ->setLabel(new TranslatableMarkup('Role'))
      ->setSetting('case_sensitive', TRUE);

    return $properties;
  }

  /**
   * {@inheritdoc}
   */
  public static function mainPropertyName() {
    return 'target_id';
  }

  /**
   * {@inheritdoc}
   */
  public static function schema(FieldStorageDefinitionInterface $field_definition) {
    $schema = parent::schema($field_definition);

    $schema['columns']['category'] = [
      'type' => 'varchar',
      'length' => 255,
    ];
    $schema['columns']['role'] = [
      'type' => 'varchar',
      'length' => 255,
    ];

    return $schema;
  }

  /**
   * {@inheritdoc}
   */
  public function storageSettingsForm(array &$form, FormStateInterface $form_state, $has_data) {
    return [];
  }

  /**
   * {@inheritdoc}
   */
  public function fieldSettingsForm(array $form, FormStateInterface $form_state) {
    return [];
  }

}
