<?php

namespace Drupal\sc_pub_entity\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Keyword entities.
 *
 * @ingroup sc_pub_entity
 */
interface KeywordInterface extends ContentEntityInterface, EntityChangedInterface {

  /**
   * Gets the Keyword name.
   *
   * @return string
   *   Name of the Keyword.
   */
  public function getName();

  /**
   * Sets the Keyword name.
   *
   * @param string $name
   *   The Keyword name.
   *
   * @return \Drupal\sc_pub_entity\Entity\KeywordInterface
   *   The called Keyword entity.
   */
  public function setName($name);

  /**
   * Gets the Keyword creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Keyword.
   */
  public function getCreatedTime();

  /**
   * Sets the Keyword creation timestamp.
   *
   * @param int $timestamp
   *   The Keyword creation timestamp.
   *
   * @return \Drupal\sc_pub_entity\Entity\KeywordInterface
   *   The called Keyword entity.
   */
  public function setCreatedTime($timestamp);

}
