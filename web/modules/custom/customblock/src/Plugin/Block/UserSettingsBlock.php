<?php

namespace Drupal\customblock\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Database\Connection;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a 'UserSettingsBlock' block.
 *
 * @Block(
 *   id = "form_data_block",
 *   admin_label = @Translation("User Settings Block"),
 * )
 */
class UserSettingsBlock extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * The database connection.
   *
   * @var \Drupal\Core\Database\Connection
   */
  protected $database;

  /**
   * Constructs a new UserSettingsBlock instance.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin ID for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\Core\Database\Connection $database
   *   The database connection.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, Connection $database) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->database = $database;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('database')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    // Query to get all the data from form_data table.
    $query = $this->database->select('form_data')
      ->fields('form_data', ['name', 'label_1', 'stat_1', 'label_2', 'stat_2']);
    $results = $query->execute()->fetchAll();

    // Prepare the data to be displayed.
    $output = [];
    foreach ($results as $record) {
      $output[] = [
        '#markup' => $this->t('Name: @name, Lebel 1: @label_1, Stat 1: @stat_1, Lebel 2: @label_2, Stat 2: @stat_2', [
          '@name' => $record->name,
          '@label_1,' => $record->label_1,
          '@stat_1' => $record->stat_1,
          '@label_2,' => $record->label_2,
          '@stat_2' => $record->stat_2,
        ]),
      ];
    }

    return [
      '#theme' => 'item_list',
      '#items' => $output,
      '#title' => $this->t('User Settings Data'),
    ];
  }
}
