<?php

namespace Drupal\bibcite;

use Drupal\bibcite\Plugin\BibCiteProcessorInterface;
use Drupal\Component\Plugin\PluginManagerInterface;
use Drupal\Core\Config\ConfigFactoryInterface;

/**
 * Render CSL data to bibliography citation.
 */
class Styler implements StylerInterface {

  /**
   * Processor plugin.
   *
   * @var \Drupal\bibcite\Plugin\BibCiteProcessorInterface
   */
  protected $processor;

  /**
   * Manager of processor plugins.
   *
   * @var \Drupal\Component\Plugin\PluginManagerInterface
   */
  protected $pluginManager;

  /**
   * Config factory service.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configFactory;

  /**
   * Styler constructor.
   *
   * @param \Drupal\Component\Plugin\PluginManagerInterface $plugin_manager
   *   Manager of processor plugins.
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   Config factory service.
   */
  public function __construct(PluginManagerInterface $plugin_manager, ConfigFactoryInterface $config_factory) {
    $this->pluginManager = $plugin_manager;
    $this->configFactory = $config_factory;
  }

  /**
   * {@inheritdoc}
   */
  public function render(array $data, $style = NULL, $lang = 'en_US') {
    $style = $style ?: $this->getDefaultStyleId();
    $processor = $this->getProcessor();

    return $processor->render($data, $style, $lang);
  }

  /**
   * {@inheritdoc}
   */
  public function setProcessor(BibCiteProcessorInterface $processor) {
    $this->processor = $processor;
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function setProcessorById($processor_id) {
    $this->processor = $this->pluginManager->createInstance($processor_id);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getProcessor() {
    if (!$this->processor) {
      $config = $this->configFactory->get('bibcite.settings');
      $this->setProcessorById($config->get('processor'));
    }

    return $this->processor;
  }

  /**
   * {@inheritdoc}
   */
  public function getAvailableProcessors() {
    return $this->pluginManager->getDefinitions();
  }

  /**
   * {@inheritdoc}
   */
  public function getAvailableStyles() {
    return $this->processor->getAvailableStyles();
  }

  /**
   * {@inheritdoc}
   */
  public function getDefaultStyleId() {
    $settings = $this->configFactory->get('bibcite.settings');
    return $settings->get('default_style');
  }

}
