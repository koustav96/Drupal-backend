<?php

declare(strict_types=1);

namespace Drupal\movie_bundle\EventSubscriber;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * @todo Add description for this subscriber.
 */
final class MovieBundleSubscriber implements EventSubscriberInterface {

    /**
   * The configuration factory.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected ConfigFactoryInterface $configFactory;

  /**
   * The entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected EntityTypeManagerInterface $entityTypeManager;

  /**
   * Constructs a new MoviePriceComparisonSubscriber object.
   *
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The configuration factory.
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager.
   */
  public function __construct(ConfigFactoryInterface $config_factory, EntityTypeManagerInterface $entity_type_manager) {
    $this->configFactory = $config_factory;
    $this->entityTypeManager = $entity_type_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents(): array {
    return [
      KernelEvents::VIEW => ['onNodeView', 10],
    ];
  }

  public function onNodeView(ViewEvent $event): void {
    $route_match = \Drupal::routeMatch();
    $node = $route_match->getParameter('node');
    if ($node && $node->getType() === 'movie') {
      // Get the budget-friendly amount from the configuration form.
      $config = $this->configFactory->get('movie_bundle.settings');
      $budget = (float) $config->get('movie_budget');
      $movie_price = (float) $node->get('field_movie_price')->value;

      // Compare movie price with the configured budget and display messages.
      if ($movie_price > $budget) {
        \Drupal::messenger()->addMessage(t('The movie is over budget.'));
      }
      elseif ($movie_price < $budget) {
        \Drupal::messenger()->addMessage(t('The movie is under budget.'));
      }
      else {
        \Drupal::messenger()->addMessage(t('The movie is within budget.'));
      }
    }
  }
}
