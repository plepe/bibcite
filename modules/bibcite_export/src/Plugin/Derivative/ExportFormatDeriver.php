<?php

namespace Drupal\bibcite_export\Plugin\Derivative;


use Drupal\bibcite\Plugin\BibciteFormatManagerInterface;
use Drupal\Component\Plugin\Derivative\DeriverBase;
use Drupal\Core\Plugin\Discovery\ContainerDeriverInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides action plugin for all export formats.
 */
class ExportFormatDeriver extends DeriverBase implements ContainerDeriverInterface {

  /**
   * Bibcite format manager service.
   *
   * @var \Drupal\bibcite\Plugin\BibciteFormatManagerInterface
   */
  protected $formatManager;

  /**
   * Identifier of base plugin.
   *
   * @var string
   */
  protected $basePluginId;

  /**
   * Construct new ExportFormatDeriver object.
   *
   * @param \Drupal\bibcite\Plugin\BibciteFormatManagerInterface $format_manager
   *   Bibcite format manager service.
   * @param string $base_plugin_id
   *   Identifier of base plugin.
   */
  public function __construct(BibciteFormatManagerInterface $format_manager, $base_plugin_id) {
    $this->formatManager = $format_manager;
    $this->basePluginId = $base_plugin_id;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, $base_plugin_id) {
    return new static(
      $container->get('plugin.manager.bibcite_format'),
      $base_plugin_id
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getDerivativeDefinitions($base_plugin_definition) {
    foreach ($this->formatManager->getExportDefinitions() as $format_definition) {
      $label = t('Export to @format', ['@format' => $format_definition['label']]);

      $this->derivatives[$format_definition['id']] = [
        'format' => $format_definition['id'],
        'label' => $label,
      ] + $base_plugin_definition;
    }

    return $this->derivatives;
  }

}
