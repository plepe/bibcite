<?php

namespace Drupal\bibcite;

use Symfony\Component\Yaml\Yaml;

/**
 * Working with CSL data.
 */
class CslDataProvider implements CslDataProviderInterface {

  protected $yamlFilesPath;

  /**
   * CslDataProvider constructor.
   *
   * @param string|null $files_path
   *    Path where YAML files will be searched.
   */
  public function __construct($files_path = NULL) {
    $this->yamlFilesPath = $files_path ?: drupal_get_path('module', 'bibcite');
  }

  /**
   * {@inheritdoc}
   */
  public function setYamlFilesPath($files_path) {
    $this->yamlFilesPath = $files_path;
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getFields() {
    return $this->parseYaml('bibcite.csl.field.yml', 'bibcite:csl_fields');
  }

  /**
   * {@inheritdoc}
   */
  public function getTypes() {
    return $this->parseYaml('bibcite.csl.type.yml', 'bibcite:csl_types');
  }

  /**
   * Get data from YAML with using cache system.
   *
   * @param string $filename
   *   Full YAML file name.
   * @param string|null $cid
   *   Identifier for cache.
   *
   * @return mixed
   *   Data from YAML file.
   */
  protected function parseYaml($filename, $cid = NULL) {
    if ($cid && $cache = \Drupal::cache()->get($cid)) {
      return $cache->data;
    }

    $data = Yaml::parse($this->yamlFilesPath . '/' . $filename);

    if ($cid) {
      \Drupal::cache()->set($cid, $data);
    }

    return $data;
  }

}
