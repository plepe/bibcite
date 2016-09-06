<?php

namespace Drupal\bibcite_import;


use Drupal\Core\DependencyInjection\ServiceProviderBase;
use Drupal\Core\DependencyInjection\ContainerBuilder;

/**
 * Service provider class.
 */
class BibciteImportServiceProvider extends ServiceProviderBase {

  /**
   * Add import CompilerPass to the system container.
   *
   * @param \Drupal\Core\DependencyInjection\ContainerBuilder $container
   *   The ContainerBuilder to register services to.
   */
  public function register(ContainerBuilder $container) {
    $container->addCompilerPass(new BibciteImportFormatsCompilerPass());
  }

}
