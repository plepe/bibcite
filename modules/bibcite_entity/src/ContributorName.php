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

    $last_name = $contributor->getLastName();
    $first_name = $contributor->getFirstName();
    $suffix = $contributor->getSuffix();
    $prefix = $contributor->getPrefix();

    $arguments = [
      '@last_name' => $last_name,
      '@first_name' => $first_name,
      '@suffix' => $suffix,
      '@prefix' => $prefix,
    ];

    $format = '@last_name';

    if ($prefix && $last_name && $first_name && $suffix) {
      $format = '@prefix @last_name @first_name @suffix';
    }
    elseif ($prefix && $last_name && $first_name) {
      $format = '@prefix @last_name @first_name';
    }
    elseif ($last_name && $first_name && $suffix) {
      $format = '@last_name @first_name @suffix';
    }
    elseif ($prefix && $last_name) {
      $format = '@prefix @last_name';
    }
    elseif ($last_name && $suffix) {
      $format = '@last_name @suffix';
    }
    elseif ($last_name && $first_name) {
      $format = '@last_name @first_name';
    }

    return (string) new FormattableMarkup($format, $arguments);
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
