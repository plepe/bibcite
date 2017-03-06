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
    /*
     * Workaround for weird behavior of "LibRIS" library.
     *
     * Replace LF line ends by CRLF.
     */
    $data = str_replace("\n", "\r\n", $data);

    $ris = new RISReader();
    $ris->parseString($data);
    $records = $ris->getRecords();

    // Workaround for weird behavior of "LibRIS" library.
    foreach ($records as &$record) {
      foreach ($record as $key => $value) {
        if (is_array($value) && count($value) == 1) {
          $record[$key] = reset($value);
        }
      }
    }

    return $records;
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
    if (isset($data['TY'])) {
      $data = [$data];
    }

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
      if (is_array($value)) {
        $entry .= $this->buildMultiLine($key, $value);
      }
      else {
        $entry .= $this->buildLine($key, $value);
      }
    }

    $entry .= $this->buildEnd();

    return $entry;
  }

  /**
   * Build multi line entry.
   *
   * @param string $key
   *   Line key.
   * @param array $value
   *   Array of multi line values.
   *
   * @return string
   *   Multi line entry.
   */
  protected function buildMultiLine($key, array $value) {
    $lines = '';

    foreach ($value as $item) {
      $lines .= $this->buildLine($key, $item);
    }

    return $lines;
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
    return $key . ' - ' . $value . "\n";
  }

  /**
   * Build the end of Bibtex entry.
   *
   * @return string
   *   End line for the Bibtex entry.
   */
  protected function buildEnd() {
    return $this->buildLine('ER', '');
  }

}
