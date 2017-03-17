<?php

namespace Drupal\bibcite_entity;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityManagerInterface;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Entity\EntityViewBuilder;
use Drupal\Core\Language\LanguageManagerInterface;
use Drupal\Core\Theme\Registry;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

/**
 * Reference entity view builder.
 */
class ReferenceViewBuilder extends EntityViewBuilder {

  /**
   * Serializer service.
   *
   * @var \Symfony\Component\Serializer\Normalizer\NormalizerInterface
   */
  protected $serializer;

  /**
   * Config factory service.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configFactory;

  /**
   * {@inheritdoc}
   */
  public function __construct(EntityTypeInterface $entity_type, NormalizerInterface $serializer, ConfigFactoryInterface $config_factory, EntityManagerInterface $entity_manager, LanguageManagerInterface $language_manager, Registry $theme_registry = NULL) {
    parent::__construct($entity_type, $entity_manager, $language_manager, $theme_registry);

    $this->serializer = $serializer;
    $this->configFactory = $config_factory;
  }

  /**
   * {@inheritdoc}
   */
  public static function createInstance(ContainerInterface $container, EntityTypeInterface $entity_type) {
    return new static(
      $entity_type,
      $container->get('serializer'),
      $container->get('config.factory'),
      $container->get('entity.manager'),
      $container->get('language_manager'),
      $container->get('theme.registry')
    );
  }

  /**
   * {@inheritdoc}
   */
  protected function getBuildDefaults(EntityInterface $entity, $view_mode) {
    $build = parent::getBuildDefaults($entity, $view_mode);

    switch ($view_mode) {
      case 'default':
      case 'full':
        $config = $this->configFactory->get('bibcite_entity.reference.settings');
        if ($config->get('display_override.enable_display_override')) {
          $build['#theme'] = 'bibcite_reference_table';
        }
        break;

      case 'citation':
        $build['#theme'] = 'bibcite_citation';
        $build['#data'] = $this->serializer->normalize($entity, 'csl');
        $build['#data']['#entity'] = $entity;
        break;
    }

    return $build;
  }

}
