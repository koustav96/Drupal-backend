<?php

namespace Drupal\events_dashboard\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Implements a form to fetch taxonomy term information.
 */
class TermInfoForm extends FormBase {

  /**
   * The entity type manager service.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * Constructs a TermInfoForm object.
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager service.
   */
  public function __construct(EntityTypeManagerInterface $entity_type_manager) {
    $this->entityTypeManager = $entity_type_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('entity_type.manager'),
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'term_info_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['term_name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Enter Taxonomy Term Name'),
      '#description' => $this->t('Enter the exact name of the taxonomy term.'),
      '#required' => TRUE,
    ];

    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Submit'),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $term_name = $form_state->getValue('term_name');

    // Entity query to find taxonomy terms by name.
    $term_query = $this->entityTypeManager->getStorage('taxonomy_term')->getQuery()
      ->condition('name', $term_name)
      // Explicitly disable access checking.
      ->accessCheck(FALSE)
      ->execute();

    if ($term_query) {
      $term_ids = array_values($term_query);
      $terms = $this->entityTypeManager->getStorage('taxonomy_term')->loadMultiple($term_ids);

      foreach ($terms as $term) {
        // Prints term id.
        $this->messenger()->addMessage($this->t('Term ID: @id', ['@id' => $term->id()]));
        // Prints term uuid.
        $this->messenger()->addMessage($this->t('Term UUID: @uuid', ['@uuid' => $term->uuid()]));

        // Load nodes associated with this term.
        $nids = $this->entityTypeManager->getStorage('node')->getQuery()
          ->condition('field_ref', $term->id())
          // Explicitly disable access checking.
          ->accessCheck(FALSE)
          ->execute();

          $nodes = $this->entityTypeManager->getStorage('node')->loadMultiple($nids);
          if (!empty($nodes)) {
            foreach ($nodes as $node) {
              if ($node instanceof \Drupal\node\NodeInterface) {
                // Prints node title.
                $this->messenger()->addMessage($this->t('Node Title: @title', ['@title' => $node->getTitle()]));
                // Prints node URL.
                $this->messenger()->addMessage($this->t('Node URL: @url', ['@url' => $node->toUrl()->toString()]));
              } else {
                // Checks if only node is returned.
                $this->messenger()->addError($this->t('The entity is not a valid node.'));
              }
            }
          } else {
            // If there are no nodes associated with the term.
            $this->messenger()->addMessage($this->t('No node available with this term.'));
          }
        }          
    } else {
      // If there are no term associated with the name.
      $this->messenger()->addError($this->t('No term found with the name %name', ['%name' => $term_name]));
    }
  }
}
