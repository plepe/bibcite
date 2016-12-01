<?php

namespace Drupal\bibcite_entity\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBase;

/**
 * Defines the Reference type entity.
 *
 * @ConfigEntityType(
 *   id = "bibcite_reference_type",
 *   label = @Translation("Reference type"),
 *   handlers = {
 *     "list_builder" = "Drupal\bibcite_entity\ReferenceTypeListBuilder",
 *     "form" = {
 *       "add" = "Drupal\bibcite_entity\Form\ReferenceTypeForm",
 *       "edit" = "Drupal\bibcite_entity\Form\ReferenceTypeForm",
 *       "delete" = "Drupal\bibcite_entity\Form\ReferenceTypeDeleteForm"
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\bibcite_entity\ReferenceTypeHtmlRouteProvider",
 *     },
 *   },
 *   config_prefix = "bibcite_reference_type",
 *   admin_permission = "administer reference entities",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label",
 *     "uuid" = "uuid"
 *   },
 *   links = {
 *     "canonical" = "/admin/config/bibcite/settings/types/{bibcite_reference_type}",
 *     "add-form" = "/admin/config/bibcite/settings/types/add",
 *     "edit-form" = "/admin/config/bibcite/settings/types/{bibcite_reference_type}/edit",
 *     "delete-form" = "/admin/config/bibcite/settings/types/{bibcite_reference_type}/delete",
 *     "collection" = "/admin/config/bibcite/settings/types"
 *   }
 * )
 */
class ReferenceType extends ConfigEntityBase implements ReferenceTypeInterface {

  /**
   * The Reference type ID.
   *
   * @var string
   */
  protected $id;

  /**
   * The Reference type label.
   *
   * @var string
   */
  protected $label;

}
