<?php

namespace Drupal\bibcite_import;

use Drupal\Core\Cache\CacheBackendInterface;
use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\Core\Plugin\DefaultPluginManager;
use Drupal\Core\Plugin\Discovery\ContainerDerivativeDiscoveryDecorator;
use Drupal\Core\Plugin\Discovery\YamlDiscovery;

/**
 * Provides the default bibcite_import_format manager.
 */
class BibciteImportFormatManager extends DefaultPluginManager implements BibciteImportFormatManagerInterface {

  /**
   * Provides default values for all bibcite_import_format plugins.
   *
   * @var array
   */
  protected $defaults = array(
    'description' => '',
  );

  /**
   * Constructs a BibciteImportFormatManager object.
   *
   * @param \Drupal\Core\Extension\ModuleHandlerInterface $module_handler
   *   The module handler.
   * @param \Drupal\Core\Cache\CacheBackendInterface $cache_backend
   *   Cache backend instance to use.
   */
  public function __construct(ModuleHandlerInterface $module_handler, CacheBackendInterface $cache_backend) {
    // Add more services as required.
    $this->moduleHandler = $module_handler;
    $this->setCacheBackend($cache_backend, 'bibcite_import_format', ['bibcite_import_format']);
  }

  /**
   * {@inheritdoc}
   */
  protected function getDiscovery() {
    if (!isset($this->discovery)) {
      $this->discovery = new YamlDiscovery('bibcite.import.format', $this->moduleHandler->getModuleDirectories());
      $this->discovery->addTranslatableProperty('label', 'label_context');
      $this->discovery->addTranslatableProperty('description', 'description_context');
      $this->discovery = new ContainerDerivativeDiscoveryDecorator($this->discovery);
    }
    return $this->discovery;
  }

}
