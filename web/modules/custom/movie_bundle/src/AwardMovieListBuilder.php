<?php

declare(strict_types=1);

namespace Drupal\movie_bundle;

use Drupal\Core\Config\Entity\ConfigEntityListBuilder;
use Drupal\Core\Entity\EntityInterface;

/**
 * Provides a listing of award movies.
 */
final class AwardMovieListBuilder extends ConfigEntityListBuilder {

  /**
   * {@inheritdoc}
   */
  public function buildHeader(): array {
    $header['label'] = $this->t('Label');
    $header['movie_name'] = $this->t('Movie name');
    $header['year'] = $this->t('Year');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity): array {
    /** @var \Drupal\movie_bundle\AwardMovieInterface $entity */
    $row['label'] = $entity->label();
    $row['year'] = $entity->getYear();
    $row['movie_name'] = $entity->getMovieName();
    return $row + parent::buildRow($entity);
  }
}
