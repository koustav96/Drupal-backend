<?php

namespace Drupal\events_dashboard\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Database\Connection;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a controller for the Events Dashboard.
 */
class DashboardController extends ControllerBase {

  /**
   * The database connection service.
   *
   * @var \Drupal\Core\Database\Connection
   */
  protected $database;
 
  /**
   * Constructs a DashboardController object.
   *
   * @param \Drupal\Core\Database\Connection $database
   *   The database connection service.
   */
  public function __construct(Connection $database) {
    $this->database = $database;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('database')
    );
  }

  /**
   * Fetches event statistics from the database.
   *
   * @return array
   *   An associative array containing event statistics.
   */
  public function getEventStats() {
    // Get yearly event counts.
    $yearly_counts = $this->database->query("
      SELECT YEAR(DATE(d.field_date_value)) AS event_year, COUNT(*) AS count
      FROM {node__field_date} d
      INNER JOIN {node_field_data} n ON d.entity_id = n.nid
      WHERE n.type = :type
      GROUP BY event_year
      ORDER BY event_year DESC
    ", [':type' => 'events'])->fetchAllKeyed();

    // Get quarterly event counts.
    $quarterly_counts = $this->database->query("
      SELECT CONCAT(YEAR(DATE(d.field_date_value)), '-Q', QUARTER(DATE(d.field_date_value))) AS event_quarter, COUNT(*) AS count
      FROM {node__field_date} d
      INNER JOIN {node_field_data} n ON d.entity_id = n.nid
      WHERE n.type = :type
      GROUP BY event_quarter
      ORDER BY event_quarter DESC
    ", [':type' => 'events'])->fetchAllKeyed();

    // Get event type counts.
    $type_counts = $this->database->query("
      SELECT field_type_value, COUNT(*) AS count
      FROM {node__field_type}
      WHERE entity_id IN (
        SELECT nid FROM {node_field_data} WHERE type = :type
      )
      GROUP BY field_type_value
    ", [':type' => 'events'])->fetchAllKeyed();

    return [
      'yearly' => $yearly_counts,
      'quarterly' => $quarterly_counts,
      'type' => $type_counts,
    ];
  }

  /**
   * Exposes the event statistics to other classes.
   *
   * @return array
   *   The event statistics array.
   */
  public function provideEventStats() {
    return $this->getEventStats();
  }

    /**
   * Builds the term information page.
   *
   * @return array
   *   A render array representing the term information form.
   */
  public function termInfoPage() {
    $form = \Drupal::formBuilder()->getForm('Drupal\events_dashboard\Form\TermInfoForm');
    return $form;
  }
}
