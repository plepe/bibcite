<?php

namespace Drupal\bibcite;

use AcademicPuma\CiteProc\CiteProc;

/**
 * Public Drupal interface for the citeproc-php library.
 */
class CiteprocPhp implements CiteprocPhpInterface {

  /**
   * Path to the citeproc libraries.
   */
  const LIBRARY_PATH = DRUPAL_ROOT . '/vendor/academicpuma';

  /**
   * {@inheritdoc}
   */
  public function render($values, $style, $lang = 'en-US') {
    $csl = CiteProc::loadStyleSheet($style);

    $cite_proc = new CiteProc($csl, $lang);

    if (!$values instanceof \stdClass) {
      $values = json_decode(json_encode($values));
    }

    return $cite_proc->render($values);
  }

  /**
   * {@inheritdoc}
   */
  public function getStyles() {
    $cid = 'bibcite:styles_citeprocphp';
    $styles = [];

    if ($cache = \Drupal::cache()->get($cid)) {
      $styles = $cache->data;
    }
    else {
      $path = $this::LIBRARY_PATH . '/styles';
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
  public function getLocales() {
    $cid = 'bibcite:styles_citeprocphp';
    $locales = [];

    if ($cache = \Drupal::cache()->get($cid)) {
      $locales = $cache->data;
    }
    else {
      $path = $this::LIBRARY_PATH . '/locales';

      $json_raw = file_get_contents($path . '/locales.json');
      $data = json_decode($json_raw);

      if (isset($data->{'language-names'})) {
        foreach ((array) $data->{'language-names'} as $lang_key => $lang_names) {
          $locales[$lang_key] = reset($lang_names);
        }

        \Drupal::cache()->set($cid, $locales);
      }
    }

    return $locales;
  }

}
