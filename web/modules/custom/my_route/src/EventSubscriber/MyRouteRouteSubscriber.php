<?php

declare(strict_types=1);

namespace Drupal\my_route\EventSubscriber;

use Drupal\Core\Routing\RouteSubscriberBase;
use Symfony\Component\Routing\RouteCollection;

/**
 * Route subscriber.
 */
final class MyRouteRouteSubscriber extends RouteSubscriberBase {

  /**
   * {@inheritdoc}
   */
  public function alterRoutes(RouteCollection $collection) {
    if ($route = $collection->get('my_route.content')) {
      $route->setRequirement('_permission', 'access the custom page');
      $route->setRequirement('_role', 'administrator');
    }
  }
}
