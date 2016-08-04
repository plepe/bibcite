<?php
/**
 * @file
 * Batch functions.
 */

/**
 * Complete a batch process.
 *
 * @param bool $success
 *   A boolean indicating whether the batch has completed successfully.
 * @param array $results
 *   The value set in $context['results'] by callback_batch_operation().
 * @param array|bool $operations
 *   If $success is FALSE, contains the operations that remained unprocessed.
 */
function bibcite_import_batch_finished($success, $results, $operations) {
  if ($success) {
    $message = \Drupal::translation()->formatPlural(count($results['success']), 'One entity processed.', '@count entities processed.');

    if (!empty($results['errors'])) {
      $error_message = \Drupal::translation()->formatPlural(count($results['errors']), 'One entry has not been processed.', '@count entries has not been processed.');
      drupal_set_message($error_message, 'error');
    }
  }
  else {
    $message = t('Finished with an error.');
  }

  drupal_set_message($message);
}

/**
 * Batch operation callback. Denormalize entries and try to save entity.
 *
 * @param array $entries
 *   Array of parsed entries.
 * @param string $format
 *   Format identifier.
 * @param array $context
 *   The batch context array, passed by reference.
 */
function bibcite_import_batch_callback($entries, $format, &$context) {
  $serializer = \Drupal::service('serializer');

  foreach ($entries as $entry) {
    /** @var \Drupal\Core\Entity\EntityInterface $entity */
    $entity = $serializer->denormalize($entry, \Drupal\bibcite_entity\Entity\Bibliography::class, $format);

    try {
      if ($entity->save()) {
        $context['results']['success'][] = $entity->id() . ' : ' . $entity->label();
      }
    }
    catch (Exception $e) {
      $message = [
        t('Entity can not be saved.'),
        t('Label: @label', ['@label' => $entity->label()]),
        '<pre>',
        $e->getMessage(),
        '</pre>',
      ];
      \Drupal::logger('bibcite_import')->error(implode("\n", $message));
      $context['results']['errors'][] = $entity->label();
    }

    $context['message'] = $entity->label();
  }
}
