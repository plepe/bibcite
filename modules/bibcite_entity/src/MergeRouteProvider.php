<?php

namespace Drupal\bibcite_entity;

use Drupal\bibcite_entity\Form\MergeConfirmForm;
use Drupal\bibcite_entity\Form\MergeForm;
use Drupal\bibcite_entity\Form\MergeMultipleForm;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Entity\Routing\EntityRouteProviderInterface;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

/**
 * Provides route for merge bibliographic entities.
 */
class MergeRouteProvider implements EntityRouteProviderInterface {

  /**
   * Only these entity types allowed to be merged.
   *
   * @var array
   *
   * @todo Find a better way to provide field name for filtering references.
   */
  protected $entityFields = [
    'bibcite_contributor' => 'author',
    'bibcite_keyword' => 'keywords',
  ];

  /**
   * {@inheritdoc}
   */
  public function getRoutes(EntityTypeInterface $entity_type) {
    $routes = new RouteCollection();

    $entity_type_id = $entity_type->id();
    if (isset($this->entityFields[$entity_type_id])) {
      if ($entity_type->hasLinkTemplate('bibcite-merge-form')) {
        if ($route = $this->getMergeRoute($entity_type)) {
          $routes->add("entity.{$entity_type_id}.bibcite_merge_form", $route);
        }

        if ($route = $this->getMergeConfirmRoute($entity_type)) {
          $routes->add("entity.{$entity_type_id}.bibcite_merge_form_confirm", $route);
        }
      }

      if ($entity_type->hasLinkTemplate('bibcite-merge-multiple-form')) {
        if ($route = $this->getMergeMultipleRoute($entity_type)) {
          $routes->add("entity.{$entity_type_id}.bibcite_merge_multiple_form", $route);
        }

        if ($route = $this->getMergeMultipleConfirmRoute($entity_type)) {
          $routes->add("entity.{$entity_type_id}.bibcite_merge_multiple_form_confirm", $route);
        }
      }
    }

    return $routes;
  }

  /**
   * Get route for merge.
   *
   * @param \Drupal\Core\Entity\EntityTypeInterface $entity_type
   *   Entity type.
   *
   * @return \Symfony\Component\Routing\Route
   *   Merge route definition.
   */
  protected function getMergeRoute(EntityTypeInterface $entity_type) {
    $link_template = $entity_type->getLinkTemplate('bibcite-merge-form');
    $entity_type_id = $entity_type->id();

    $route = new Route($link_template);
    $route
      ->setDefault('_form', MergeForm::class)
      ->setDefault('_title_callback', MergeForm::class . '::getTitle')
      ->setOption('_bibcite_entity_type_id', $entity_type_id)
      ->setOption('_admin_route', TRUE)
      ->setOption('parameters', [
        $entity_type_id => ['type' => 'entity:' . $entity_type_id],
      ])
      ->setRequirement('_permission', $entity_type->getAdminPermission());

    return $route;
  }

  /**
   * Get route for merge confirm.
   *
   * @param \Drupal\Core\Entity\EntityTypeInterface $entity_type
   *   Entity type.
   *
   * @return \Symfony\Component\Routing\Route
   *   Merge confirm route definition.
   */
  protected function getMergeConfirmRoute(EntityTypeInterface $entity_type) {
    $link_template = $entity_type->getLinkTemplate('bibcite-merge-form');
    $entity_type_id = $entity_type->id();

    $route = new Route($link_template . '/{' . $entity_type_id . '_target}');
    $route
      ->setDefault('_form', MergeConfirmForm::class)
      ->setDefault('field_name', $this->entityFields[$entity_type_id])
      ->setOption('_bibcite_entity_type_id', $entity_type_id)
      ->setOption('_admin_route', TRUE)
      ->setOption('parameters', [
        $entity_type_id => ['type' => 'entity:' . $entity_type_id],
        "{$entity_type_id}_target" => ['type' => 'entity:' . $entity_type_id],
      ])
      ->setRequirement('_permission', $entity_type->getAdminPermission());

    return $route;
  }

  /**
   * Get route for multiple merge.
   *
   * @param \Drupal\Core\Entity\EntityTypeInterface $entity_type
   *   Entity type.
   *
   * @return \Symfony\Component\Routing\Route
   *   Multiple merge route definition.
   */
  protected function getMergeMultipleRoute(EntityTypeInterface $entity_type) {
    $link_template = $entity_type->getLinkTemplate('bibcite-merge-multiple-form');
    $entity_type_id = $entity_type->id();

    $route = new Route($link_template);
    $route
      ->setDefault('_form', MergeMultipleForm::class)
      ->setDefault('entity_type_id', $entity_type_id)
      ->setDefault('field_name', $this->entityFields[$entity_type_id])
      ->setOption('_admin_route', TRUE)
      ->setRequirement('_permission', $entity_type->getAdminPermission());

    return $route;
  }

  /**
   * Get route for multiple merge confirm.
   *
   * @param \Drupal\Core\Entity\EntityTypeInterface $entity_type
   *   Entity type.
   *
   * @return null
   *   Nothing.
   */
  protected function getMergeMultipleConfirmRoute(EntityTypeInterface $entity_type) {
    // @todo What was supposed to be returned here?
    return NULL;
  }

}
