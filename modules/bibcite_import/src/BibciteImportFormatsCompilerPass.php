<?php


namespace Drupal\bibcite_import;


use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Find encoders available for bibcite_import.
 */
class BibciteImportFormatsCompilerPass implements CompilerPassInterface {

  /**
   * Process search of available encoders.
   *
   * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
   *   Service container.
   */
  public function process(ContainerBuilder $container) {
    $export_formats = [];

    foreach ($container->findTaggedServiceIds('encoder') as $id => $attributes) {
      $encoder_attributes = &$attributes[0];

      if (isset($encoder_attributes['bibcite_import'],
        $encoder_attributes['bibcite_extension'],
        $encoder_attributes['bibcite_label'])) {

        $export_formats[$encoder_attributes['format']] = [
          'id' => $encoder_attributes['format'],
          'service' => $id,
          'extension' => $encoder_attributes['bibcite_extension'],
          'label' => $encoder_attributes['bibcite_label'],
        ];
      }
    }

    $container->setParameter('bibcite_import_formats', $export_formats);
  }

}
