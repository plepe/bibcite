<?php

namespace Drupal\bibcite_entity;


use Drupal\bibcite_entity\Entity\ContributorInterface;
use Drupal\Core\Entity\Sql\SqlContentEntityStorage;

/**
 * Contributor storage.
 *
 * Allow to create Contributor entity using name property.
 */
class ContributorStorage extends SqlContentEntityStorage {

  /**
   * {@inheritdoc}
   */
  protected function initFieldValues(ContributorInterface $entity, array $values = [], array $field_names = []) {
    $this->initContributorName($entity, $values);
    parent::initFieldValues($entity, $values, $field_names);
  }

  /**
   * Init contributor properties by full name string.
   *
   * @param \Drupal\bibcite_entity\Entity\ContributorInterface $entity
   *   Contributor entity.
   * @param array $values
   *   Array of values.
   */
  protected function initContributorName(ContributorInterface $entity, array &$values = []) {
    if (isset($values['name'])) {
      $entity->setName($values['name']);

      foreach (['first_name', 'last_name', 'prefix', 'suffix'] as $property) {
        if (!empty($value = $entity->{$property}->value)) {
          $values[$property] = $value;
        }
      }
    }
  }

}
