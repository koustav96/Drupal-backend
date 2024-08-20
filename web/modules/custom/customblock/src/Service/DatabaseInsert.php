<?php

namespace Drupal\customblock\Service;

use Drupal\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Database\Connection;

/**
 * Service class to insert data into database
 */
class DatabaseInsert {

  /**
   * Database Connection object.
   * 
   * @var \Drupal\Core\Database\Connection
   */
  protected $database;

  /**
   * Constructor of the DatabaseInsert
   *
   * @param \Drupal\Core\Database\Connection $database
   */
  public function __construct(Connection $database) {
    $this->database = $database;
  }

  /**
   * Create function to get database service injected.
   *
   * @param ContainerInterface $container
   *   Container provides the database service.
   * 
   * @return void
   */
  public static function create(ContainerInterface $container) {
    return new static (
      $container->get('database')
    );
  }

  /**
   * Function to insert given values into the given database.
   *
   * @param string $database_name
   *   Name of the database.
   * @param string $name
   *   Name of the group field.
   * @param string $label_1
   *   Name of the 1st label.
   * @param integer $stat_1
   *   Statistical number of the 1st label.
   * @param string $label_2
   *   Name of the 2nd label.
   * @param integer $stat_2
   *   Statistical number of the 2nd label.
   * 
   * @return void
   */
  public function insertData(string $database_name, string $name, string $label_1, int $stat_1, string $label_2, int $stat_2) {
    $this->database->insert($database_name)->fields([
      'name' => $name,
      'label_1' => $label_1,
      'stat_1' => $stat_1,
      'label_2' => $label_2,
      'stat_2' => $stat_2,
    ])->execute();
  }
}
