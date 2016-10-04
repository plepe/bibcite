<?php

namespace Drupal\bibcite_entity\Normalizer;


use Drupal\bibcite_entity\Entity\BibliographyInterface;
use Drupal\Core\Config\ConfigException;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Entity\EntityManagerInterface;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\serialization\Normalizer\EntityNormalizer;

/**
 * Base normalizer class for bibcite formats.
 */
abstract class BibliographyNormalizerBase extends EntityNormalizer {

  /**
   * The format that this Normalizer supports.
   *
   * @var string
   */
  protected $format;

  /**
   * Default publication type. Will be assigned for types without mapping.
   *
   * @var string
   */
  protected $defaultType;

  /**
   * Mapping between bibcite_entity and format publication types.
   *
   * @var array
   */
  protected $typesMapping;

  /**
   * Mapping between bibcite_entity and format fields.
   *
   * @var array
   */
  protected $fieldsMapping;

  /**
   * The interface or class that this Normalizer supports.
   *
   * @var array
   */
  protected $supportedInterfaceOrClass = ['Drupal\bibcite_entity\Entity\BibliographyInterface'];

  /**
   * Configuration factory service.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configFactory;

  /**
   * Construct new BiblioraphyNormalizer object.
   *
   * @param \Drupal\Core\Entity\EntityManagerInterface $entity_manager
   *   The entity manager.
   */
  public function __construct(EntityManagerInterface $entity_manager, ConfigFactoryInterface $config_factory) {
    parent::__construct($entity_manager);

    $this->configFactory = $config_factory;

    $config_name = sprintf('bibcite_entity_mapping.%s', $this->format);
    $config = $this->configFactory->get($config_name);

    $this->fieldsMapping = $config->get('fields');
    $this->typesMapping = $config->get('types');
  }

  /**
   * Convert publication type to format type.
   *
   * @param string $type
   *   Bibcite entity publication type.
   *
   * @return string
   *   Format publication type.
   */
  protected function convertEntityType($type) {
    $types_mapping = [];

    foreach ($this->typesMapping as $format_type => $entity_type) {
      if (empty($entity_type) || isset($mapping[$entity_type])) {
        continue;
      }

      $types_mapping[$entity_type] = $format_type;
    }

    return isset($types_mapping[$type]) ? $types_mapping[$type] : $this->defaultType;
  }

  /**
   * Convert format type to publication type.
   *
   * @param string $type
   *   Format publication type.
   *
   * @return string|null
   *   Bibcite entity publication type.
   */
  protected function convertFormatType($type) {
    return isset($this->typesMapping[$type]) ? $this->typesMapping[$type] : NULL;
  }

  /**
   * Extract fields values from bibliography entity.
   *
   * @param \Drupal\bibcite_entity\Entity\BibliographyInterface $bibliography
   *   Bibliography entity object.
   *
   * @return array
   *   Array of entity values.
   */
  protected function extractFields(BibliographyInterface $bibliography) {
    $attributes = [];

    foreach ($this->fieldsMapping as $format_field => $entity_field) {
      if ($entity_field && $bibliography->hasField($entity_field) && ($field = $bibliography->get($entity_field)) && !$field->isEmpty()) {
        $attributes[$format_field] = $this->extractScalar($field);
      }
    }

    return $attributes;
  }

  /**
   * Extract keywords labels from field.
   *
   * @param \Drupal\Core\Field\FieldItemListInterface $field_item_list
   *   List of field items.
   *
   * @return array
   *   Keywords labels.
   */
  protected function extractKeywords(FieldItemListInterface $field_item_list) {
    $keywords = [];

    foreach ($field_item_list as $field) {
      $keywords[] = $field->entity->label();
    }

    return $keywords;
  }

  /**
   * Extract authors values from field.
   *
   * @param \Drupal\Core\Field\FieldItemListInterface $field_item_list
   *   List of field items.
   *
   * @return array
   *   Authors in BibTex format.
   */
  protected function extractAuthors(FieldItemListInterface $field_item_list) {
    $authors = [];

    foreach ($field_item_list as $field) {
      $authors[] = $field->entity->getName();
    }

    return $authors;
  }

  /**
   * Convert author name string to Contributor object.
   *
   * @param string $author_name
   *   Raw author name string.
   *
   * @return \Drupal\bibcite_entity\Entity\ContributorInterface
   *   New contributor entity.
   */
  protected function prepareAuthor($author_name) {
    $contributor_storage = $this->entityManager->getStorage('bibcite_contributor');
    return $contributor_storage->create(['name' => trim($author_name)]);
  }

  /**
   * Convert keyword string to Keyword object.
   *
   * @param string $keyword
   *   Keyword string.
   *
   * @return \Drupal\bibcite_entity\Entity\KeywordInterface
   *   New keyword entity.
   */
  protected function prepareKeyword($keyword) {
    $storage = $this->entityManager->getStorage('bibcite_keyword');
    return $storage->create(['name' => trim($keyword)]);
  }

  /**
   * Checks if the provided format is supported by this normalizer.
   *
   * @param string $format
   *   The format to check.
   *
   * @return bool
   *   TRUE if the format is supported, FALSE otherwise. If no format is
   *   specified this will return FALSE.
   */
  protected function checkFormat($format = NULL) {
    return isset($format, $this->format) && $format == $this->format;
  }

  /**
   * Extract scalar value.
   *
   * @param \Drupal\Core\Field\FieldItemListInterface $scalar_field
   *   Scalar items list.
   *
   * @return mixed
   *   Scalar value.
   */
  protected function extractScalar(FieldItemListInterface $scalar_field) {
    return $scalar_field->value;
  }

}
