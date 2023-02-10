<?php

namespace Drupal\farm_nrcs_quick_pcs\Plugin\QuickForm;

use Drupal\Component\Datetime\TimeInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Messenger\MessengerInterface;
use Drupal\farm_quick\Plugin\QuickForm\QuickFormBase;
use Drupal\farm_quick\Traits\QuickLogTrait;
use Psr\Container\ContainerInterface;

/**
 * Pasture condition score quick form.
 *
 * @QuickForm(
 *   id = "nrcs_pcs",
 *   label = @Translation("Pasture condition score"),
 *   description = @Translation("Record an observerd pasture condition score."),
 *   helpText = @Translation("Use this form to record a pasture condition score as an observation."),
 *   permissions = {
 *     "create observation log",
 *   }
 * )
 */
class PCS extends QuickFormBase {

  use QuickLogTrait;

  /**
   * The time service.
   *
   * @var \Drupal\Component\Datetime\TimeInterface
   */
  protected $time;

  /**
   * Constructs a QuickFormBase object.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\Core\Messenger\MessengerInterface $messenger
   *   The messenger service.
   * @param \Drupal\Component\Datetime\TimeInterface $time
   *   The time service.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, MessengerInterface $messenger, TimeInterface $time) {
    parent::__construct($configuration, $plugin_id, $plugin_definition, $messenger);
    $this->messenger = $messenger;
    $this->time = $time;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('messenger'),
      $container->get('datetime.time'),
    );
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, string $id = NULL) {

    // Pasture asset reference.
    $form['asset'] = [
      '#type' => 'entity_autocomplete',
      '#title' => $this->t('Pasture'),
      '#description' => $this->t('Which pasture is this an observation of?'),
      '#target_type' => 'asset',
      '#selection_handler' => 'views',
      '#selection_settings' => [
        'view' => [
          'view_name' => 'farm_location_reference',
          'display_name' => 'entity_reference',
          'arguments' => [],
        ],
        'match_operator' => 'CONTAINS',
      ],
      '#required' => TRUE,
    ];

    // Date of observation.
    $form['date'] = [
      '#type' => 'date',
      '#title' => $this->t('Date'),
      '#default_value' => date('Y-m-d', $this->time->getRequestTime()),
      '#required' => TRUE,
    ];

    // Create a score field for each indicator.
    $indicators = [
      'desirable_plants' => [
        'title' => 'Percent Desirable Plants',
      ],
      'percent_legume' => [
        'title' => 'Percent Legume by Dry Weight',
      ],
      'live_plant_cover' => [
        'title' => 'Live (includes dormant) Plant Cover',
      ],
      'plant_diversity' => [
        'title' => 'Plant Diversity by Dry Weight',
      ],
      'plant_residue' => [
        'title' => 'Plant Residue and Litter as Soil Cover',
      ],
      'grazing_utilization' => [
        'title' => 'Grazing Utilization and Severity',
      ],
      'livestock_concentration' => [
        'title' => 'Livestock Concentration Areas',
      ],
      'soil_compaction' => [
        'title' => 'Soil Compaction and Soil Regenerative Features',
      ],
      'plant_vigor' => [
        'title' => 'Plant Vigor',
      ],
      'erosion' => [
        'title' => 'Erosion',
      ],
    ];
    $score_options = range(1, 5);
    $form['indicators'] = ['#tree' => TRUE];
    foreach ($indicators as $name => $indicator) {
      $form['indicators'][$name] = [
        '#type' => 'select',
        '#title' => $indicator['title'],
        '#options' => array_combine($score_options, $score_options),
        '#required' => TRUE,
        '#ajax' => [
          'callback' => [$this, 'overallScoreCallback'],
          'wrapper' => 'pcs-score',
        ],
      ];
    }

    // Summarize the overall pasture condition score when indicators are
    // updated.
    $values = $form_state->getValue('indicators');
    if (!empty($values)) {
      $score = 0;
      foreach ($values as $value) {
        if (!empty($value)) {
          $score += $value;
        }
      }
    }
    else {
      $score = 'N/A';
    }
    $form['score'] = [
      '#tree' => TRUE,
      '#prefix' => '<div id="pcs-score">',
      '#suffix' => '</div>',
    ];
    $form['score']['value'] = [
      '#type' => 'value',
      '#value' => $score,
    ];
    $form['score']['markup'] = [
      '#type' => 'markup',
      '#markup' => '<strong>' . $this->t('Overall Pasture Condition Score') . ': ' . $score . '</strong>',
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

    // Get the overall score value.
    $score = $form_state->getValue(['score', 'value']);

    // Draft an observation log.
    $log = [
      'name' => $this->t('Pasture condition score'),
      'type' => 'observation',
      'timestamp' => strtotime($form_state->getValue('date')),
      'asset' => $form_state->getValue('asset'),
      'quantity' => [
        [
          'measure' => 'rating',
          'value' => $score,
          'label' => 'Overall Score',
        ],
      ],
      'status' => 'done',
    ];

    // Add quantity measurements for each indicator.
    $indicator_values = $form_state->getValue('indicators');
    foreach ($indicator_values as $name => $value) {
      $log['quantity'][] = [
        'measure' => 'rating',
        'value' => $value,
        'label' => $form['indicators'][$name]['#title'],
      ];
    }

    // Create the log.
    $this->createLog($log);
  }

  /**
   * Ajax callback for the overall pasture condition score.
   */
  public function overallScoreCallback(array $form, FormStateInterface $form_state) {
    return $form['score'];
  }

}
