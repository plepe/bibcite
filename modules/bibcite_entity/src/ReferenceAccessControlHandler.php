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
    /** @var \Drupal\bibcite_entity\Entity\ReferenceInterface $entity */
    switch ($operation) {
      case 'view':
        return AccessResult::allowedIfHasPermission($account, 'view bibcite_reference entities');

      case 'update':
        return AccessResult::allowedIf($account->hasPermission('edit all bibcite_reference entities')
          || ($account->hasPermission('edit own bibcite_reference entities')) && $entity->getOwnerId() == $account->id());

      case 'delete':
        return AccessResult::allowedIf($account->hasPermission('delete all bibcite_reference entities')
          || ($account->hasPermission('delete own bibcite_reference entities')) && $entity->getOwnerId() == $account->id());
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add bibcite_reference entities');
  }

}
