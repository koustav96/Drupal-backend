<?php

declare(strict_types=1);

namespace Drupal\rgb_field\Plugin\Field\FieldType;

use Drupal\Core\Field\FieldDefinitionInterface;
use Drupal\Core\Field\FieldItemBase;
use Drupal\Core\Field\FieldStorageDefinitionInterface;
use Drupal\Core\TypedData\DataDefinition;

/**
 * Defines the 'rgb_field_rgb_field' field type.
 *
 * @FieldType(
 *   id = "rgb_field_rgb_field",
 *   label = @Translation("rgb_field"),
 *   description = @Translation("Some description."),
 *   default_widget = "customfield_hexcolor",
 *   default_formatter = "rgb_field_rgb_field",
 * )
 */
final class RgbFieldItem extends FieldItemBase {

  /**
   * {@inheritdoc}
   */
  public static function propertyDefinitions(FieldStorageDefinitionInterface $field_definition): array {

    // @DCG
    // See /core/lib/Drupal/Core/TypedData/Plugin/DataType directory for
    // available data types.
    $properties['red'] = DataDefinition::create('integer')
      ->setLabel(t('Red'))
      ->addConstraint('Range', ['min' => 0, 'max' => 255])
      ->setRequired(TRUE);

    $properties['green'] = DataDefinition::create('integer')
      ->setLabel(t('Green'))
      ->addConstraint('Range', ['min' => 0, 'max' => 255])
      ->setRequired(TRUE);

    $properties['blue'] = DataDefinition::create('integer')
      ->setLabel(t('Blue'))
      ->addConstraint('Range', ['min' => 0, 'max' => 255])
      ->setRequired(TRUE);

    return $properties;
  }

  /**
   * {@inheritdoc}
   */
  public static function schema(FieldStorageDefinitionInterface $field_definition): array {

    $columns = [
      'red' => [
        'type' => 'int',
        'not null' => FALSE,
        'description' => 'Red color.',
        'min' => 0,
        'max' => 255
      ],
      'green' => [
        'type' => 'int',
        'not null' => FALSE,
        'description' => 'Green color.',
        'min' => 0,
        'max' => 255
      ],
      'blue' => [
        'type' => 'int',
        'not null' => FALSE,
        'description' => 'Blue color.',
        'min' => 0,
        'max' => 255
      ],
    ];

    $schema = [
      'columns' => $columns,
    ];

    return $schema;
  }

  /**
   * {@inheritdoc}
   */
  public static function generateSampleValue(FieldDefinitionInterface $field_definition): array {
    $values['red'] = mt_rand(0, 255);
    $values['green'] = mt_rand(0, 255);
    $values['blue'] = mt_rand(0, 255);
    return $values;
  }
}
