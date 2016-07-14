<?php

namespace Drupal\bibcite\Form;


use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\bibcite\Plugin\BibCiteProcessor\CiteprocPhp;

/**
 * Settings form for CiteprocPhp processor.
 */
class CiteprocPhpSettings extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'bibcite_settings_citeproc_php';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'bibcite.processor.citeprocphp.settings',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('bibcite.processor.citeprocphp.settings');

    $all_styles = CiteprocPhp::getStyles();

    $enabled_styles = $config->get('enabled_styles');
    // Flip array to use in intersect function.
    $enabled_styles = array_flip($enabled_styles);
    $enabled_styles = array_intersect_key($all_styles, $enabled_styles);

    $form['styles'] = [
      '#type' => 'container',
      '#attributes' => ['class' => ['form--inline', 'clearfix']],
    ];

    $form['styles']['enabled_styles'] = [
      '#type' => 'select',
      '#multiple' => TRUE,
      '#size' => 20,
      '#title' => $this->t('Enabled styles'),
      '#options' => $enabled_styles,
    ];
    $form['styles']['buttons'] = [
      '#type' => 'container',
    ];
    $form['styles']['buttons']['add'] = [
      '#type' => 'submit',
      '#value' => $this->t('@arrow Add', ['@arrow' => '<< ']),
      '#submit' => [$this, '::submitAddAction'],
    ];
    $form['styles']['buttons']['delete'] = [
      '#type' => 'submit',
      '#value' => $this->t('Delete @arrow', ['@arrow' => ' >>']),
      '#submit' => [$this, '::submitDeleteAction'],
      '#validate' => [$this, '::validateDeleteAction'],
    ];
    $form['styles']['available_styles'] = [
      '#type' => 'select',
      '#multiple' => TRUE,
      '#size' => 20,
      '#title' => $this->t('Available styles'),
      '#options' => array_diff($all_styles, $enabled_styles),
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * Make styles available for use.
   *
   * @param array $form
   *   An associative array containing the structure of the form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The current state of the form.
   */
  public function submitAddAction(array &$form, FormStateInterface $form_state) {
    $value = $form_state->getValue('available_styles');
    $new_styles = array_keys($value);

    $config = $this->config('bibcite.processor.citeprocphp.settings');
    $available_styles = $config->get('enabled_styles');

    $available_styles = array_merge($new_styles, $available_styles);
    ksort($available_styles);

    $config->set('enabled_styles', $available_styles)
      ->save();

    parent::submitForm($form, $form_state);
  }

  /**
   * Validate callback for delete action.
   *
   * @param array $form
   *   An associative array containing the structure of the form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The current state of the form.
   */
  public function validateDeleteAction(array &$form, FormStateInterface $form_state) {
    $config = $this->config('bibcite.processor.citeprocphp.settings');
    $default_style = $config->get('default_style');
    $value = $form_state->getValue('enabled_styles');

    if (isset($value[$default_style])) {
      $form_state->setErrorByName('enabled_styles', $this->t('You can not delete default style'));
    }
  }

  /**
   * Delete styles from list of available.
   *
   * @param array $form
   *   An associative array containing the structure of the form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The current state of the form.
   */
  public function submitDeleteAction(array &$form, FormStateInterface $form_state) {
    $value = $form_state->getValue('enabled_styles');
    $removed_styles = array_keys($value);

    $config = $this->config('bibcite.processor.citeprocphp.settings');
    $available_styles = $config->get('enabled_styles');

    $available_styles = array_diff($available_styles, $removed_styles);

    $config->set('enabled_styles', $available_styles)
      ->save();

    parent::submitForm($form, $form_state);
  }

}
