<?php

namespace Drupal\bibcite_entity\Plugin\Field\FieldWidget;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\Plugin\Field\FieldWidget\EntityReferenceAutocompleteWidget;
use Drupal\Core\Form\FormStateInterface;

/**
 * Plugin implementation of the 'bibcite_contributor_widget' widget.
 *
 * @FieldWidget(
 *   id = "bibcite_contributor_widget",
 *   label = @Translation("Contributor widget"),
 *   field_types = {
 *     "bibcite_contributor"
 *   }
 * )
 */
class ContributorWidget extends EntityReferenceAutocompleteWidget {

  /**
   * {@inheritdoc}
   */
  public function form(FieldItemListInterface $items, array &$form, FormStateInterface $form_state, $get_delta = NULL) {
    // @todo Render form as draggable table.
    return parent::form($items, $form, $form_state, $get_delta);
  }

  /**
   * {@inheritdoc}
   */
  public function formElement(FieldItemListInterface $items, $delta, array $element, array &$form, FormStateInterface $form_state) {
    $element += parent::formElement($items, $delta, $element, $form, $form_state);

    $element['category'] = [
      '#type' => 'select',
      '#title' => $this->t('Category'),
      '#default_value' => isset($items[$delta]->category) ? $items[$delta]->category : NULL,
      '#maxlength' => $this->getFieldSetting('max_length'),
      '#weight' => $delta,
      '#options' => [
        'primary' => 'Primary',
        'secondary' => 'Secondary',
        'tertiary' => 'Tertiary',
        'subsidiary' => 'Subsidiary',
        'corporate' => 'Corporate/Institutional',
      ],
    ];

    $element['role'] = [
      '#type' => 'select',
      '#title' => $this->t('Role'),
      '#default_value' => isset($items[$delta]->role) ? $items[$delta]->role : NULL,
      '#maxlength' => $this->getFieldSetting('max_length'),
      '#weight' => $delta,
      '#options' => [
        '1' => 'Author',
        '2' => 'Secondary Author',
        '3' => 'Tertiary Author',
        '4' => 'Subsidiary Author',
        '5' => 'Corporate Author',
        '10' => 'Series Editor',
        '11' => 'Performers',
        '12' => 'Sponsor',
        '13' => 'Translator',
        '14' => 'Editor',
        '15' => 'Counsel',
        '16' => 'Series Director',
        '17' => 'Producer',
        '18' => 'Department',
        '19' => 'Issuing Organization',
        '20' => 'International Author',
        '21' => 'Recipient',
        '22' => 'Advisor',
      ],
    ];

    $element['#type'] = 'container';
    $element['#attributes']['class'][] = 'container-inline';

    return $element;
  }

}
