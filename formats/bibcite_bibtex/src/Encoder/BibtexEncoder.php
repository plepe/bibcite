<?php

namespace Drupal\bibcite\Encoder;


use Symfony\Component\Serializer\Encoder\EncoderInterface;

/**
 * Bibtex format encoder.
 */
class BibtexEncoder implements EncoderInterface {

  /**
   * The format that this encoder supports.
   *
   * @var string
   */
  protected static $format = 'bibtex';

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

  }

}
