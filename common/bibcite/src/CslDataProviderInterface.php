<?php

namespace Drupal\bibcite;

/**
 * Provides interface defining CSL data provider.
 */
interface CslDataProviderInterface {

  /**
   * Change path where YAML files will be searched.
   *
   * @param string $files_path
   *   Path for YAML files.
   *
   * @return \Drupal\bibcite\CslDataProviderInterface
   *   The called CSL data provider.
   */
  public function setYamlFilesPath($files_path);

  /**
   * Get of all CSL fields.
   *
   * @return array
   *   Array of CSL fields.
   */
  public function getFields();

  /**
   * Get all CSL types.
   *
   * @return array
   *   Array of CSL types.
   */
  public function getTypes();

}
