<?php

namespace Drupal\sc_pub\Plugin;

use Drupal\Core\Plugin\DefaultPluginManager;
use Drupal\Core\Cache\CacheBackendInterface;
use Drupal\Core\Extension\ModuleHandlerInterface;

/**
 * Provides the Processor plugin manager.
 */
class ScPubProcessorManager extends DefaultPluginManager {


  /**
   * Constructor for ScPubProcessorManager objects.
   *
   * @param \Traversable $namespaces
   *   An object that implements \Traversable which contains the root paths
   *   keyed by the corresponding namespace to look for plugin implementations.
   * @param \Drupal\Core\Cache\CacheBackendInterface $cache_backend
   *   Cache backend instance to use.
   * @param \Drupal\Core\Extension\ModuleHandlerInterface $module_handler
   *   The module handler to invoke the alter hook with.
   */
  public function __construct(\Traversable $namespaces, CacheBackendInterface $cache_backend, ModuleHandlerInterface $module_handler) {
    parent::__construct('Plugin/ScPubProcessor', $namespaces, $module_handler, 'Drupal\sc_pub\Plugin\ScPubProcessorInterface', 'Drupal\sc_pub\Annotation\ScPubProcessor');

    $this->alterInfo('sc_pub_sc_pub_processor_info');
    $this->setCacheBackend($cache_backend, 'sc_pub_sc_pub_processor_plugins');
  }

}
