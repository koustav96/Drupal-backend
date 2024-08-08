<?php

namespace Drupal\my_route\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Session\AccountInterface;

/**
 * MyRouteController class to handle the module functionality. 
 */
class MyRouteController extends ControllerBase
{

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
   * Function to greet user.
   * 
   * @return void
   */
  public function content() {
    $uname = $this->currUser->getAccountName();
    return [
      '#markup' => $this->t('Hi @uname, What`s going on ?', ['@uname' => $uname]),
    ];
  }

  /**
   * Displays the dynamic id.
   *
   * @param int $id
   *   Dynamic path component present in page url.
   * 
   * @return void
   */
  public function dynamicContent(int $id) {
    return [
      '#markup' => $this->t('Hello user, Your Roll no is: @id', ['@id' => $id]),
    ];
  }
}
