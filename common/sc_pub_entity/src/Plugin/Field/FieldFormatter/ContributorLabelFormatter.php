<?php

namespace Drupal\bibcite_entity\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\Plugin\Field\FieldFormatter\EntityReferenceLabelFormatter;

/**
 * Plugin implementation of the 'entity reference label' formatter.
 *
 * @FieldFormatter(
 *   id = "bibcite_contributor_label",
 *   label = @Translation("Label"),
 *   description = @Translation("Display the label of the contributors."),
 *   field_types = {
 *     "bibcite_contributor"
 *   }
 * )
 */
class ContributorLabelFormatter extends EntityReferenceLabelFormatter {
}
