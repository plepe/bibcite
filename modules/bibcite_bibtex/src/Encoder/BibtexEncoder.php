<?php

namespace Drupal\bibcite_bibtex\Encoder;

use AudioLabs\BibtexParser\BibtexParser;
use Symfony\Component\Serializer\Encoder\DecoderInterface;
use Symfony\Component\Serializer\Encoder\EncoderInterface;

/**
 * Bibtex format encoder.
 */
class BibtexEncoder implements EncoderInterface, DecoderInterface {

  /**
   * The format that this encoder supports.
   *
   * @var string
   */
  protected static $format = 'bibtex';

  /**
   * {@inheritdoc}
   */
  public function supportsDecoding($format) {
    return $format == static::$format;
  }

  /**
   * {@inheritdoc}
   */
  public function decode($data, $format, array $context = []) {
    /*
     * Different sources uses different line endings in exports.
     * Convert all line endings to unix which is expected by BibtexParser.
     * \R is escape sequence of newline, equivalent to the following: (\r\n|\n|\x0b|\f|\r|\x85)
     * @see http://www.pcre.org/original/doc/html/pcrepattern.html Newline sequences.
     */
    $data = preg_replace("/\R/", "\n", $data);

    $parsed = BibtexParser::parse_string($data);

    $this->processEntries($parsed);

    return $parsed;
  }

  /**
   * Workaround about some things in BibtexParser library.
   *
   * @param array $parsed
   *   List of parsed entries.
   */
  protected function processEntries(array &$parsed) {
    foreach ($parsed as &$entry) {
      if (!empty($entry['pages']) && is_array($entry['pages'])) {
        $entry['pages'] = implode('-', $entry['pages']);
      }

      if (!empty($entry['keywords'])) {
        $entry['keywords'] = array_map(function ($keyword) {
          return trim($keyword);
        }, explode(',', $entry['keywords']));
      }
    }
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
  public function encode($data, $format, array $context = []) {
    if (isset($data['type'])) {
      $data = [$data];
    }

    $data = array_map(function ($raw) {
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
    if (empty($data['reference'])) {
      $data['reference'] = $data['type'];
    }

    $entry = $this->buildStart($data['type'], $data['reference']);

    unset($data['type']);
    unset($data['reference']);

    foreach ($data as $key => $value) {
      $entry .= $this->buildLine($key, $value);
    }

    $entry .= $this->buildEnd();

    return $entry;
  }

  /**
   * Build first string for bibtex entry.
   *
   * @param string $type
   *   Publication type in Bibtex format.
   * @param string $reference
   *   Reference key.
   *
   * @return string
   *   First entry string.
   */
  protected function buildStart($type, $reference) {
    return '@' . $type . '{' . $reference . ',' . "\n";
  }

  /**
   * Build entry line.
   *
   * @param string $key
   *   Line key.
   * @param string|array $value
   *   Line value.
   *
   * @return string
   *   Entry line.
   */
  protected function buildLine($key, $value) {
    switch ($key) {
      case 'author':
        $value = implode(' and ', $value);
        break;

      case 'keywords':
        $value = implode(', ', $value);
        break;
    }

    return '  ' . $key . ' = {' . $value . '},' . "\n";
  }

  /**
   * Build the end of Bibtex entry.
   *
   * @return string
   *   End line for the Bibtex entry.
   */
  protected function buildEnd() {
    return "}\n";
  }

}
