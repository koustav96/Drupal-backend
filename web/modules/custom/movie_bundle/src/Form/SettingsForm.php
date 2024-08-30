<?php

declare(strict_types=1);

namespace Drupal\movie_bundle\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Cache\Cache;

/**
 * Configure Movie Bundle settings for this site.
 */
final class SettingsForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId(): string {
    return 'movie_bundle_settings';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames(): array {
    return ['movie_bundle.settings'];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state): array {
    $form['movie_budget'] = [
      '#type' => 'number',
      '#title' => $this->t('Movie Budget'),
      '#default_value' => $this->config('movie_bundle.settings')->get('movie_budget'),
    ];
    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state): void {
    $this->config('movie_bundle.settings')
      ->set('movie_budget', $form_state->getValue('movie_budget'))
      ->save();
    parent::submitForm($form, $form_state);
    Cache::invalidateTags(['message']);
  }
}
