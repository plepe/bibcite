<?php

namespace Drupal\bibcite_entity\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Common Reference settings.
 */
class ReferenceSettings extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return ['bibcite_entity.reference.settings'];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'bibcite_entity_reference_settings';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('bibcite_entity.reference.settings');

    $form['display_override'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('Display override'),
      '#tree' => TRUE,
      'enable_display_override' => [
        '#type' => 'checkbox',
        '#title' => $this->t('Display reference as table'),
        '#description' => $this->t('View of Reference entity type will be rendered as table at Default and Full display modes. This will not working with Display suite.'),
        '#default_value' => $config->get('display_override.enable_display_override'),
      ],
    ];

    $form['ui_override'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('Interface override'),
      '#tree' => TRUE,
      'enable_form_override' => [
        '#type' => 'checkbox',
        '#title' => $this->t('Override entity forms'),
        '#description' => $this->t("Regroup all base fields of Reference entity to vertical tabs. You can use it if you don't want to configure form display view."),
        '#default_value' => $config->get('ui_override.enable_form_override'),
      ],
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $config = $this->config('bibcite_entity.reference.settings');
    $config->set('display_override.enable_display_override', (bool) $form_state->getValue(['display_override', 'enable_display_override']));
    $config->set('ui_override.enable_form_override', (bool) $form_state->getValue(['ui_override', 'enable_form_override']));
    $config->save();

    parent::submitForm($form, $form_state);
  }

}
