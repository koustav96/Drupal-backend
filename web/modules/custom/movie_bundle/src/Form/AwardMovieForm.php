<?php

declare(strict_types=1);

namespace Drupal\movie_bundle\Form;

use Drupal\Core\Entity\EntityForm;
use Drupal\Core\Form\FormStateInterface;
use Drupal\movie_bundle\Entity\AwardMovie;

/**
 * Award Movie form.
 */
final class AwardMovieForm extends EntityForm {

  /**
   * {@inheritdoc}
   */
  public function form(array $form, FormStateInterface $form_state): array {
    /** @var \Drupal\movie_bundle\Entity\AwardMovie $award_movie */
    $award_movie = $this->entity;
    $form['label'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Label'),
      '#default_value' => $award_movie->label(),
      '#required' => TRUE,
    ];

    $form['year'] = [
      '#type' => 'number',
      '#title' => $this->t('Year'),
      '#default_value' => $award_movie->getYear(),
      '#required' => TRUE,
    ];

    $form['id'] = [
      '#type' => 'machine_name',
      '#default_value' => $award_movie->id(),
      '#machine_name' => [
        'exists' => [$this, 'exist'],
      ],
      '#disabled' => !$this->entity->isNew(),
    ];

    $form['movie_name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Movie Name'),
      '#default_value' => $award_movie->getMovieName(),
      '#required' => TRUE,
    ];

    return parent::form($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state): int {
    $result = parent::save($form, $form_state);
    $message_args = ['%label' => $this->entity->label()];
    $this->messenger()->addStatus(
      match($result) {
        \SAVED_NEW => $this->t('Created new example %label.', $message_args),
        \SAVED_UPDATED => $this->t('Updated example %label.', $message_args),
      }
    );
    $form_state->setRedirectUrl($this->entity->toUrl('collection'));
    return $result;
  }

  /**
   * Helper function to check whether an Example configuration entity exists.
   */
  public function exist($id) {
    $entity = $this->entityTypeManager->getStorage('award_movie')->getQuery()
      ->condition('id', $id)
      ->execute();
    return (bool) $entity;
  }
}
