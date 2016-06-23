<?php

namespace Drupal\sc_pub\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Contributor entities.
 *
 * @ingroup sc_pub
 */
interface ContributorInterface extends ContentEntityInterface, EntityChangedInterface {

  /**
   * Gets the Contributor name.
   *
   * @return string
   *   Name of the Contributor.
   */
  public function getName();

  /**
   * Sets the Contributor name.
   *
   * @param string $name
   *   The Contributor name.
   *
   * @return \Drupal\sc_pub\Entity\ContributorInterface
   *   The called Contributor entity.
   */
  public function setName($name);

  /**
   * Gets the Contributor creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Contributor.
   */
  public function getCreatedTime();

  /**
   * Sets the Contributor creation timestamp.
   *
   * @param int $timestamp
   *   The Contributor creation timestamp.
   *
   * @return \Drupal\sc_pub\Entity\ContributorInterface
   *   The called Contributor entity.
   */
  public function setCreatedTime($timestamp);

}
