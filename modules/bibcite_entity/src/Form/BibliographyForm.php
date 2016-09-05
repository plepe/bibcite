<?php

namespace Drupal\bibcite_entity\Form;

use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Render\Element;

/**
 * Form controller for Bibliography edit forms.
 *
 * @ingroup bibcite_entity
 */
class BibliographyForm extends ContentEntityForm {

  /**
   * {@inheritdoc}
   */
  public function form(array $form, FormStateInterface $form_state) {
    $form = parent::form($form, $form_state);

    $form['#process'][] = '::bibliographyRestructure';

    return $form;
  }

  /**
   * Restructure form elements to the vertical tabs view.
   *
   * @see \Drupal\bibcite_entity\Form\BibliographyForm::form()
   */
  public function bibliographyRestructure(array $element, FormStateInterface $form_state) {
    // @todo Move field groups to the configuration level.
    $field_groups = [
      'authors' => [
        'title' => $this->t('Authors'),
        'elements' => [
          'author',
        ],
      ],
      'abstract' => [
        'title' => $this->t('Abstract'),
        'elements' => [
          'bibcite_abst_e',
        ],
      ],
      'publication' => [
        'title' => $this->t('Publication'),
        'elements' => [
          'bibcite_year',
          'bibcite_secondary_title',
          'bibcite_volume',
          'bibcite_edition',
          'bibcite_section',
          'bibcite_issue',
          'bibcite_number_of_volumes',
          'bibcite_number',
          'bibcite_pages',
          'bibcite_date',
          'bibcite_type_of_work',
          'bibcite_lang',
          'bibcite_reprint_edition',
        ],
      ],
      'publisher' => [
        'title' => $this->t('Publisher'),
        'elements' => [
          'bibcite_publisher',
          'bibcite_place_published',
        ],
      ],
      'identifiers' => [
        'title' => $this->t('Identifiers'),
        'elements' => [
          'bibcite_issn',
          'bibcite_isbn',
          'bibcite_accession_number',
          'bibcite_call_number',
          'bibcite_other_number',
          'bibcite_citekey',
          'bibcite_pmid',
        ],
      ],
      'locators' => [
        'title' => $this->t('Locators'),
        'elements' => [
          'bibcite_url',
          'bibcite_doi',
        ],
      ],
      'keywords' => [
        'title' => $this->t('Keywords'),
        'elements' => [
          'keywords',
        ],
      ],
      'notes' => [
        'title' => $this->t('Notes'),
        'elements' => [
          'bibcite_notes',
          'bibcite_research_notes',
        ],
      ],
      'alternate_titles' => [
        'title' => $this->t('Alternative titles'),
        'elements' => [
          'bibcite_tertiary_title',
          'bibcite_short_title',
          'bibcite_alternate_title',
          'bibcite_translated_title',
          'bibcite_original_publication',
        ],
      ],
      'other' => [
        'title' => $this->t('Other'),
        'elements' => [
          'bibcite_other_author_affiliations',
          'bibcite_abst_f',
          'bibcite_custom1',
          'bibcite_custom2',
          'bibcite_custom3',
          'bibcite_custom4',
          'bibcite_custom5',
          'bibcite_custom6',
          'bibcite_custom7',
          'bibcite_remote_db_name',
          'bibcite_remote_db_provider',
          'bibcite_auth_address',
          'bibcite_label',
          'bibcite_access_date',
          'bibcite_refereed',
        ],
      ],
    ];

    $element['tabs'] = [
      '#type' => 'vertical_tabs',
      '#weight' => 3,
    ];

    foreach ($field_groups as $group_id => $group) {
      $element[$group_id] = [
        '#type' => 'details',
        '#title' => $group['title'],
        '#group' => 'tabs',
      ];

      foreach ($group['elements'] as $field_id) {
        if (isset($element[$field_id])) {
          $element[$field_id]['#group'] = $group_id;
        }
      }
    }

    return $element;
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $entity = $this->entity;
    $status = parent::save($form, $form_state);

    switch ($status) {
      case SAVED_NEW:
        drupal_set_message($this->t('Created the %label Bibliography.', [
          '%label' => $entity->label(),
        ]));
        break;

      default:
        drupal_set_message($this->t('Saved the %label Bibliography.', [
          '%label' => $entity->label(),
        ]));
    }
    $form_state->setRedirect('entity.bibliography.canonical', ['bibliography' => $entity->id()]);
  }

}
