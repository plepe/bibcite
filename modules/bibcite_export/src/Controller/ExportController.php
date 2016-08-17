<?php

namespace Drupal\bibcite_export\Controller;

use Drupal\bibcite_export\BibciteExportFormatManagerInterface;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Class ExportController.
 *
 * @package Drupal\bibcite_export\Controller
 */
class ExportController extends ControllerBase {

  /**
   * The serializer service.
   *
   * @var \Symfony\Component\Serializer\Serializer
   */
  protected $serializer;

  /**
   * The bibcite export format plugin manager.
   *
   * @var \Drupal\bibcite_export\BibciteExportFormatManager
   */
  protected $pluginManager;

  /**
   * The entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * {@inheritdoc}
   */
  public function __construct(SerializerInterface $serializer, BibciteExportFormatManagerInterface $plugin_manager, EntityTypeManagerInterface $entity_type_manager) {
    $this->serializer = $serializer;
    $this->pluginManager = $plugin_manager;
    $this->entityTypeManager = $entity_type_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('serializer'),
      $container->get('plugin.manager.bibcite_export_format'),
      $container->get('entity_type.manager')
    );
  }

  /**
   * Serialize and create response contains file in selectrd format.
   *
   * @param array $entities
   *   Array of entities objects.
   * @param array $bibcite_format
   *   Format definition.
   * @param null|string $filename
   *   Filename. Will be generated if not provided.
   *
   * @return \Symfony\Component\HttpFoundation\Response
   *   Response object contains serialized bibliography data.
   */
  protected function processExport(array $entities, array $bibcite_format, $filename = NULL) {
    if (!$filename) {
      $filename = (string) $bibcite_format['label'];
    }

    $response = new Response();

    if ($result = $this->serializer->serialize($entities, $bibcite_format['id'])) {
      $response->headers->set('Cache-Control', 'no-cache');
      $response->headers->set('Content-type', 'text/plain');
      $response->headers->set('Content-Disposition', 'attachment; filename="' . $filename . '.' . $bibcite_format['extension'] . '";');

      $response->sendHeaders();

      $result = is_array($result) ? implode("\n", $result) : $result;
      $response->setContent($result);
    }

    return $response;
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
  public function export(array $bibcite_format, $entity_type, EntityInterface $entity) {
    $filename = $entity_type . '-' . $entity->id() . (string) $bibcite_format['label'];
    return $this->processExport([$entity], $bibcite_format, $filename);
  }

  /**
   * Export multiple entities to available export formats.
   *
   * @param array $bibcite_format
   *   Format definition.
   * @param string $entity_type
   *   Entity type identifier.
   * @param \Symfony\Component\HttpFoundation\Request $request
   *   Request object.
   *
   * @return \Symfony\Component\HttpFoundation\Response
   *   Response object contains serialized bibliography data.
   *
   * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
   *   Throw 404 error if Id parameter is not provided or entities not loaded.
   */
  public function exportMultiple(array $bibcite_format, $entity_type, Request $request) {
    $entity_type_manager = \Drupal::entityTypeManager();
    $storage = $entity_type_manager->getStorage($entity_type);

    if (!$request->query->has('id')) {
      throw new NotFoundHttpException();
    }

    $ids = explode(' ', $request->query->get('id'));
    $entities = $storage->loadMultiple($ids);

    if (!$entities) {
      throw new NotFoundHttpException();
    }

    $filename = $entity_type . '-' . (string) $bibcite_format['label'];
    return $this->processExport($entities, $bibcite_format, $filename);
  }

}
