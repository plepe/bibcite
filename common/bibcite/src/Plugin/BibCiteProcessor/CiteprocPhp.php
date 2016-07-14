<?php

namespace Drupal\bibcite\Plugin\BibCiteProcessor;

use AcademicPuma\CiteProc\CiteProc;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\bibcite\Plugin\BibCiteProcessorBase;
use Drupal\bibcite\Plugin\BibCiteProcessorInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Defines a style provider based on citeproc-php library.
 *
 * @BibCiteProcessor(
 *   id = "citeproc-php",
 *   label = @Translation("Citeproc PHP"),
 * )
 */
class CiteprocPhp extends BibCiteProcessorBase implements BibCiteProcessorInterface, ContainerFactoryPluginInterface {

  /**
   * Path to the citeproc libraries.
   */
  const LIBRARY_PATH = DRUPAL_ROOT . '/vendor/academicpuma';

  /**
   * Config factory service.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configFactory;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('config.factory')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, ConfigFactoryInterface $config_factory) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);

    $this->configFactory = $config_factory;
  }

  /**
   * {@inheritdoc}
   */
  public function getDescription() {
    return $this->t('Render citation by citeproc-php library');
  }

  /**
   * {@inheritdoc}
   */
  public function render(array $values, $style, $lang = 'en-US') {
    // @todo Use Drupal language.
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
  public function getAvailableStyles() {
    $all_styles = static::getStyles();

    $available_styles = $this->configFactory->get('bibcite.processor.citeprocphp.settings')->get('enabled_styles');
    // Flip array to use in intersect function.
    $available_styles = array_flip($available_styles);

    return array_intersect_key($all_styles, $available_styles);
  }

  /**
   * {@inheritdoc}
   */
  public function getDefaultStyleId() {
    return $this->configFactory->get('bibcite.processor.citeprocphp.settings')->get('default_style');
  }

  /**
   * {@inheritdoc}
   */
  public static function getStyles() {
    $cid = 'bibcite:styles_citeprocphp';
    $styles = [];

    if ($cache = \Drupal::cache()->get($cid)) {
      $styles = $cache->data;
    }
    else {
      $path = static::LIBRARY_PATH . '/styles';
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
  public static function getLocales() {
    $cid = 'bibcite:locales_citeprocphp';
    $locales = [];

    if ($cache = \Drupal::cache()->get($cid)) {
      $locales = $cache->data;
    }
    else {
      $path = static::LIBRARY_PATH . '/locales';

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
