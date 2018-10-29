<?php

namespace Drupal\bibcite_entity;

use Drupal\Component\Render\FormattableMarkup;
use Drupal\Core\Field\FieldItemList;
use Drupal\Core\TypedData\ComputedItemListTrait;

/**
 * Contributor name computed field.
 */
class ContributorName extends FieldItemList {

  use ComputedItemListTrait;

  /**
   * {@inheritdoc}
   */
  protected function computeValue() {
    /** @var \Drupal\bibcite_entity\Entity\ContributorInterface $contributor */
    $contributor = $this->parent->getValue();

    $arguments = [
      '@leading_title' => $contributor->getLeadingInitial(),
      '@last_name' => $contributor->getLastName(),
      '@middle_name' => $contributor->getMiddleName(),
      '@first_name' => $contributor->getFirstName(),
      '@nick' => $contributor->getNickName(),
      '@suffix' => $contributor->getSuffix(),
      '@prefix' => $contributor->getPrefix(),
    ];

    // @todo Dependency injection.
    $format = \Drupal::config('bibcite_entity.contributor.settings')->get('full_name_pattern') ?: '@prefix @first_name @last_name @suffix';
    $full_name = (string) new FormattableMarkup($format, $arguments);
    $value = trim(str_replace('  ', ' ', $full_name));

    $this->list[0] = $this->createItem(0, $value);
  }

  /**
   * Compute values every time.
   */
  protected function ensureComputedValue() {
    $this->computeValue();
  }

  /**
   * {@inheritdoc}
   */
  public function setValue($values, $notify = TRUE) {
    parent::setValue($values, $notify);

    /** @var \Drupal\bibcite_entity\Entity\ContributorInterface $contributor */
    $contributor = $this->parent->getValue();
    // Gather new string value, so we do not worry how it was set: as string
    // or as array.
    // We cannot use $this->value because it'll be newly calculated value but
    // from old name parts i.e. old value in result.
    $item = isset($this->list[0]) ? $this->list[0] : NULL;
    if ($item && ($value = $item->value)) {
      $name_parts = \Drupal::service('bibcite.human_name_parser')->parse(
        $value
      );

      foreach ($name_parts as $key => $name_part) {
        $contributor->$key = $name_part;
      }
    }
    // @todo Handle setting empty string and NULL.
  }

}
