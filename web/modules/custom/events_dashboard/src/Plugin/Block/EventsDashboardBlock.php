<?php

namespace Drupal\events_dashboard\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\events_dashboard\Controller\DashboardController;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a block for the Events Dashboard.
 *
 * @Block(
 *   id = "events_dashboard_block",
 *   admin_label = @Translation("Events Dashboard Block"),
 * )
 */
class EventsDashboardBlock extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * The dashboard controller service.
   *
   * @var \Drupal\events_dashboard\Controller\DashboardController
   */
  protected $dashboardController;

  /**
   * Constructs a new EventsDashboardBlock instance.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin ID for the block.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\events_dashboard\Controller\DashboardController $dashboard_controller
   *   The dashboard controller service.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, DashboardController $dashboard_controller) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->dashboardController = $dashboard_controller;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('controller.dashboard')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    // Fetch the event statistics from the controller.
    $event_stats = $this->dashboardController->provideEventStats();

    // Prepare the output.
    $output = [];
    $output[] = $this->t('Yearly Event Counts:');
    foreach ($event_stats['yearly'] as $year => $count) {
      $output[] = $this->t('@year: @count events', ['@year' => $year, '@count' => $count]);
    }

    $output[] = $this->t('Quarterly Event Counts:');
    foreach ($event_stats['quarterly'] as $quarter => $count) {
      $output[] = $this->t('@quarter: @count events', ['@quarter' => $quarter, '@count' => $count]);
    }

    $output[] = $this->t('Event Type Counts:');
    foreach ($event_stats['type'] as $type => $count) {
      $output[] = $this->t('@type: @count events', ['@type' => $type, '@count' => $count]);
    }

    // Return the renderable array.
    return [
      '#theme' => 'item_list',
      '#items' => $output,
    ];
  }
}
 