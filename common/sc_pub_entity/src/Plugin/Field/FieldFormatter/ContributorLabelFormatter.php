<?php

namespace Drupal\sc_pub_entity\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\Plugin\Field\FieldFormatter\EntityReferenceLabelFormatter;

/**
 * Plugin implementation of the 'entity reference label' formatter.
 *
 * @FieldFormatter(
 *   id = "sc_pub_contributor_label",
 *   label = @Translation("Label"),
 *   description = @Translation("Display the label of the contributors."),
 *   field_types = {
 *     "sc_pub_contributor"
 *   }
 * )
 */
class ContributorLabelFormatter extends EntityReferenceLabelFormatter {
}
