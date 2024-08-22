<?php

declare(strict_types=1);

namespace Drupal\rgb_field\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FormatterBase;

/**
 * Plugin implementation of the 'rgb_field' formatter.
 *
 * @FieldFormatter(
 *   id = "rgb_field_rgb_field",
 *   label = @Translation("rgb_field"),
 *   field_types = {"rgb_field_rgb_field"},
 * )
 */
final class RgbFieldFormatter extends FormatterBase {

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode): array {
    $element = [];

    foreach ($items as $delta => $item) {
      // Get the RGB values
      $r = $item->red ?? 0;
      $g = $item->green ?? 0;
      $b = $item->blue ?? 0;

      // Create the RGB string
      $rgb_value = "rgb($r, $g, $b)";

      // Render the color box
      $element[$delta] = [
        '#markup' => t('<div style="height:200px;width:200px; background-color:@rgb_value;border-radius:50%;display:flex;align-items:center;justify-content:center;">@msg</div>', ['@rgb_value' => $rgb_value
      ,'@msg'=>'Hi There']),
      ];
    }
    return $element;
  }
}
