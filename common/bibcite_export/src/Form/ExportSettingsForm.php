<?php

namespace Drupal\bibcite_export\Form;


use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Export configuration form.
 */
class ExportSettingsForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'bibcite_export_settings';
  }

  /**
   * {@inheritdoc}
   */
  public function getEditableConfigNames() {
    return [
      'bibcite_export.settings',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('bibcite_export.settings');

    $manager = \Drupal::service('plugin.manager.bibcite_export_format');

    $form['show_full'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Show export links on table view of Bibliography entity'),
      '#default_value' => $config->get('show_full'),
    ];
    $form['show_citation'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Show export links in the rendered citation'),
      '#default_value' => $config->get('show_citation'),
    ];
    $form['enabled_formats'] = [
      '#type' => 'checkboxes',
      '#title' => $this->t('Enabled export formats'),
      '#options' => array_map(function($definition) {
        return $definition['label'];
      }, $manager->getDefinitions()),
      '#default_value' => $config->get('enabled_formats'),
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $config = $this->config('bibcite_export.settings');
    $config->set('show_full', $form_state->getValue('show_full'));
    $config->set('show_citation', $form_state->getValue('show_citation'));

    $enabled_formats = $form_state->getValue('enabled_formats');
    $config->set('enabled_formats', array_keys($enabled_formats, TRUE));

    $config->save();

    parent::submitForm($form, $form_state);
  }

}
