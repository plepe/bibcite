<?php

namespace Drupal\bibcite_entity\Form;

use Drupal\bibcite_entity\BibciteEntityFormOverrider;
use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Form controller for Reference edit forms.
 *
 * @ingroup bibcite_entity
 */
class ReferenceForm extends ContentEntityForm {

  /**
   * {@inheritdoc}
   */
  public function form(array $form, FormStateInterface $form_state) {
    $form = parent::form($form, $form_state);

    /*
     * @todo
     * This is a temporary solution.
     * Should be replaces by default fields mechanism in the next major release.
     */
    $form['#process'][] = [BibciteEntityFormOverrider::class, 'staticReferenceFieldsOverride'];
    $form['#process'][] = [BibciteEntityFormOverrider::class, 'staticReferenceRestructure'];

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
