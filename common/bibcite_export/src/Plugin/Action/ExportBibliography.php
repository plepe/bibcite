<?php

namespace Drupal\bibcite_export\Plugin\Action;


use Drupal\Core\Action\ActionBase;
use Drupal\Core\Session\AccountInterface;
use Symfony\Component\HttpFoundation\Response;

/**
 * Export bibliography.
 *
 * @Action(
 *   id = "bibcite_export_action",
 *   label = @Translation("Export bibliography"),
 *   type = "bibliography"
 * )
 */
class ExportBibliography extends ActionBase {

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
    // @todo Dependency injection.
    $serializer = \Drupal::service('serializer');

    $content = $serializer->serialize($entities, 'bibtex');

    $response = new Response($content);
    $response->headers->set('Cache-Control', 'no-cache');
    $response->headers->set('Content-type', 'text/plain');
    $response->headers->set('Content-Disposition', 'attachment; filename="multiple.bib";');

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
