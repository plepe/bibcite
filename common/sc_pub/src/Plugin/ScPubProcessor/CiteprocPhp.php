<?php

namespace Drupal\sc_pub\Plugin\ScPubProcessor;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\sc_pub\CiteprocPhpInterface;
use Drupal\sc_pub\Plugin\ScPubProcessorBase;
use Drupal\sc_pub\Plugin\ScPubProcessorInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Defines a style provider based on citeproc-php library.
 *
 * @ScPubProcessor(
 *   id = "citeproc-php",
 *   label = @Translation("Citeproc PHP"),
 * )
 */
class CiteprocPhp extends ScPubProcessorBase implements ScPubProcessorInterface, ContainerFactoryPluginInterface {

  /**
   * CiteprocPhp service.
   *
   * @var \Drupal\sc_pub\CiteprocPhpInterface
   */
  protected $citeproc;

  /**
   * Config factory service.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configFactory;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('sc_pub.citeproc_php'),
      $container->get('config.factory')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, CiteprocPhpInterface $citeproc, ConfigFactoryInterface $config_factory) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);

    $this->citeproc = $citeproc;
    $this->configFactory = $config_factory;
  }

  /**
   * {@inheritdoc}
   */
  public function getDescription() {
    return $this->t('Render citation by citeproc-php library');
  }

  /**
   * {@inheritdoc}
   */
  public function render(array $values, $style) {
    // @todo Use Drupal language.
    return $this->citeproc->render($values, $style);
  }

  /**
   * {@inheritdoc}
   */
  public function getAvailableStyles() {
    $all_styles = $this->citeproc->getStyles();

    $available_styles = $this->configFactory->get('sc_pub.processor.citeprocphp.settings')->get('enabled_styles');
    // Flip array to use in intersect function.
    $available_styles = array_flip($available_styles);

    return array_intersect_key($all_styles, $available_styles);
  }

  /**
   * {@inheritdoc}
   */
  public function getDefaultStyleId() {
    return $this->configFactory->get('sc_pub.processor.citeprocphp.settings')->get('default_style');
  }

}
