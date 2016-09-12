<?php

namespace Drupal\bibcite_export\Plugin\Derivative;


use Drupal\Component\Plugin\Derivative\DeriverBase;
use Drupal\Core\Plugin\Discovery\ContainerDeriverInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides action plugin for all export formats.
 */
class ExportFormatDeriver extends DeriverBase implements ContainerDeriverInterface {

  /**
   * List of format definitions.
   *
   * @var array
   */
  protected $bibciteExportFormats;

  /**
   * Identifier of base plugin.
   *
   * @var string
   */
  protected $basePluginId;

  /**
   * ExportFormatDeriver constructor.
   *
   * @param array $bibcite_export_formats
   *   List of format definitions.
   * @param string $base_plugin_id
   *   Identifier of base plugin.
   */
  public function __construct(array $bibcite_export_formats, $base_plugin_id) {
    $this->bibciteExportFormats = $bibcite_export_formats;
    $this->basePluginId = $base_plugin_id;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, $base_plugin_id) {
    return new static(
      $container->getParameter('bibcite_export_formats'),
      $base_plugin_id
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getDerivativeDefinitions($base_plugin_definition) {
    foreach ($this->bibciteExportFormats as $format_info) {
      $label = t('Export to @format', ['@format' => $format_info['label']]);

      $this->derivatives[$format_info['id']] = [
        'format' => $format_info['id'],
        'label' => $label,
      ] + $base_plugin_definition;
    }

    return $this->derivatives;
  }

}
