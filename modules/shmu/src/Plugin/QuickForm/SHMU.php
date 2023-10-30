<?php

namespace Drupal\farm_nrcs_shmu\Plugin\QuickForm;

use Drupal\Core\Datetime\DrupalDateTime;
use Drupal\Core\Form\FormStateInterface;
use Drupal\farm_quick\Plugin\QuickForm\QuickFormBase;

/**
 * SHMU quick form.
 *
 * @QuickForm(
 *   id = "nrcs_shmu",
 *   label = @Translation("NRCS Soil Health Management Unit"),
 *   description = @Translation("Create an asset for representing an NRCS Soil Health Management Unit (SHMU)."),
 *   helpText = @Translation("Use this form to create a Soil Health Management Unit (SHMU) for use with an NRCS research project."),
 *   permissions = {
 *     "create shmu asset",
 *   }
 * )
 */
class SHMU extends QuickFormBase {

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    // Create vertical tabs.
    $form['tabs'] = [
      '#type' => 'vertical_tabs',
    ];

    // General SHMU information tab.
    $form['general'] = [
      '#type' => 'details',
      '#title' => $this->t('General'),
      '#group' => 'tabs',
    ];

    // SHMU name.
    $form['general']['name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Soil health management unit (SHMU) name'),
      '#required' => TRUE,
    ];

    // Land owner.
    $form['general']['owner'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Land owner'),
      '#required' => TRUE,
    ];

    // Soil health practices.
    $form['general']['soil_health_practices'] = [
      '#type' => 'select',
      '#title' => $this->t('Soil health practices'),
      '#options' => [
        0 => 'No Soil Health Practice(s) Applied',
        1 => 'One Soil health Practice Applied',
        2 => 'More than One Soil Health Practice Applied',
      ],
    ];

    // Geometry.
    $form['general']['geometry'] = [
      '#type' => 'farm_map_input',
      '#title' => $this->t('Geometry'),
      '#map_settings' => [
        'behaviors' => [
          'nrcs_soil_survey' => [
            'visible' => TRUE,
          ],
        ],
      ],
      '#display_raw_geometry' => TRUE,
      '#required' => TRUE,
    ];

    // PLU IDs
    // @todo make this add another
    $form['general']['plu'] = [
      '#type' => 'textfield',
      '#title' => $this->t('PLU IDs'),
      '#required' => TRUE,
    ];

    // Soil information tab.
    $form['soil'] = [
      '#type' => 'details',
      '#title' => $this->t('Soil information'),
      '#group' => 'tabs',
    ];

    // Soil texture.
    $form['soil']['soil_texture'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Soil texture'),
    ];

    // Soil suborder.
    $form['soil']['soil_suborder'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Soil suborder'),
    ];

    // @todo replace with Ecological Site IDs? can derive MLRA and Ecological Site Group from IDs
    // @todo make this add another
    $form['soil']['mlra'] = [
      '#type' => 'textarea',
      '#title' => $this->t('MLRA'),
    ];

    // Soil interpretions.
    $soil_interpretations = [
      '@todo',
    ];
    $form['soil']['soil_interpretations'] = [
      '#type' => 'checkboxes',
      '#title' => $this->t('Soil interpretations'),
      '#options' => array_combine($soil_interpretations, $soil_interpretations),
      '#multiple' => TRUE,
      '#required' => TRUE,
    ];

    // Soil drainage classes.
    $soil_drainage_classes = [
      'Very Poorly Drained',
      'Poorly Drained',
      'Somewhat Poorly Drained',
      'Moderately Well Drained',
      'Well Drained',
      'Somewhat Excessively Drained',
      'Excessively Drained',
      'Saturated Muck',
      'Well Drained Muck',
      'Unknown',
    ];
    $form['soil']['soil_drainage_classes'] = [
      '#type' => 'checkboxes',
      '#title' => $this->t('Soil drainage classes'),
      '#options' => array_combine($soil_drainage_classes, $soil_drainage_classes),
      '#multiple' => TRUE,
      '#required' => TRUE,
    ];

    // Hydrologic groups.
    $hydrologic_groups = [
      'Group A',
      'Group B',
      'Group C',
      'Group D',
      'Group A/D',
      'Group B/D',
      'Group C/D',
    ];
    $form['soil']['hydrologic_groups'] = [
      '#type' => 'checkboxes',
      '#title' => $this->t('Hydrologic groups'),
      '#options' => array_combine($hydrologic_groups, $hydrologic_groups),
      '#multiple' => TRUE,
      '#required' => TRUE,
    ];

    $form['soil']['ecological_site_ids'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Ecological site IDs'),
    ];

    // Site limitations tab.
    $form['site_limitations'] = [
      '#type' => 'details',
      '#title' => $this->t('Site limitations'),
      '#group' => 'tabs',
    ];
    $site_limitations = [
      'None',
      'Poor Drainage',
      'Poor Organic matter',
      '@todo',
    ];
    $form['site_limitations']['site_limitations_addressed'] = [
      '#type' => 'checkboxes',
      '#title' => $this->t('Site limitations addressed'),
      '#options' => array_combine($site_limitations, $site_limitations),
      '#multiple' => TRUE,
      '#required' => TRUE,
    ];
    // @todo make this add another
    $form['site_limitations']['site_limitations_addressed_other'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Site limitations addressed (other)'),
    ];
    $form['site_limitations']['site_limitations_not_addressed'] = [
      '#type' => 'checkboxes',
      '#title' => $this->t('Site limitations not addressed'),
      '#options' => array_combine($site_limitations, $site_limitations),
      '#multiple' => TRUE,
      '#required' => TRUE,
    ];
    // @todo make this add another
    $form['site_limitations']['site_limitations_not_addressed_other'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Site limitations not addressed (other)'),
    ];

    // Resource concerns tab.
    $form['resource_concerns'] = [
      '#type' => 'details',
      '#title' => $this->t('Resource concerns'),
      '#group' => 'tabs',
    ];

    // @todo dependent dropdowns
    // Resource concern category (aka SWAPAHE)
    // Conservation practice asset (aka a thing that is addressing a resource concern)
    // Asset purpose (aka narrative)
    // Resource concern
    // Resource concern component
    ///... Q: is this the right order? we want to limit lists at each level
    ///... Q: what data ultimately needs to be stored?

    // Resource concern areas.
    $resource_concern_areas = [
      'Soil',
      'Water',
      'Air',
      'Plants',
      'Animal',
      'Human',
      'Energy',
    ];
    $form['resource_concerns']['resource_concern_area'] = [
      '#type' => 'checkboxes',
      '#title' => $this->t('Resource concern area'),
      '#options' => array_combine($resource_concern_areas, $resource_concern_areas),
      '#multiple' => TRUE,
      '#required' => TRUE,
    ];

    // Resource concerns.
    $resource_concerns = [
      'None',
      'Aggregate Stability',
      '@todo',
    ];
    $form['resource_concerns']['resource_concerns'] = [
      '#type' => 'checkboxes',
      '#title' => $this->t('Resource concerns'),
      '#options' => array_combine($resource_concerns, $resource_concerns),
      '#multiple' => TRUE,
      '#required' => TRUE,
    ];

    // Land use tab.
    $form['land_use'] = [
      '#type' => 'details',
      '#title' => $this->t('Land use'),
      '#group' => 'tabs',
    ];

    // Land uses.
    $land_uses = [
      'Crop',
      'Forest',
      'Range',
      'Pasture',
      'Farmstead',
      'Developed Land',
      'Water',
      'Associated Agriculture Lands',
    ];
    $form['land_use']['land_use'] = [
      '#type' => 'select',
      '#title' => $this->t('Land use'),
      '#options' => array_combine($land_uses, $land_uses),
      '#required' => TRUE,
    ];

    // Land use modifiers.
    $land_use_modifiers = [
      'None',
      'Drained',
      'Grazed',
      'Hayed',
      'Irrigated',
      'Organic',
      'Other',
      'Protected',
      'Water Feature',
      'Wildlife',
    ];
    $form['land_use']['land_use_modifiers'] = [
      '#type' => 'checkboxes',
      '#title' => $this->t('Land use modifiers'),
      '#options' => array_combine($land_use_modifiers, $land_use_modifiers),
      '#multiple' => TRUE,
      '#required' => TRUE,
    ];

    $form['land_use']['precision'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Is precision agriculture equipment and variable rate application used?'),
    ];

    // Livestock raised on land.
    $form['land_use']['livestock'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Livestock raised on this land'),
    ];

    // Crop rotation history.
    $form['land_use']['crop_history'] = [
      '#type' => 'details',
      '#title' => $this->t('Crop history'),
      '#description' => $this->t('Describe the past five years of crop rotation history. First specify the year that this history starts, then click "Add crop" button to describe the rotation pattern.'),
      '#open' => TRUE,
    ];
    $five_years_ago = new DrupalDateTime('5 years ago');
    $form['land_use']['crop_history']['crop_rotation_start_year'] = [
      '#type' => 'number',
      '#title' => $this->t('Starting year'),
      '#min' => $five_years_ago->format('Y') - 5,
      '#default_value' => $five_years_ago->format('Y'),
    ];
    $form['land_use']['crop_history']['crop_rotation_pattern'] = [
      '#type' => 'crop_rotation_pattern',
      '#title' => $this->t('Crop rotation history'),
      '#title_display' => 'invisible',
    ];

    // Inputs.
    $form['land_use']['inputs'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Inputs'),
    ];

    // Current tillage.
    $form['land_use']['tillage_current'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Current tillage'),
    ];

    // Previous tillage.
    $form['land_use']['tillage_previous'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Previous tillage practice'),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

  }

}
