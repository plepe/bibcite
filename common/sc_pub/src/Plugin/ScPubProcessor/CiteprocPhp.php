<?php

namespace Drupal\sc_pub\Plugin\ScPubProcessor;

use Drupal\sc_pub\Plugin\ScPubProcessorBase;
use Drupal\sc_pub\Plugin\ScPubProcessorInterface;
use AcademicPuma\CiteProc\CiteProc;

/**
 * Defines a style provider based on citeproc-php library.
 *
 * @ScPubProcessor(
 *   id = "citeproc-php",
 *   label = @Translation("Citeproc PHP"),
 * )
 */
class CiteprocPhp extends ScPubProcessorBase implements ScPubProcessorInterface {

  /**
   * {@inheritdoc}
   */
  public function getDescription() {
    return $this->t('Render citation by citeproc-php library');
  }

  /**
   * {@inheritdoc}
   */
  public function render(array $values, $style) {
    $csl = CiteProc::loadStyleSheet($style);

    // @todo Use Drupal language.
    $lang = 'en-US';

    $cite_proc = new CiteProc($csl, $lang);

    $data = json_decode(json_encode($values));

    // @todo Make render configurable.
    return $cite_proc->render($data);
  }

  /**
   * {@inheritdoc}
   */
  public function getAvailableStyles() {
    $cid = 'sc_pub_styles:' . $this->getPluginId();

    $styles = [];

    if ($cache = \Drupal::cache()->get($cid)) {
      $styles = $cache->data;
    }
    else {
      $path = DRUPAL_ROOT . '/vendor/academicpuma/styles';
      $files = scandir($path);

      foreach ($files as $filename) {
        if ($stylename = strstr($filename, '.csl', TRUE)) {
          $xml = simplexml_load_file($path . '/' . $filename);
          $styles[$stylename] = (string) $xml->info->title;
        }
      }

      \Drupal::cache()->set($cid, $styles);
    }

    return $styles;
  }

  /**
   * {@inheritdoc}
   */
  public function getDefaultStyleId() {
    return 'apa';
  }

}
