<?php


namespace Drupal\Tests\bibcite_entity\Kernel;


use Drupal\bibcite\Plugin\BibCiteProcessor\CiteprocPhp;
use Drupal\bibcite_entity\Entity\Bibliography;
use Drupal\KernelTests\KernelTestBase;
use Symfony\Component\Yaml\Yaml;

/**
 * Test rendering of entity to citation.
 *
 * @group bibcite
 */
class EntityCitationRenderTest extends KernelTestBase {

  public static $modules = [
    'system',
    'serialization',
    'bibcite',
    'bibcite_entity',
  ];

  /**
   * Styler service.
   *
   * @var \Drupal\bibcite\StylerInterface
   */
  protected $styler;

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

    $this->styler = $this->container->get('bibcite.styler');
    $this->styler->setProcessor(new CiteprocPhp([], 'citeproc-php', [], $this->container->get('config.factory')));

    $this->serializer = $this->container->get('serializer');
  }

  /**
   * Test rendering Bibliography entity to citation.
   *
   * @dataProvider providerBibliographyEntity
   */
  public function testEntityRender($entity_values, $expected) {
    $entity = Bibliography::create($entity_values);

    $data = $this->serializer->normalize($entity, 'csl');
    $citation = $this->styler->render($data, 'apa');

    $this->assertEquals($expected, strip_tags($citation));
  }

  /**
   * Get test data from YAML.
   *
   * @return array
   *   Data for test.
   */
  public function providerBibliographyEntity() {
    $yaml_text = file_get_contents(__DIR__ . '/data/testEntityRender.data.yml');
    return Yaml::parse($yaml_text);
  }

}
