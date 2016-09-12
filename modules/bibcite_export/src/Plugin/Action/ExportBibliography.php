<?php

namespace Drupal\bibcite_export\Plugin\Action;


use Drupal\Core\Action\ActionBase;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Session\AccountInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Export bibliography.
 *
 * @Action(
 *   id = "bibcite_export_action",
 *   label = @Translation("Export bibliography"),
 *   type = "bibliography",
 *   deriver = "Drupal\bibcite_export\Plugin\Derivative\ExportFormatDeriver"
 * )
 */
class ExportBibliography extends ActionBase implements ContainerFactoryPluginInterface {

  /**
   * The serializer service.
   *
   * @var \Symfony\Component\Serializer\SerializerInterface
   */
  protected $serializer;

  /**
   * The list of export formats definitions.
   *
   * @var array
   */
  protected $bibcteExportFormats;

  /**
   * Constructs a new ExportBibliography action.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin ID for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param \Symfony\Component\Serializer\SerializerInterface $serializer
   *   The serializer service.
   * @param array $bibcite_export_formats
   *   The list of export formats definitions.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, SerializerInterface $serializer, array $bibcite_export_formats) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);

    $this->serializer = $serializer;
    $this->bibcteExportFormats = $bibcite_export_formats;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static($configuration, $plugin_id, $plugin_definition,
      $container->get('serializer'),
      $container->getParameter('bibcite_export_formats')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function execute($entity = NULL) {
    $this->executeMultiple([$entity]);
  }

  /**
   * {@inheritdoc}
   */
  public function executeMultiple(array $entities) {
    $format_info = $this->bibcteExportFormats[$this->pluginDefinition['format']];
    $filename = sprintf('bibcite-multiple-export.%s', $format_info['extension']);

    $content = $this->serializer->serialize($entities, $format_info['id']);

    $response = new Response($content);
    $response->headers->set('Cache-Control', 'no-cache');
    $response->headers->set('Content-type', 'application/octet-stream');
    $response->headers->set('Content-Disposition', 'attachment; filename="' . $filename . '";');

    $response->send();

    // @todo Use route or temporary file to dispatch file to the user.
    exit();
  }

  /**
   * {@inheritdoc}
   */
  public function access($object, AccountInterface $account = NULL, $return_as_object = FALSE) {
    return $object->access('view', $account, $return_as_object);
  }

}
