<?php

namespace Drupal\bibcite_import\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Common import form.
 */
class ImportForm extends FormBase {

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
      '#options' => [
        'bibtex' => 'Bibtex',
        'ris' => 'RIS',
      ],
    ];
    $form['batch'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Proceed import with batch'),
      '#default_value' => TRUE,
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

    $serializer = \Drupal::service('serializer');
    $decoded = $serializer->decode($data, $format);

    foreach ($decoded as $entry) {
      $entity = $serializer->denormalize($entry, \Drupal\bibcite_entity\Entity\Bibliography::class, $format);

      try {
        $entity->save();
        drupal_set_message($entity->label() . ' has been created');
      }
      catch (\Exception $e) {
        drupal_set_message($entity->label() . ' could not be saved', 'error');
      }

    }

  }

}
