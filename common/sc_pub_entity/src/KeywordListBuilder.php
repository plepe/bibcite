<?php

namespace Drupal\sc_pub_entity;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;
use Drupal\Core\Routing\LinkGeneratorTrait;
use Drupal\Core\Url;

/**
 * Defines a class to build a listing of Keyword entities.
 *
 * @ingroup sc_pub_entity
 */
class KeywordListBuilder extends EntityListBuilder {

  use LinkGeneratorTrait;

  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['id'] = $this->t('Keyword ID');
    $header['name'] = $this->t('Name');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    /* @var $entity \Drupal\sc_pub_entity\Entity\Keyword */
    $row['id'] = $entity->id();
    $row['name'] = $this->l(
      $entity->label(),
      new Url(
        'entity.sc_pub_keyword.edit_form', array(
          'sc_pub_keyword' => $entity->id(),
        )
      )
    );
    return $row + parent::buildRow($entity);
  }

}
