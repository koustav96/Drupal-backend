<?php

declare(strict_types=1);

namespace Drupal\customblock\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Returns responses for Customblock routes.
 */
final class CustomblockController extends ControllerBase {


  /**
   * Displays the content of the custom routes page.
   *
   * @return array
   *   Returns the content to be displayed.
   */
  public function content(): array {
    return [
      '#markup' => $this->t('This is custom welcome page.'),
    ];
  }
}
