<?php

namespace Drupal\bibcite_entity;

use Drupal\Component\Render\FormattableMarkup;
use Drupal\Core\Field\FieldItemBase;
use Drupal\Core\Field\FieldStorageDefinitionInterface;

/**
 * Contributor name computed field.
 */
class ContributorName extends FieldItemBase {

  /**
   * {@inheritdoc}
   */
  public function getValue($langcode = NULL) {
    /** @var \Drupal\bibcite_entity\Entity\ContributorInterface $contributor */
    $contributor = $this->parent->getValue();

    $arguments = [
      '@leading_title' => $contributor->getLeadingInitial(),
      '@last_name' => $contributor->getLastName(),
      '@middle_name' => $contributor->getMiddleName(),
      '@first_name' => $contributor->getFirstName(),
      '@nick' => $contributor->getNickName(),
      '@suffix' => $contributor->getSuffix(),
      '@prefix' => $contributor->getPrefix(),
    ];

    // @todo Dependency injection.
    $format = \Drupal::config('bibcite_entity.contributor.settings')->get('full_name_pattern') ?: '@prefix @first_name @last_name @suffix';

    $full_name = (string) new FormattableMarkup($format, $arguments);
    return trim(str_replace('  ', ' ', $full_name));
  }

  /**
   * {@inheritdoc}
   */
  public function setValue($value, $notify = TRUE) {
    if ($value) {
      /** @var \Drupal\bibcite_entity\Entity\ContributorInterface $contributor */
      $contributor = $this->parent->getValue();
      $name_parts = \Drupal::service('bibcite.human_name_parser')->parse($value);

      foreach ($name_parts as $key => $name_part) {
        $contributor->$key = $name_part;
      }
    }

    // Notify the parent of any changes.
    if ($notify && isset($this->parent)) {
      $this->parent->onChange($this->name);
    }
  }

  /**
   * {@inheritdoc}
   */
  public static function schema(FieldStorageDefinitionInterface $field_definition) {
    return [];
  }

  /**
   * {@inheritdoc}
   */
  public static function propertyDefinitions(FieldStorageDefinitionInterface $field_definition) {}

  /**
   * Set field langcode.
   *
   * @todo This method is required but not in the interface?
   */
  public function setLangcode() {}

}
