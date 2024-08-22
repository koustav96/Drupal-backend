<?php

declare(strict_types=1);

namespace Drupal\rgb_field\Plugin\Field\FieldWidget;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\WidgetBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Defines the 'rgb_field_rgb_field' field widget.
 *
 * @FieldWidget(
 *   id = "rgb_field_rgb_field",
 *   label = @Translation("rgb_field"),
 *   field_types = {"rgb_field_rgb_field"},
 * )
 */
final class RgbFieldWidget extends WidgetBase {

  /**
   * {@inheritdoc}
   */
  public function formElement(FieldItemListInterface $items, $delta, array $element,
  array &$form, FormStateInterface $form_state): array {
    // Get the RGB values from the items.
    $red = $items[$delta]->red ?? NULL;
    $green = $items[$delta]->green ?? NULL;
    $blue = $items[$delta]->blue ?? NULL;
    
    // Add the rgb field to the form.
    $element['red'] = [
      '#type' => 'number',
      '#title' => $this->t('Red'),
      '#default_value' => $red,
    ];
    $element['green'] =  [
      '#type' => 'number',
      '#title' => $this->t('Green'),
      '#default_value' => $green,
    ];
    $element['blue'] = [
      '#type' => 'number',
      '#title' => $this->t('Blue'),
      '#default_value' => $blue,
    ];
    return $element;
  }
}
