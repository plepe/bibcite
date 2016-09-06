<?php

namespace Drupal\Tests\bibcite_import\Kernel;

use Drupal\Core\Entity\EntityInterface;
use Drupal\KernelTests\KernelTestBase;
use Drupal\bibcite_entity\Entity\Bibliography;
use Symfony\Component\Yaml\Yaml;

/**
 * Basic import tests.
 *
 * @group bibcite
 */
class ImportBasicTest extends KernelTestBase {

  /**
   * Modules to install.
   *
   * @var array
   */
  public static $modules = [
    'system',
    'serialization',
    'bibcite',
    'bibcite_entity',
    'bibcite_import',
    'bibcite_bibtex',
  ];

  /**
   * The list of import formats definitions.
   *
   * @var array
   */
  protected $bibciteImportFormats;

  /**
   * Serializer service.
   *
   * @var \Symfony\Component\Serializer\SerializerInterface
   */
  protected $serializer;

  /**
   * {@inheritdoc}
   */
  public function setUp() {
    parent::setUp();

    $this->bibciteImportFormats = $this->container->getParameter('bibcite_import_formats');
    $this->serializer = $this->container->get('serializer');
  }

  /**
   * Test if export formats available after enabling modules.
   *
   * @dataProvider importData
   */
  public function testAvailableFormats($format) {
    $this->assertTrue(isset($this->bibciteImportFormats[$format]));
  }

  /**
   * Test decode and denormalization from available text formats to entity.
   *
   * @dataProvider importData
   */
  public function testBibliographyDeserialization($format, $text, $entity_expected_values) {
    $entries = $this->serializer->decode($text, $format);

    foreach ($entries as $entry) {
      /** @var \Drupal\bibcite_entity\Entity\BibliographyInterface $entity */
      $entity = $this->serializer->denormalize($entry, Bibliography::class, $format);
      $this->assertTrue($entity instanceof Bibliography);

      $this->assertEntityValues($entity, $entity_expected_values);
    }
  }

  /**
   * Check if values in the provided entity equal to expected values.
   *
   * @param \Drupal\Core\Entity\EntityInterface $entity
   *   The entity object.
   * @param array $expected_values
   *   List of expected values.
   */
  protected function assertEntityValues(EntityInterface $entity, array $expected_values) {
    foreach ($expected_values as $field_name => $expected_value) {
      $this->assertEquals($expected_value, $entity->{$field_name}->value);
    }
  }

  /**
   * Get test data from YAML.
   *
   * @return array
   *   Data for import test.
   */
  public function importData() {
    $yaml_text = file_get_contents(__DIR__ . '/data/ImportBasicTest.data.yml');
    return Yaml::parse($yaml_text);
  }

}
