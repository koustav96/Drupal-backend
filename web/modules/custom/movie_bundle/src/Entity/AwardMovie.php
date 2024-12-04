<?php

declare(strict_types=1);

namespace Drupal\movie_bundle\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBase;
use Drupal\movie_bundle\AwardMovieInterface;

/**
 * Defines the award movie entity type.
 *
 * @ConfigEntityType(
 *   id = "award_movie",
 *   label = @Translation("Award Movie"),
 *   label_collection = @Translation("Award Movies"),
 *   label_singular = @Translation("award movie"),
 *   label_plural = @Translation("award movies"),
 *   label_count = @PluralTranslation(
 *     singular = "@count award movie",
 *     plural = "@count award movies",
 *   ),
 *   handlers = {
 *     "list_builder" = "Drupal\movie_bundle\AwardMovieListBuilder",
 *     "form" = {
 *       "add" = "Drupal\movie_bundle\Form\AwardMovieForm",
 *       "edit" = "Drupal\movie_bundle\Form\AwardMovieForm",
 *       "delete" = "Drupal\Core\Entity\EntityDeleteForm",
 *     },
 *   },
 *   config_prefix = "award_movie",
 *   admin_permission = "administer award_movie",
 *   links = {
 *     "collection" = "/admin/structure/award-movie",
 *     "add-form" = "/admin/structure/award-movie/add",
 *     "edit-form" = "/admin/structure/award-movie/{award_movie}",
 *     "delete-form" = "/admin/structure/award-movie/{award_movie}/delete",
 *   },
 *   entity_keys = { 
 *     "id" = "id",
 *     "label" = "label",
 *     "year" = "year",
 *     "movie_name" = "movie_name",
 *   },
 *   config_export = {
 *     "id",
 *     "label",
 *     "year",
 *     "movie_name",
 *   },
 * )
 */
final class AwardMovie extends ConfigEntityBase implements AwardMovieInterface {

  /**
   * The AwardMovie ID.
   *
   * @var string
   */
  protected $id;

  /**
   * The AwardMovie label.
   *
   * @var string
   */
  protected $label;

  /**
   * The year the movie won the award.
   *
   * @var int
   */
  protected $year;

  /**
   * The name of the movie.
   *
   * @var string
   */
  protected $movie_name;

  /**
   * {@inheritdoc}
   */
  public function getYear() {
    return $this->year;
  }

  /**
   * {@inheritdoc}
   */
  public function getMovieName() {
    return $this->movie_name;
  }

  /**
   * {@inheritdoc}
   */
  public function setYear($year) {
    $this->year = $year;
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function setMovieName($movie_name) {
    $this->movie_name = $movie_name;
    return $this;
  }
}
