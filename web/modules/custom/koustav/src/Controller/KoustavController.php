<?php

namespace Drupal\koustav\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Session\AccountInterface;

/**
 * Class KoustavController for managing the functionality of the module.
 */
class KoustavController extends ControllerBase {
  
  /**
   * This variable holds the current user's session information.
   *
   * @var \Drupal\Core\Session\AccountInterface $account
   */
  protected $currUser;
  
  /**
   * Constructor of the Controller class.
   *
   * @param \Drupal\Core\Session\AccountInterface $account
   */
  public function __construct(AccountInterface $curr_user) {
    $this->currUser = $curr_user;
  }
  /**
   * Function to greet currently loggedin user.
   * 
   * @return array
   *  A renderable array containing the greeting message.
   */
  public function content() {
    $user = $this->currUser->getAccountName();
    return [
      '#markup' => $this->t('Hello, @username', ['@username' => $user]),
    ];
  }
}
