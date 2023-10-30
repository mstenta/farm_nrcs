<?php

namespace Drupal\farm_nrcs_shmu\Plugin\Asset\AssetType;

use Drupal\entity\BundleFieldDefinition;
use Drupal\farm_entity\Plugin\Asset\AssetType\FarmAssetType;

/**
 * Provides the NRCS SHMU asset type.
 *
 * @AssetType(
 *   id = "nrcs_shmu",
 *   label = @Translation("Soil Health Management Unit (NRCS)"),
 * )
 */
class NrcsShmu extends FarmAssetType {

  /**
   * {@inheritdoc}
   */
  public function buildFieldDefinitions() {
    $fields = parent::buildFieldDefinitions();
    $field_info = [
      'nrcs_land_use' => [
        'type' => 'list_string',
        'label' => $this->t('Land Use (NRCS)'),
        'allowed_values' => [
          'crop' => t('Crop'),
          'forest' => t('Forest'),
          'range' => t('Range'),
          'pasture' => t('Pasture'),
          'farmstead' => t('Farmstead'),
          'developed_land' => t('Developed Land'),
          'water' => t('Water'),
          'associated_agriculture_land' => t('Associated Agriculture Lands'),
        ],
        'weight' => [
          'form' => 0,
          'view' => 0,
        ],
      ],
      'nrcs_soil_drainage_class' => [
        'type' => 'list_string',
        'label' => $this->t('Soil Drainage Class (NRCS)'),
        'allowed_values' => [
          'very_poorly_drained' => t('Very Poorly Drained'),
          'poorly_drained' => t('Poorly Drained'),
          'somewhat_poorly_drained' => t('Somewhat Poorly Drained'),
          'moderately_well_drained' => t('Moderately Well Drained'),
          'well_drained' => t('Well Drained'),
          'somewhat_excessively_drained' => t('Somewhat Excessively Drained'),
          'excessively_drained' => t('Excessively Drained'),
          'saturated_muck' => t('Saturated Muck'),
          'well_drained_muck' => t('Well Drained Muck '),
        ],
        'weight' => [
          'form' => 0,
          'view' => 0,
        ],
      ],
    ];
    foreach ($field_info as $name => $info) {
      $fields[$name] = \Drupal::service('farm_field.factory')->bundleFieldDefinition($info);
    }
    return $fields;
  }

}
