<?php

namespace Drupal\bibcite_entity\Plugin\Field\FieldWidget;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Field\FieldDefinitionInterface;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\Plugin\Field\FieldWidget\EntityReferenceAutocompleteWidget;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\user\EntityOwnerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Plugin implementation of the 'bibcite_contributor_widget' widget.
 *
 * @FieldWidget(
 *   id = "bibcite_contributor_widget",
 *   label = @Translation("Contributor widget"),
 *   field_types = {
 *     "bibcite_contributor"
 *   }
 * )
 */
class ContributorWidget extends EntityReferenceAutocompleteWidget implements ContainerFactoryPluginInterface {

  /**
   * The entity type manager service.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * {@inheritdoc}
   */
  public function __construct($plugin_id, $plugin_definition, FieldDefinitionInterface $field_definition, array $settings, array $third_party_settings, EntityTypeManagerInterface $entity_type_manager) {
    parent::__construct($plugin_id, $plugin_definition, $field_definition, $settings, $third_party_settings);

    $this->entityTypeManager = $entity_type_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $plugin_id,
      $plugin_definition,
      $configuration['field_definition'],
      $configuration['settings'],
      $configuration['third_party_settings'],
      $container->get('entity_type.manager')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function form(FieldItemListInterface $items, array &$form, FormStateInterface $form_state, $get_delta = NULL) {
    // @todo Render form as draggable table.
    return parent::form($items, $form, $form_state, $get_delta);
  }

  /**
   * {@inheritdoc}
   */
  public function formElement(FieldItemListInterface $items, $delta, array $element, array &$form, FormStateInterface $form_state) {
    $element += parent::formElement($items, $delta, $element, $form, $form_state);

    $element['category'] = [
      '#type' => 'select',
      '#title' => $this->t('Category'),
      '#default_value' => isset($items[$delta]->category) ? $items[$delta]->category : NULL,
      '#maxlength' => $this->getFieldSetting('max_length'),
      '#weight' => $delta,
      '#options' => $this->getContributorCategories(),
    ];

    $element['role'] = [
      '#type' => 'select',
      '#title' => $this->t('Role'),
      '#default_value' => isset($items[$delta]->role) ? $items[$delta]->role : NULL,
      '#maxlength' => $this->getFieldSetting('max_length'),
      '#weight' => $delta,
      '#options' => $this->getContributorRoles(),
    ];

    $element['#type'] = 'container';
    $element['#attributes']['class'][] = 'container-inline';

    $entity = $items->getEntity();
    $element['target_id']['#autocreate'] = array(
      'bundle' => 'bibcite_contributor',
      'uid' => ($entity instanceof EntityOwnerInterface) ? $entity->getOwnerId() : \Drupal::currentUser()->id(),
    );

    return $element;
  }

  /**
   * Get list of contributor categories.
   *
   * @return array
   *   Contributor categories.
   */
  protected function getContributorCategories() {
    $entities = $this->entityTypeManager->getStorage('bibcite_contributor_category')->loadMultiple();

    return array_map(function($entity) {
      /** @var \Drupal\Core\Entity\EntityInterface $entity */
      return $entity->label();
    }, $entities);
  }

  /**
   * Get list of contributor roles.
   *
   * @return array
   *   Contributor roles.
   */
  protected function getContributorRoles() {
    $entities = $this->entityTypeManager->getStorage('bibcite_contributor_role')->loadMultiple();

    return array_map(function($entity) {
      /** @var \Drupal\Core\Entity\EntityInterface $entity */
      return $entity->label();
    }, $entities);
  }

}
