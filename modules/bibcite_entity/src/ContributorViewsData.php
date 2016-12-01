<?php

namespace Drupal\bibcite_entity;


use Drupal\views\EntityViewsData;

/**
 * Provides the views data for the Contributor entity type.
 */
class ContributorViewsData extends EntityViewsData {

  /**
   * {@inheritdoc}
   */
  public function getViewsData() {
    $data = parent::getViewsData();

    $entity_type = $this->entityManager->getDefinition('bibcite_reference');

    $data[$this->entityType->getBaseTable()] += [
      'reverse__' . $entity_type->id() . '__' . $this->entityType->id() => [
        'relationship' => [
          'title' => $this->t('Reference using contributors'),
          'label' => $entity_type->getLabel(),
          'group' => $this->entityType->getLabel(),
          'id' => 'entity_reverse',
          'base' => $entity_type->getDataTable() ?: $entity_type->getBaseTable(),
          'entity_type' => $entity_type->id(),
          'base field' => $entity_type->getKey('id'),
          'field_name' => 'author',
          'field table' => 'bibcite_reference__author',
          'field field' => 'author_target_id',
          'join_extra' => [
            [
              'field' => 'deleted',
              'value' => 0,
              'numeric' => TRUE,
            ],
          ],
        ],
      ],
    ];

    return $data;
  }

}
