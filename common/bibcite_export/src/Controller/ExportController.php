<?php

namespace Drupal\bibcite_export\Controller;

use Drupal\bibcite_export\BibciteExportFormatManagerInterface;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Entity\EntityInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Class ExportController.
 *
 * @package Drupal\bibcite_export\Controller
 */
class ExportController extends ControllerBase {

  /**
   * Symfony\Component\Serializer\Serializer definition.
   *
   * @var \Symfony\Component\Serializer\Serializer
   */
  protected $serializer;

  /**
   * Drupal\bibcite_export\BibciteExportFormatManager definition.
   *
   * @var \Drupal\bibcite_export\BibciteExportFormatManager
   */
  protected $pluginManager;

  /**
   * {@inheritdoc}
   */
  public function __construct(SerializerInterface $serializer, BibciteExportFormatManagerInterface $plugin_manager) {
    $this->serializer = $serializer;
    $this->pluginManager = $plugin_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('serializer'),
      $container->get('plugin.manager.bibcite_export_format')
    );
  }

  /**
   * Export entity to available export format.
   *
   * @param array $bibcite_format
   *   Format definition.
   * @param string $entity_type
   *   Entity type identifier.
   * @param \Drupal\Core\Entity\EntityInterface $entity
   *   Entity object.
   *
   * @return \Symfony\Component\HttpFoundation\Response
   *   Response object contains serialized bibliography data.
   */
  public function export($bibcite_format, $entity_type, EntityInterface $entity) {
    $response = new Response();

    if ($result = $this->serializer->serialize($entity, $bibcite_format['id'])) {
      $response->headers->set('Cache-Control', 'no-cache');
      $response->headers->set('Content-type', 'text/plain');
      $response->headers->set('Content-Disposition', 'attachment; filename="' . $entity->id() . '.' . $bibcite_format['extension'] . '";');

      $response->sendHeaders();

      $response->setContent(implode("\n", $result));
    }

    return $response;
  }

}
