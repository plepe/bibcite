<?php

namespace Drupal\bibcite_export;


use Drupal\Core\DependencyInjection\ContainerBuilder;
use Drupal\Core\DependencyInjection\ServiceProviderBase;

/**
 * Service provider class.
 */
class BibciteExportServiceProvider extends ServiceProviderBase {

  /**
   * Add export CompilerPass to the system container.
   *
   * @param \Drupal\Core\DependencyInjection\ContainerBuilder $container
   *   The ContainerBuilder to register services to.
   */
  public function register(ContainerBuilder $container) {
    $container->addCompilerPass(new BibciteExportFormatsCompilerPass());
  }

}
