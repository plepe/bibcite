<?php

namespace Drupal\bibcite_import\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Common import form.
 */
class ImportForm extends FormBase {

  /**
   * The list of import formats definitions.
   *
   * @var array
   */
  protected $bibciteImportFormats;

  /**
   * Serializer service.
   *
   * @var \Symfony\Component\Serializer\SerializerInterface
   */
  protected $serializer;

  /**
   * Import form constructor.
   *
   * @param \Symfony\Component\Serializer\SerializerInterface $serializer
   *   Import plugins manager.
   * @param array $bibcite_import_formats
   *   List of available import formats.
   */
  public function __construct(SerializerInterface $serializer, array $bibcite_import_formats) {
    $this->serializer = $serializer;
    $this->bibciteImportFormats = $bibcite_import_formats;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('serializer'),
      $container->getParameter('bibcite_import_formats')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'bibcite_import';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['file'] = [
      '#type' => 'file',
      '#title' => $this->t('File'),
      '#multiple' => FALSE,
    ];
    $form['format'] = [
      '#type' => 'select',
      '#title' => $this->t('Format'),
      '#options' => array_map(function($definition) {
        return $definition['label'];
      }, $this->bibciteImportFormats),
    ];

    $form['actions'] = ['#type' => 'actions'];
    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Import'),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    $all_files = $this->getRequest()->files->get('files', []);
    if (!empty($all_files['file'])) {
      $file_upload = $all_files['file'];
      if ($file_upload->isValid()) {
        $form_state->setValue('file', $file_upload->getRealPath());
        return;
      }
    }

    $form_state->setErrorByName('file', $this->t('The file could not be uploaded.'));

    parent::validateForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $data = file_get_contents($form_state->getValue('file'));
    $format = $form_state->getValue('format');

    $decoded = $this->serializer->decode($data, $format);
    $chunks = array_chunk($decoded, 10);

    $batch = [
      'title' => t('Import bibliographic data'),
      'operations' => [],
      'finished' => 'bibcite_import_batch_finished',
      'file' => drupal_get_path('module', 'bibcite_import') . '/bibcite_import.batch.inc',
    ];

    foreach ($chunks as $chunk) {
      $batch['operations'][] = [
        'bibcite_import_batch_callback', [$chunk, $format],
      ];
    }

    batch_set($batch);
  }

}
