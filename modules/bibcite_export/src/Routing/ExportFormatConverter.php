<?php

namespace Drupal\bibcite_export\Routing;


use Drupal\bibcite_export\BibciteExportFormatManagerInterface;
use Drupal\Core\ParamConverter\ParamConverterInterface;
use Symfony\Component\Routing\Route;

/**
 * Converts export plugin id to definition array.
 */
class ExportFormatConverter implements ParamConverterInterface {

  /**
   * The plugin manager of export formats.
   *
   * @var \Drupal\bibcite_export\BibciteExportFormatManagerInterface
   */
  protected $pluginManager;

  /**
   * Construct a new ExportFormatConverter.
   *
   * @param \Drupal\bibcite_export\BibciteExportFormatManagerInterface $plugin_manager
   *   The plugin manager of export formats.
   */
  public function __construct(BibciteExportFormatManagerInterface $plugin_manager) {
    $this->pluginManager = $plugin_manager;
  }

  /**
   * {@inheritdoc}
   */
  public function convert($value, $definition, $name, array $defaults) {
    if (!empty($value) && $this->pluginManager->hasDefinition($value)) {
      return $this->pluginManager->getDefinition($value);
    }

    return NULL;
  }

  /**
   * {@inheritdoc}
   */
  public function applies($definition, $name, Route $route) {
    return (!empty($definition['type']) && $definition['type'] == 'bibcite_format');
  }

}
