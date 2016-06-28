<?php

/**
 * @file
 * Contains sc_pub_contributor.page.inc.
 *
 * Page callback for Contributor entities.
 */

use Drupal\Core\Render\Element;
use Drupal\Core\Link;
use Drupal\Core\Url;

/**
 * Prepares variables for Contributor templates.
 *
 * Default template: sc_pub_contributor.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_sc_pub_contributor(array &$variables) {
  // Fetch Contributor Entity Object.
  $sc_pub_contributor = $variables['elements']['#sc_pub_contributor'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
