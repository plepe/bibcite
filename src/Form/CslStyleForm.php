<?php

namespace Drupal\bibcite\Form;

use Drupal\bibcite\Csl;
use Drupal\bibcite\Entity\CslStyle;
use Drupal\Core\Entity\EntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Add/edit form for bibcite_csl_style entity.
 */
class CslStyleForm extends EntityForm {

  /**
   * {@inheritdoc}
   */
  public function form(array $form, FormStateInterface $form_state) {
    $form = parent::form($form, $form_state);
    /** @var \Drupal\bibcite\Entity\CslStyleInterface $csl_style */
    $csl_style = $this->entity;

    $form['label'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Label'),
      '#maxlength' => 255,
      '#default_value' => $csl_style->label(),
      '#description' => $this->t("Label for the CSL style."),
    ];

    $form['id'] = [
      '#type' => 'machine_name',
      '#default_value' => $csl_style->id(),
      '#machine_name' => [
        'exists' => [CslStyle::class, 'load'],
      ],
      '#disabled' => !$csl_style->isNew(),
    ];

    $form['status'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Enabled'),
      '#default_value' => $csl_style->status(),
      '#access' => !$csl_style->isNew(),
    ];

    $form['csl'] = [
      '#type' => 'textarea',
      '#rows' => 20,
      '#title' => $this->t('CSL text'),
      '#default_value' => $csl_style->getCslText(),
      '#required' => TRUE,
    ];

    $form['url_id'] = [
      '#type' => 'textfield',
      '#title' => $this->t('URL'),
      '#access' => !$csl_style->isNew(),
      '#default_value' => $csl_style->getUrlId(),
      '#disabled' => TRUE,
    ];

    $form['parent'] = [
      '#type' => 'entity_autocomplete',
      '#title' => $this->t('Parent style'),
      '#target_type' => 'bibcite_csl_style',
      '#default_value' => $csl_style->getParent(),
      '#access' => !$csl_style->isNew(),
      '#disabled' => TRUE,
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    $csl = new Csl($form_state->getValue('csl'));

    if (!$csl->validate()) {
      $form_state->setErrorByName('csl', $this->t('You are trying to save not valid CSL'));
    }


    $storage = $this->entityTypeManager->getStorage('bibcite_csl_style');
    if ($url_id = $csl->getId()) {
      $result = $storage->getQuery()
        ->condition('url_id', $url_id)
        ->execute();

      if ($result) {
        $form_state->setError($form, $this->t('You are trying to save existing style. Check out style with this id: @id', ['@id' => reset($result)]));
      }
    }

    if ($parent_url = $csl->getParent()) {
      $result = $storage->getQuery()
        ->condition('url_id', $parent_url)
        ->execute();

      if (!$result) {
        $message = $this->t('You are trying to save dependent style without installed parent. You should install parent style first: @style', [
          '@style' => $parent_url,
        ]);
        $form_state->setError($form, $message);
      }
    }
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $bibcite_csl_style = $this->entity;
    $status = $bibcite_csl_style->save();

    switch ($status) {
      case SAVED_NEW:
        drupal_set_message($this->t('Created the %label CSL style.', [
          '%label' => $bibcite_csl_style->label(),
        ]));
        break;

      default:
        drupal_set_message($this->t('Saved the %label CSL style.', [
          '%label' => $bibcite_csl_style->label(),
        ]));
    }

    $form_state->setRedirectUrl($bibcite_csl_style->toUrl('collection'));
  }

}
