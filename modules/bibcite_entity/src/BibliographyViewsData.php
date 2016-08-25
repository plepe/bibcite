<?php

namespace Drupal\bibcite_entity;


use Drupal\views\EntityViewsData;

/**
 * Provides the views data for the bibliography entity type.
 */
class BibliographyViewsData extends EntityViewsData {

  /**
   * {@inheritdoc}
   */
  public function getViewsData() {
    $data = parent::getViewsData();

    $data['bibliography']['bulk_form'] = [
      'title' => $this->t('Operations bulk form'),
      'help' => $this->t('Add a form element that lets you run operations on multiple bibliography entries.'),
      'field' => [
        'id' => 'bulk_form',
      ],
    ];

    /*
     * @todo Optimize structure of fields handlers.
     */

    $data['bibliography__keywords'] = [
      'keywords_target_id' => [
        'title' => $this->t('Keywords'),
        'field' => [
          'id' => 'field',
        ],
        'argument' => [
          'id' => 'numeric',
        ],
        'filter' => [
          'id' => 'numeric',
        ],
        'sort' => [
          'id' => 'standard',
        ],
        'entity field' => 'keywords',
      ],
      'table' => [
        'group' => $this->t('Bibliography'),
        'provider' => 'bibcite_entity',
        'entity type' => 'bibliography',
        'join' => [
          'bibliography' => [
            'left_field' => 'id',
            'field' => 'entity_id',
            'extra' => [
              [
                'field' => 'deleted',
                'value' => 0,
                'numeric' => TRUE,
              ],
            ],
          ],
        ],
      ],
    ];

    $entity_type = $this->entityManager->getDefinition('bibcite_keyword');
    $data['bibliography__keywords']['keywords_target_id']['relationship'] = [
      'base' => $this->getViewsTableForEntityType($entity_type),
      'base field' => $entity_type->getKey('id'),
      'label' => $entity_type->getLabel(),
      'title' => $entity_type->getLabel(),
      'id' => 'standard',
    ];

    $data['bibliography__author'] = [
      'author_target_id' => [
        'title' => $this->t('Author'),
        'field' => [
          'id' => 'field',
        ],
        'argument' => [
          'id' => 'numeric',
        ],
        'filter' => [
          'id' => 'numeric',
        ],
        'sort' => [
          'id' => 'standard',
        ],
        'entity field' => 'author',
      ],
      'author_category' => [
        'title' => $this->t('Author (Category)'),
        'field' => [
          'id' => 'standard',
        ],
        'argument' => [
          'id' => 'string',
        ],
        'filter' => [
          'id' => 'string',
        ],
        'sort' => [
          'id' => 'standard',
        ],
      ],
      'author_role' => [
        'title' => $this->t('Author (Role)'),
        'field' => [
          'id' => 'standard',
        ],
        'argument' => [
          'id' => 'string',
        ],
        'filter' => [
          'id' => 'string',
        ],
        'sort' => [
          'id' => 'standard',
        ],
      ],
      'table' => [
        'group' => $this->t('Bibliography'),
        'provider' => 'bibcite_entity',
        'entity type' => 'bibliography',
        'join' => [
          'bibliography' => [
            'left_field' => 'id',
            'field' => 'entity_id',
            'extra' => [
              [
                'field' => 'deleted',
                'value' => 0,
                'numeric' => TRUE,
              ],
            ],
          ],
        ],
      ],
    ];

    $entity_type = $this->entityManager->getDefinition('bibcite_contributor');
    $data['bibliography__author']['author_target_id']['relationship'] = [
      'base' => $this->getViewsTableForEntityType($entity_type),
      'base field' => $entity_type->getKey('id'),
      'label' => $entity_type->getLabel(),
      'title' => $entity_type->getLabel(),
      'id' => 'standard',
    ];

    return $data;
  }

}
