<?php

namespace Drupal\bibcite_entity\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBase;

/**
 * Defines the Bibliography type entity.
 *
 * @ConfigEntityType(
 *   id = "bibliography_type",
 *   label = @Translation("Bibliography type"),
 *   handlers = {
 *     "list_builder" = "Drupal\bibcite_entity\BibliographyTypeListBuilder",
 *     "form" = {
 *       "add" = "Drupal\bibcite_entity\Form\BibliographyTypeForm",
 *       "edit" = "Drupal\bibcite_entity\Form\BibliographyTypeForm",
 *       "delete" = "Drupal\bibcite_entity\Form\BibliographyTypeDeleteForm"
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\bibcite_entity\BibliographyTypeHtmlRouteProvider",
 *     },
 *   },
 *   config_prefix = "bibliography_type",
 *   admin_permission = "administer bibliography entities",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label",
 *     "uuid" = "uuid"
 *   },
 *   links = {
 *     "canonical" = "/admin/config/bibcite/settings/types/{bibliography_type}",
 *     "add-form" = "/admin/config/bibcite/settings/types/add",
 *     "edit-form" = "/admin/config/bibcite/settings/types/{bibliography_type}/edit",
 *     "delete-form" = "/admin/config/bibcite/settings/types/{bibliography_type}/delete",
 *     "collection" = "/admin/config/bibcite/settings/types"
 *   }
 * )
 */
class BibliographyType extends ConfigEntityBase implements BibliographyTypeInterface {

  /**
   * The Bibliography type ID.
   *
   * @var string
   */
  protected $id;

  /**
   * The Bibliography type label.
   *
   * @var string
   */
  protected $label;

}
