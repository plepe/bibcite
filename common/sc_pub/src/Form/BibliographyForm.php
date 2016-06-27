<?php

namespace Drupal\sc_pub\Form;

use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Form controller for Bibliography edit forms.
 *
 * @ingroup sc_pub
 */
class BibliographyForm extends ContentEntityForm {

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    /* @var $entity \Drupal\sc_pub\Entity\Bibliography */
    $form = parent::buildForm($form, $form_state);
    $entity = $this->entity;

//    $form['issue'] = [
//      '#type' => 'textfield',
//      '#title' => $this->t('Issue'),
//      '#default_value' => $entity->issue->value,
//    ];
//
//    dsm($form);

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    dsm($form_state->getValues());
    $entity = $this->entity;
    $status = parent::save($form, $form_state);

    switch ($status) {
      case SAVED_NEW:
        drupal_set_message($this->t('Created the %label Bibliography.', [
          '%label' => $entity->label(),
        ]));
        break;

      default:
        drupal_set_message($this->t('Saved the %label Bibliography.', [
          '%label' => $entity->label(),
        ]));
    }
    $form_state->setRedirect('entity.bibliography.canonical', ['bibliography' => $entity->id()]);
  }

}
