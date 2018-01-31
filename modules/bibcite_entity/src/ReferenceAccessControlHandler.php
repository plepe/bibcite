<?php

namespace Drupal\bibcite_entity;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the Reference entity.
 *
 * @see \Drupal\bibcite_entity\Entity\Reference.
 */
class ReferenceAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    $type = $entity->bundle();
    /** @var \Drupal\bibcite_entity\Entity\ReferenceInterface $entity */
    switch ($operation) {
      case 'view':
        return AccessResult::allowedIfHasPermission($account, 'view bibcite_reference');

      case 'update':
        return AccessResult::allowedIf($account->hasPermission('edit any bibcite_reference')
          || $account->hasPermission("edit any $type bibcite_reference")
          || ($entity->getOwnerId() == $account->id() &&
            ($account->hasPermission('edit own bibcite_reference')
            || $account->hasPermission("edit own $type bibcite_reference"))));

      case 'delete':
        return AccessResult::allowedIf($account->hasPermission('delete any bibcite_reference')
          || $account->hasPermission("delete any $type bibcite_reference")
          || ($entity->getOwnerId() == $account->id() &&
            ($account->hasPermission('delete own bibcite_reference')
            || $account->hasPermission("delete own $type bibcite_reference"))));
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIf($account->hasPermission('create bibcite_reference')
      || $account->hasPermission('create ' . $entity_bundle . ' bibcite_reference'));
  }

}
