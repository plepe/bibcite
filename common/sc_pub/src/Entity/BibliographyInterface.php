<?php

namespace Drupal\sc_pub\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Bibliography entities.
 *
 * @ingroup sc_pub
 */
interface BibliographyInterface extends ContentEntityInterface, EntityChangedInterface, EntityOwnerInterface {

  // Add get/set methods for your configuration properties here.

  /**
   * Gets the Bibliography name.
   *
   * @return string
   *   Name of the Bibliography.
   */
  public function getName();

  /**
   * Sets the Bibliography name.
   *
   * @param string $name
   *   The Bibliography name.
   *
   * @return \Drupal\sc_pub\Entity\BibliographyInterface
   *   The called Bibliography entity.
   */
  public function setName($name);

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
   * @return \Drupal\sc_pub\Entity\BibliographyInterface
   *   The called Bibliography entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Returns the Bibliography published status indicator.
   *
   * Unpublished Bibliography are only visible to restricted users.
   *
   * @return bool
   *   TRUE if the Bibliography is published.
   */
  public function isPublished();

  /**
   * Sets the published status of a Bibliography.
   *
   * @param bool $published
   *   TRUE to set this Bibliography to published, FALSE to set it to unpublished.
   *
   * @return \Drupal\sc_pub\Entity\BibliographyInterface
   *   The called Bibliography entity.
   */
  public function setPublished($published);

}
