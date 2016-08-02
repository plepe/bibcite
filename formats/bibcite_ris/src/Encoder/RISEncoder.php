<?php

namespace Drupal\bibcite_ris\Encoder;

use LibRIS\RISReader;
use Symfony\Component\Serializer\Encoder\DecoderInterface;
use Symfony\Component\Serializer\Encoder\EncoderInterface;

/**
 * RIS format encoder.
 */
class RISEncoder implements EncoderInterface, DecoderInterface {

  /**
   * The format that this encoder supports.
   *
   * @var string
   */
  protected static $format = 'ris';

  /**
   * {@inheritdoc}
   */
  public function supportsDecoding($format) {
    return $format == static::$format;
  }

  /**
   * {@inheritdoc}
   */
  public function decode($data, $format, array $context = array()) {
    $ris = new \LibRIS\RISReader();
    $ris->parseString($data);
    return $ris->getRecords();
  }

  /**
   * {@inheritdoc}
   */
  public function supportsEncoding($format) {
    return $format == static::$format;
  }

  /**
   * {@inheritdoc}
   */
  public function encode($data, $format, array $context = array()) {
    $data = array_map(function($raw) {
      return $this->buildEntry($raw);
    }, $data);

    return implode("\n", $data);
  }

  /**
   * Build BibTex entry string.
   *
   * @param array $data
   *   Array of BibTex values.
   *
   * @return string
   *   Formatted BibTex string.
   */
  protected function buildEntry(array $data) {
    $entry = NULL;
    foreach ($data as $key => $value) {
      $entry .= $this->buildLine($key, $value);
    }

    return $entry;
  }

  /**
   * Build entry line.
   *
   * @param string $key
   *   Line key.
   * @param string $value
   *   Line value.
   *
   * @return string
   *   Entry line.
   */
  protected function buildLine($key, $value) {
    return $key . '  - ' . $value . "\n";
  }

}
