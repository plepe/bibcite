<?php

namespace Drupal\bibcite;

use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Entity\Routing\AdminHtmlRouteProvider;
use Symfony\Component\Routing\Route;

/**
 * Provides routes for CSL style entities.
 *
 * @see \Drupal\Core\Entity\Routing\AdminHtmlRouteProvider
 * @see \Drupal\Core\Entity\Routing\DefaultHtmlRouteProvider
 */
class CslStyleHtmlRouteProvider extends AdminHtmlRouteProvider {

  /**
   * {@inheritdoc}
   */
  public function getRoutes(EntityTypeInterface $entity_type) {
    $collection = parent::getRoutes($entity_type);

    $entity_type_id = $entity_type->id();

    if ($collection_route = $this->getCollectionRoute($entity_type)) {
      $collection->add("entity.{$entity_type_id}.collection", $collection_route);
    }

    if ($form_file_route = $this->getAddFileFormRoute($entity_type)) {
      $collection->add("entity.{$entity_type_id}.add_file_form", $form_file_route);
    }

    return $collection;
  }

  /**
   * Gets the collection route.
   *
   * @param \Drupal\Core\Entity\EntityTypeInterface $entity_type
   *   The entity type.
   *
   * @return \Symfony\Component\Routing\Route|null
   *   The generated route, if available.
   */
  protected function getCollectionRoute(EntityTypeInterface $entity_type) {
    if ($entity_type->hasLinkTemplate('collection') && $entity_type->hasListBuilderClass()) {
      $entity_type_id = $entity_type->id();
      $route = new Route($entity_type->getLinkTemplate('collection'));
      $route
        ->setDefaults([
          '_entity_list' => $entity_type_id,
          '_title' => (string) $entity_type->getLabel(),
        ])
        ->setRequirement('_permission', $entity_type->getAdminPermission())
        ->setOption('_admin_route', TRUE);

      return $route;
    }

    return NULL;
  }

  /**
   * Gets the file form route.
   *
   * @param \Drupal\Core\Entity\EntityTypeInterface $entity_type
   *   The entity type.
   *
   * @return \Symfony\Component\Routing\Route|null
   *   The generated route, if available.
   */
  protected function getAddFileFormRoute(EntityTypeInterface $entity_type) {
    if ($entity_type->hasLinkTemplate('add-form-file') && $entity_type->hasHandlerClass('form', 'add-file')) {
      $entity_type_id = $entity_type->id();
      $route = new Route($entity_type->getLinkTemplate('add-form-file'));
      $route
        ->setDefaults([
          '_entity_form' => "{$entity_type_id}.add-file",
          'entity_type_id' => $entity_type_id,
        ])
        ->setRequirement('_permission', $entity_type->getAdminPermission())
        ->setOption('_admin_route', TRUE);

      return $route;
    }

    return NULL;
  }

}
