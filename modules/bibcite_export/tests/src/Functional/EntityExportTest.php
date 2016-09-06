<?php

namespace Drupal\Tests\bibcite_export\FunctionalJavascript;

use Drupal\Tests\BrowserTestBase;
use Symfony\Component\Yaml\Yaml;

/**
 * Test for main export functions.
 *
 * @group bibcite
 */
class EntityExportTest extends BrowserTestBase {

  public static $modules = [
    'bibcite_export_test',
  ];

  /**
   * Test user.
   *
   * @var \Drupal\user\UserInterface
   */
  protected $user;

  /**
   * {@inheritdoc}
   */
  public function setUp() {
    parent::setUp();

    $this->user = $this->drupalCreateUser([
      'view bibliography entities',
      'access bibcite export',
    ]);
  }

  /**
   * Test export URL's.
   *
   * @dataProvider exportDataProvider
   */
  public function testExportUrl($id, $format, $expected_result) {
    $this->drupalLogin($this->user);

    $text = $this->drupalGet(sprintf('bibcite/export/%s/bibliography/%s', $format, $id));

    $this->assertEquals(trim($expected_result), trim($text));
  }

  /**
   * Test export links.
   *
   * @dataProvider exportDataProvider
   */
  public function testExportLinks($id, $format, $expected_result) {
    $this->drupalLogin($this->user);

    $this->drupalGet(sprintf('bibcite/bibliography/%s', $id));

    $page = $this->getSession()->getPage();

    $link = $page->findLink('BibTex');
    $link->click();

    $content = $page->getContent();
    $this->assertEquals(trim($expected_result), trim($content));
  }

  /**
   * Get test data from YAML.
   *
   * @return array
   *   Data for URL test.
   */
  public function exportDataProvider() {
    $yaml_text = file_get_contents(__DIR__ . '/data/testExportUrl.data.yml');
    return Yaml::parse($yaml_text);
  }

}
