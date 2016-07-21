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

    $data['bibliography']['bulk_form'] = array(
      'title' => $this->t('Operations bulk form'),
      'help' => $this->t('Add a form element that lets you run operations on multiple bibliography entries.'),
      'field' => [
        'id' => 'bulk_form',
      ],
    );

    return $data;
  }

}
