<?php

namespace Drupal\sc_pub\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\sc_pub\CiteprocPhpInterface;

/**
 * Settings form for CiteprocPhp processor.
 */
class CiteprocPhpSettings extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'sc_pub_settings_citeproc_php';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'sc_pub.processor.citeprocphp.settings',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('sc_pub.processor.citeprocphp.settings');

    /** @var CiteprocPhpInterface $citeproc */
    $citeproc = \Drupal::service('sc_pub.citeproc_php');
    $all_styles = $citeproc->getStyles();

    $available_styles = $config->get('available_styles');
    // Flip array to use in intersect function.
    $available_styles = array_flip($available_styles);
    $available_styles = array_intersect_key($all_styles, $available_styles);

    $form['styles'] = [
      '#type' => 'container',
      '#attributes' => ['class' => ['form--inline', 'clearfix']],
    ];

    $form['styles']['available_styles'] = [
      '#type' => 'select',
      '#multiple' => TRUE,
      '#size' => 20,
      '#title' => $this->t('Available styles'),
      '#options' => $available_styles,
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
    $form['styles']['actions']['delete'] = [];
    $form['styles']['all_styles'] = [
      '#type' => 'select',
      '#multiple' => TRUE,
      '#size' => 20,
      '#title' => $this->t('All styles'),
      '#options' => array_diff($all_styles, $available_styles),
    ];
    $form['default_style'] = [
      '#type' => 'select',
      '#title' => $this->t('Default style'),
      '#options' => $available_styles,
      '#default_value' => $config->get('default_style'),
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
    $value = $form_state->getValue('all_styles');
    $new_styles = array_keys($value);

    $config = $this->config('sc_pub.processor.citeprocphp.settings');
    $available_styles = $config->get('available_styles');

    $available_styles = array_merge($new_styles, $available_styles);
    ksort($available_styles);

    $config->set('available_styles', $available_styles)
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
    $config = $this->config('sc_pub.processor.citeprocphp.settings');
    $default_style = $config->get('default_style');
    $value = $form_state->getValue('available_styles');

    if (isset($value[$default_style])) {
      $form_state->setErrorByName('available_styles', $this->t('You can not delete default style'));
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
    $value = $form_state->getValue('available_styles');
    $removed_styles = array_keys($value);

    $config = $this->config('sc_pub.processor.citeprocphp.settings');
    $available_styles = $config->get('available_styles');

    $available_styles = array_diff($available_styles, $removed_styles);

    $config->set('available_styles', $available_styles)
      ->save();

    parent::submitForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $config = $this->config('sc_pub.processor.citeprocphp.settings');
    $config->set('default_style', $form_state->getValue('default_style'))
      ->save();

    parent::submitForm($form, $form_state);
  }

}
