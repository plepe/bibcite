<?php

namespace Drupal\sc_pub;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the Bibliography entity.
 *
 * @see \Drupal\sc_pub\Entity\Bibliography.
 */
class BibliographyAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\sc_pub\Entity\BibliographyInterface $entity */
    switch ($operation) {
      case 'view':
        if (!$entity->isPublished()) {
          return AccessResult::allowedIfHasPermission($account, 'view unpublished bibliography entities');
        }
        return AccessResult::allowedIfHasPermission($account, 'view published bibliography entities');

      case 'update':
        return AccessResult::allowedIfHasPermission($account, 'edit bibliography entities');

      case 'delete':
        return AccessResult::allowedIfHasPermission($account, 'delete bibliography entities');
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add bibliography entities');
  }

}
