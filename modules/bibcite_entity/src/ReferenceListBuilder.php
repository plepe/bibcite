<?php

namespace Drupal\bibcite_entity;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;
use Drupal\Core\Link;

/**
 * Defines a class to build a listing of Reference entities.
 *
 * @ingroup bibcite_entity
 */
class ReferenceListBuilder extends EntityListBuilder {

  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['name'] = $this->t('Title');
    $header['type'] = $this->t('Type');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    /* @var $entity \Drupal\bibcite_entity\Entity\Reference */
    $row['name'] = Link::createFromRoute($entity->label(), 'entity.bibcite_reference.canonical', [
      'bibcite_reference' => $entity->id(),
    ]);
    // @todo Use non-magic entity method.
    $row['type'] = $entity->type->target_id;
    return $row + parent::buildRow($entity);
  }

}
