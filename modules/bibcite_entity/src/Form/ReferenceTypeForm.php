<?php

namespace Drupal\bibcite_entity\Form;

use Drupal\Core\Entity\EntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Reference type form.
 */
class ReferenceTypeForm extends EntityForm {

  /**
   * {@inheritdoc}
   */
  public function form(array $form, FormStateInterface $form_state) {
    $form = parent::form($form, $form_state);

    $reference_type = $this->entity;
    $form['label'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Label'),
      '#maxlength' => 255,
      '#default_value' => $reference_type->label(),
      '#description' => $this->t("Label for the Reference type."),
      '#required' => TRUE,
    ];

    $form['id'] = [
      '#type' => 'machine_name',
      '#default_value' => $reference_type->id(),
      '#machine_name' => [
        'exists' => '\Drupal\bibcite_entity\Entity\ReferenceType::load',
      ],
      '#disabled' => !$reference_type->isNew(),
    ];

    /* You will need additional form elements for your custom properties. */

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $reference_type = $this->entity;
    $status = $reference_type->save();

    switch ($status) {
      case SAVED_NEW:
        drupal_set_message($this->t('Created the %label Reference type.', [
          '%label' => $reference_type->label(),
        ]));
        break;

      default:
        drupal_set_message($this->t('Saved the %label Reference type.', [
          '%label' => $reference_type->label(),
        ]));
    }
    $form_state->setRedirectUrl($reference_type->urlInfo('collection'));
  }

}
