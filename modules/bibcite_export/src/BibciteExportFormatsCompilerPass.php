<?php

namespace Drupal\bibcite_export;


use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;

/**
 * Find encoders available for bibcite_export.
 */
class BibciteExportFormatsCompilerPass implements CompilerPassInterface {

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

      if (isset($encoder_attributes['bibcite_export'],
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

    $container->setParameter('bibcite_export_formats', $export_formats);
  }

}
