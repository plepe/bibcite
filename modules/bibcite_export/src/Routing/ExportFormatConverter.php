<?php

namespace Drupal\bibcite_export\Routing;


use Drupal\Core\ParamConverter\ParamConverterInterface;
use Symfony\Component\Routing\Route;

/**
 * Converts export plugin id to definition array.
 */
class ExportFormatConverter implements ParamConverterInterface {

  /**
   * List of available export formats.
   *
   * @var array
   */
  protected $bibciteExportFormats;

  /**
   * Construct a new ExportFormatConverter.
   *
   * @param array $bibcite_export_formats
   *   Array of export formats attributes.
   */
  public function __construct(array $bibcite_export_formats) {
    $this->bibciteExportFormats = $bibcite_export_formats;
  }

  /**
   * {@inheritdoc}
   */
  public function convert($value, $definition, $name, array $defaults) {
    if (!empty($value) && isset($this->bibciteExportFormats[$value])) {
      return $this->bibciteExportFormats[$value];
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
