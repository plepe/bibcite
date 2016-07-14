<?php

namespace Drupal\bibcite\Form;

use Drupal\Component\Plugin\PluginManagerInterface;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Common configuration.
 */
class SettingsForm extends ConfigFormBase {

  /**
   * BibCiteProcessor plugins manager.
   *
   * @var \Drupal\Component\Plugin\PluginManagerInterface
   */
  protected $processorManager;

  /**
   * {@inheritdoc}
   */
  public function __construct(ConfigFactoryInterface $config_factory, PluginManagerInterface $processor_manager) {
    parent::__construct($config_factory);
    $this->processorManager = $processor_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('config.factory'),
      $container->get('plugin.manager.bibcite_processor.processor')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'bibcite_settings';
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
    $config = $this->config('bibcite.settings');

    $available_processors = array_map(function($definition) {
      return $definition['label'];
    }, $this->processorManager->getDefinitions());

    $form['processor'] = array(
      '#type' => 'select',
      '#options' => $available_processors,
      '#title' => $this->t('Processor'),
      '#default_value' => $config->get('processor'),
    );

    /** @var \Drupal\bibcite\Plugin\BibCiteProcessorInterface $processor */
    $processor = $this->processorManager->createInstance($config->get('processor'));
    $description = $processor->getDescription();

    if (is_array($description)) {
      $form['description'] = [
        '#type' => 'container',
        'content' => $description,
      ];
    }
    else {
      $form['description'] = [
        '#type' => 'item',
        '#markup' => $description,
      ];
    }

    $form['default_style'] = [
      '#type' => 'select',
      '#title' => $this->t('Default style'),
      '#options' => $processor->getAvailableStyles(),
      '#default_value' => $config->get('default_style'),
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $config = $this->config('bibcite.settings');
    $config
      ->set('processor', $form_state->getValue('processor'))
      ->set('default_style', $form_state->getValue('default_style'))
      ->save();

    parent::submitForm($form, $form_state);
  }

}
