<?php

namespace Drupal\sc_pub_entity\Normalizer;

/**
 * Normalizes/denormalizes datetime item to CSL format.
 */
class CslDateTimeItemNormalizer extends CslNormalizerBase {

  /**
   * The interface or class that this Normalizer supports.
   *
   * @var string
   */
  protected $supportedInterfaceOrClass = ['Drupal\datetime\Plugin\Field\FieldType\DateTimeItem'];

  /**
   * {@inheritdoc}
   */
  public function normalize($datetime_item, $format = NULL, array $context = array()) {
    $date_pars = date_parse($datetime_item->value);

    return [
      'date-parts' => [
        [$date_pars['year']],
        [$date_pars['month']],
        [$date_pars['day']],
      ],
      'literal' => $datetime_item->value,
    ];
  }

}
