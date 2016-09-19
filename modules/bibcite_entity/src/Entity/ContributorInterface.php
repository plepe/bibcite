<?php

namespace Drupal\bibcite_entity\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Contributor entities.
 *
 * @ingroup bibcite_entity
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
   * Get the Contributor first name.
   *
   * @return string
   *   First name of Contributor.
   */
  public function getFirstName();

  /**
   * Get the Contributor last name.
   *
   * @return string
   *   Last name of Contributor.
   */
  public function getLastName();

  /**
   * Get the Contributor suffix.
   *
   * @return string
   *   Suffix of Contributor.
   */
  public function getSuffix();

  /**
   * Get the Contributor prefix.
   *
   * @return string
   *   Prefix of Contributor.
   */
  public function getPrefix();

  /**
   * Sets the Contributor full name.
   *
   * Full name string will be parsed and another fields will be changed.
   *
   * @param string $name
   *   Full name sting.
   *
   * @return \Drupal\bibcite_entity\Entity\ContributorInterface
   *   The called Contributor entity.
   */
  public function setName($name);

  /**
   * Sets the Contributor first name.
   *
   * @param string $first_name
   *   The Contributor first name.
   *
   * @return \Drupal\bibcite_entity\Entity\ContributorInterface
   *   The called Contributor entity.
   */
  public function setFirstName($first_name);

  /**
   * Sets the Contributor last name.
   *
   * @param string $last_name
   *   The Contributor last name.
   *
   * @return \Drupal\bibcite_entity\Entity\ContributorInterface
   *   The called Contributor entity.
   */
  public function setLastName($last_name);

  /**
   * Sets the Contributor suffix.
   *
   * @param string $suffix
   *   The Contributor suffix.
   *
   * @return \Drupal\bibcite_entity\Entity\ContributorInterface
   *   The called Contributor entity.
   */
  public function setSuffix($suffix);

  /**
   * Sets the Contributor prefix.
   *
   * @param string $prefix
   *   The Contributor prefix.
   *
   * @return \Drupal\bibcite_entity\Entity\ContributorInterface
   *   The called Contributor entity.
   */
  public function setPrefix($prefix);

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
   * @return \Drupal\bibcite_entity\Entity\ContributorInterface
   *   The called Contributor entity.
   */
  public function setCreatedTime($timestamp);

}
