<?php

namespace Drupal\bibcite_entity\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Bibliography entities.
 *
 * @ingroup bibcite_entity
 */
interface BibliographyInterface extends ContentEntityInterface, EntityChangedInterface {

  /**
   * Gets the Bibliography creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Bibliography.
   */
  public function getCreatedTime();

  /**
   * Sets the Bibliography creation timestamp.
   *
   * @param int $timestamp
   *   The Bibliography creation timestamp.
   *
   * @return \Drupal\bibcite_entity\Entity\BibliographyInterface
   *   The called Bibliography entity.
   */
  public function setCreatedTime($timestamp);

}
