<?php

namespace Drupal\my_route\Access;

use Drupal;
use Drupal\Core\Access\AccessResult;
use Symfony\Component\Routing\Route;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Routing\Access\AccessInterface;

/**
 * Custom access check service.
 */
class CustomAccessCheck implements AccessInterface {
 
  /**
   * Current User.
   *
   * @var \Drupal\Core\Session\AccountInterface
   */
  protected $currUser;

  /**
   * Constructor of CustomAccessCheck class.
   *
   * @param \Drupal\Core\Session\AccountInterface $curr_user
   *   Current User.
   */
  public function __construct(AccountInterface $curr_user) {
    $this->currUser = $curr_user;
  }

  /**
   * Checks access for the custom route.
   *
   * @return \Drupal\Core\Access\AccessResult
   *   The access result.
   */
  public function access() {
    $role = $this->currUser->getRoles();
    // Logic to detfine if the user can view the page.
    if ($this->currUser->hasPermission('access the custom page') && $role != 'content_editor') {
      return AccessResult::allowed();
    }
    // Deny access, if the user does not have the permission.
    return AccessResult::forbidden();
  }
}
