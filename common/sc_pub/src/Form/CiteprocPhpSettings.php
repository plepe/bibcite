<?php

namespace Drupal\sc_pub\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

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

    $form['available_styles'] = [];
    $form['all_styles'] = [];
    $form['default_style'] = [];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    dsm($form_state->getValues());
  }

}
