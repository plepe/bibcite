<?php

namespace Drupal\sc_pub_entity\Form;

use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Form controller for Contributor edit forms.
 *
 * @ingroup sc_pub_entity
 */
class ContributorForm extends ContentEntityForm {

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    /* @var $entity \Drupal\sc_pub_entity\Entity\Contributor */
    $entity = $this->entity;

    $form['name'] = [
      '#type' => 'item',
      '#title' => $this->t('Name'),
      '#description' => $this->t('Will be created from another fields'),
      '#markup' => $entity->getName(),
    ];

    $form = parent::buildForm($form, $form_state);

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $entity = $this->entity;
    $status = parent::save($form, $form_state);

    switch ($status) {
      case SAVED_NEW:
        drupal_set_message($this->t('Created the %label Contributor.', [
          '%label' => $entity->label(),
        ]));
        break;

      default:
        drupal_set_message($this->t('Saved the %label Contributor.', [
          '%label' => $entity->label(),
        ]));
    }
    $form_state->setRedirect('entity.sc_pub_contributor.canonical', ['sc_pub_contributor' => $entity->id()]);
  }

}
