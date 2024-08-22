<?php

declare(strict_types=1);

namespace Drupal\rgb_field\Plugin\Field\FieldWidget;

use Drupal\Component\Utility\Color;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\WidgetBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Defines the 'customfield_hexcolor' field widget.
 *
 * @FieldWidget(
 *   id = "customfield_hexcolor",
 *   label = @Translation("Hex color"),
 *   field_types = {"rgb_field_rgb_field"},
 * )
 */
final class HexWidget extends WidgetBase {

  /**
   * {@inheritdoc}
   */
  public function formElement(FieldItemListInterface $items, $delta,
   array $element, array &$form, FormStateInterface $form_state): array{
    // Get the RGB values from the items.
    $red = $items[$delta]->red ?? NULL;
    $green = $items[$delta]->green ?? NULL;
    $blue = $items[$delta]->blue ?? NULL;
    // Convert RGB values to hex.
    $hex = ($red !== NULL && $green !== NULL && $blue !== NULL) ? sprintf('#%02x%02x%02x', $red, $green, $blue) : '';
    // Add the hex field to the form.
    $element['hex'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Hex color'),
      '#default_value' => $hex,
      '#description' => $this->t('Enter the color in hex format (#RRGGBB).'),
      '#required' => TRUE,
    ];
    return $element;
  }

  /**
   * {@inheritdoc}
   */
  public function massageFormValues(array $values, array $form,
   FormStateInterface $form_state): array {
    foreach ($values as &$value) {
      // Validate and convert hex code to RGB using Drupal's Color utility.
      if (isset($value['hex']) && $this->validateHexCode($value['hex'])) {
        $rgb = Color::hexToRgb($value['hex']);
        $value['red'] = $rgb['red'] ?? 0;
        $value['green'] = $rgb['green'] ?? 0;
        $value['blue'] = $rgb['blue'] ?? 0;
      }
      else {
        // Handle invalid hex code if necessary.
        $value['red'] = $value['green'] = $value['blue'] = NULL;
      }
    }

    return $values;
  }

  /**
   * Function to validate Hex codes.
   *
   * @param string $hex
   *   Hex code.
   *
   * @return boolean
   */
  private function validateHexCode(string $hex): bool {
    return preg_match('/^#[0-9A-Fa-f]{6}$/', $hex) === 1;
  }
}
