<?php

namespace Drupal\farm_nrcs_soil_health_test\Plugin\QuickForm;

use Drupal\Core\Form\FormStateInterface;
use Drupal\farm_quick_soil_test\Plugin\QuickForm\SoilTest;

/**
 * Soil health test quick form.
 *
 * @QuickForm(
 *   id = "cema216_soil_health_test",
 *   label = @Translation("CEMA 216 Soil Health Test"),
 *   description = @Translation("Create a lab test log for CEMA 216 Soil Health tests."),
 *   helpText = @Translation("Use this form to create a Soil Health Test log for CEMA 216."),
 *   permissions = {
 *     "create lab_test log",
 *   }
 * )
 */
class Cema216SoilHealthTest extends SoilTest {

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form = parent::buildForm($form, $form_state);

    // Make certain fields required.
    $form['lab']['#required'] = TRUE;
    $form['geometry']['#required'] = TRUE;
    $form['location']['#required'] = TRUE;

    // Require that a SHMU is referenced instead of a land asset.
    $form['location']['#title'] = $this->t('SHMU');
    $form['location']['#description'] = $this->t('Associate this lab test with a Soil Health Management Unit (SHMU).');
    $form['location']['#selection_settings']['target_bundles'] = ['shmu'];

    // Add required CEMA 216 results.
    $results = [
      // @todo add percent organic matter (?)

      'soil_organic_carbon' => 'Soil Organic Carbon',
      'aggregate_stability' => 'Wet Macro-Aggregate Stability',
      'respiration' => 'Respiration',
      'active_carbon' => 'Active Carbon',
      'ace_protein' => 'Bioavailable Nitrogen using ACE Protein',
      'microbial_diversity' => 'Microbial Diversity using PFLA',

      // @todo make comprehensive chemical analysis indicators optional
      'phosphorus' => 'Phosphorus',
      'potassium' => 'Potassium',
      'calcium' => 'Calcium',
      'magnesium' => 'Magnesium',
      'ph' => 'pH',
      'sulfur' => 'Sulfur',
      'iron' => 'Iron',
      'manganese' => 'Manganese',
      'copper' => 'Copper',
      'zinc' => 'Zinc',
      'boron' => 'Boron',
      'cec' => 'Cation exchange capacity',
      'total_nitrogen' => 'Total nitrogen',
    ];
    foreach ($results as $key => $label) {
      $form['results'][$key] = $this->buildInlineContainer();
      $form['results'][$key]['value'] = [
        '#type' => 'number',
        '#title' => $label,
        '#size' => 16,
        '#required' => TRUE,
      ];
      $form['results'][$key]['units'] = [
        '#type' => 'entity_autocomplete',
        '#title' => $this->t('Units'),
        '#target_type' => 'taxonomy_term',
        '#selection_settings' => [
          'target_bundles' => ['units'],
        ],
        '#autocreate' => [
          'bundle' => 'units',
        ],
        '#size' => 16,
        '#required' => TRUE,
      ];
      $form['results'][$key]['test_method'] = [
        '#type' => 'entity_autocomplete',
        '#title' => $this->t('Test method'),
        '#target_type' => 'taxonomy_term',
        '#selection_settings' => [
          'target_bundles' => ['lab'],
        ],
        '#autocreate' => [
          'bundle' => 'lab',
        ],
        '#size' => 16,
        '#required' => TRUE,
      ];
    }

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {

    // @todo ensure there are 15 points
  }

}
