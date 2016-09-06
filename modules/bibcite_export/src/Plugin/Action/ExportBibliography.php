<?php

namespace Drupal\bibcite_export\Plugin\Action;


use Drupal\Core\Action\ConfigurableActionBase;
use Drupal\Core\Form\FormStateInterface;
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
 *   type = "bibliography"
 * )
 */
class ExportBibliography extends ConfigurableActionBase implements ContainerFactoryPluginInterface {

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
    $content = $this->serializer->serialize($entities, $this->configuration['format']);

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

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    return array(
      'format' => 'bibtex',
    );
  }

  /**
   * {@inheritdoc}
   */
  public function buildConfigurationForm(array $form, FormStateInterface $form_state) {
    $form['format'] = [
      '#type' => 'select',
      '#title' => $this->t('Format'),
      '#options' => array_map(function($definition) {
        return $definition['label'];
      }, $this->bibcteExportFormats),
      '#empty_option' => $this->t('- Select -'),
      '#requried' => TRUE,
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitConfigurationForm(array &$form, FormStateInterface $form_state) {
    $this->configuration['format'] = $form_state->getValue('format');
  }

}
