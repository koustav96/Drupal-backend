<?php

declare(strict_types=1);

namespace Drupal\movie_bundle;

use Drupal\Core\Config\Entity\ConfigEntityInterface;

/**
 * Provides an interface defining an award movie entity type.
 */
interface AwardMovieInterface extends ConfigEntityInterface {
  
  /**
   * Gets the year of the award.
   *
   * @return int
   *   The year the movie won the award.
   */
  public function getYear();

  /**
   * Gets the movie name.
   *
   * @return string
   *   The name of the movie.
   */
  public function getMovieName();
}
