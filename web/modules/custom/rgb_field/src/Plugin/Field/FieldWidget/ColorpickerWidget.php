<?php

declare(strict_types=1);

namespace Drupal\rgb_field\Plugin\Field\FieldWidget;

use Drupal\Component\Utility\Color;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\WidgetBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Defines the 'customfield_colorpicker' field widget.
 *
 * @FieldWidget(
 *   id = "customfield_colorpicker",
 *   label = @Translation("colorpicker"),
 *   field_types = {"rgb_field_rgb_field"},
 * )
 */
final class ColorpickerWidget extends WidgetBase {

  public function formElement(FieldItemListInterface $items, $delta, array $element,
   array &$form,FormStateInterface $form_state): array{
    // Get the RGB values from the items.
    $red = $items[$delta]->red ?? 0;
    $green = $items[$delta]->green ?? 0;
    $blue = $items[$delta]->blue ?? 0;

    // Convert RGB values to hex.
    $hex = sprintf('#%02x%02x%02x', $red, $green, $blue);

    // Add the color picker field to the form.
    $element['color'] = [
      '#type' => 'color',
      '#title' => $this->t('Color Picker'),
      '#default_value' => $hex,
      '#description' => $this->t('Select a color using the color picker.'),
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
      if (isset($value['color']) && $this->validateHexCode($value['color'])) {
        $rgb = Color::hexToRgb($value['color']);
        $value['red'] = $rgb['red'] ?? 0;
        $value['green'] = $rgb['green'] ?? 0;
        $value['blue'] = $rgb['blue'] ?? 0;
      }
      else {
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
