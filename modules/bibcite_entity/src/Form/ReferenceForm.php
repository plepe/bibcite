<?php

namespace Drupal\bibcite_entity\Form;

use Drupal\bibcite_entity\Entity\ReferenceInterface;
use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Entity\EntityManagerInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\user\PrivateTempStore;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Form controller for Reference edit forms.
 *
 * @ingroup bibcite_entity
 */
class ReferenceForm extends ContentEntityForm {

  /**
   * Module temp store.
   *
   * @var \Drupal\user\PrivateTempStore
   */
  protected $tempStore;

  /**
   * {@inheritdoc}
   */
  public function __construct(EntityManagerInterface $entity_manager, PrivateTempStore $temp_store) {
    $this->entityManager = $entity_manager;
    $this->tempStore = $temp_store;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('entity.manager'),
      $container->get('user.private_tempstore')->get('bibcite_entity_populate')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function prepareEntity() {
    parent::prepareEntity();

    /*
     * Allow to populate entity object from external source.
     */
    $current_user_id = \Drupal::currentUser()->id();
    $entity = $this->tempStore->get($current_user_id);
    if ($entity && $entity instanceof ReferenceInterface) {
      $this->entity = $entity;
      $this->tempStore->delete($current_user_id);
    }
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $entity = $this->entity;
    $status = parent::save($form, $form_state);

    switch ($status) {
      case SAVED_NEW:
        drupal_set_message($this->t('Created the %label Reference.', [
          '%label' => $entity->label(),
        ]));
        break;

      default:
        drupal_set_message($this->t('Saved the %label Reference.', [
          '%label' => $entity->label(),
        ]));
    }
    $form_state->setRedirect('entity.bibcite_reference.canonical', ['bibcite_reference' => $entity->id()]);
  }

}
