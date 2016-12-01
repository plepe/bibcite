<?php

namespace Drupal\bibcite\Form;


use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Links configuration.
 */
class SettingsLinksForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'bibcite_settings_links';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'bibcite.settings',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['links'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('Lookup'),
      '#tree' => TRUE,
    ];

    $config = $this->config('bibcite.settings');

    $form['links']['pubmed'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('PubMed'),
      '#default_value' => $config->get('links.pubmed'),
    ];
    $form['links']['doi'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('DOI'),
      '#default_value' => $config->get('links.doi'),
    ];
    $form['links']['google_scholar'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Google Scholar'),
      '#default_value' => $config->get('links.google_scholar'),
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $config = $this->config('bibcite.settings');
    $config->set('links', $form_state->getValue('links'))
      ->save();

    parent::submitForm($form, $form_state);
  }

}
