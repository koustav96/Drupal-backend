<?php

declare(strict_types=1);

namespace Drupal\customblock\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a customblock block.
 *
 * @Block(
 *   id = "customblock_customblock",
 *   admin_label = @Translation("customblock"),
 *   category = @Translation("Custom"),
 * )
 */
final class Customblock extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * Contains object of current user.
   *
   * @var \Drupal\Core\Session\AccountInterface
   */
  protected $currentUser;

  /**
   * Constructs a Drupalist object.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\Core\Session\AccountInterface $current_user
   *   The current_user.
   */
  public function __construct(
    array $configuration,
    $plugin_id,
    $plugin_definition,
    AccountInterface $current_user,
  ) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->currentUser = $current_user;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('current_user')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function build(): array {
    // Array containing user roles.
    $roles = implode(', ', $this->currentUser->getRoles());
    return [
      '#type' => 'markup',
      '#markup' => $this->t('Welcome @role', ['@role' => $roles]),
      '#cache' => [
        'tags' => ['user:' . $this->currentUser->id()],
      ],
    ];
  }
}
