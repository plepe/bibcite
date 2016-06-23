<?php

namespace Drupal\sc_pub;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the Contributor entity.
 *
 * @see \Drupal\sc_pub\Entity\Contributor.
 */
class ContributorAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\sc_pub\Entity\ContributorInterface $entity */
    switch ($operation) {
      case 'view':
        if (!$entity->isPublished()) {
          return AccessResult::allowedIfHasPermission($account, 'view unpublished contributor entities');
        }
        return AccessResult::allowedIfHasPermission($account, 'view published contributor entities');

      case 'update':
        return AccessResult::allowedIfHasPermission($account, 'edit contributor entities');

      case 'delete':
        return AccessResult::allowedIfHasPermission($account, 'delete contributor entities');
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add contributor entities');
  }

}
