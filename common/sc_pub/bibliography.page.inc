<?php

/**
 * @file
 * Contains bibliography.page.inc.
 *
 * Page callback for Bibliography entities.
 */

use Drupal\Core\Render\Element;
use Drupal\Core\Link;
use Drupal\Core\Url;

/**
 * Prepares variables for Bibliography templates.
 *
 * Default template: bibliography.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_bibliography(array &$variables) {
  // Fetch Bibliography Entity Object.
  $bibliography = $variables['elements']['#bibliography'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
