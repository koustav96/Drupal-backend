<?php

namespace Drupal\customblock\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\customblock\Service\DatabaseInsert;

class SettingsForm extends FormBase {
  /**
   * A custom database inserter object.
   *
   * @var \Drupal\customblock\Service\DatabaseInsert
   */
  protected $data_inserter;

  /**
   * Constructs a new StatDisplayBlock.
   *
   * @param \Drupal\customblock\Service\DatabaseInsert $database
   *   The custom database inserter object.
   */
  public function __construct(DatabaseInsert $data_inserter) {
    $this->data_inserter = $data_inserter;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('customblock.database_insert')
    );
  }
  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'stat_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    // Initialize the field group array.
    if (empty($form_state->get('num_field_group'))) {
      $form_state->set('num_field_group', 1);
    }

    $num_field_group = $form_state->get('num_field_group');

    $form['field_group'] = [
      '#type' => 'container',
      '#prefix' => '<div id="field-group-wrapper">',
      '#suffix' => '</div>',
      '#tree' => TRUE,
    ];
    // Loop through the field groups.
    for ($i = 0; $i < $num_field_group; $i++) {
      $form['field_group'][$i] = [
        '#type' => 'fieldset',
        '#title' => $this->t('Field Group @number', ['@number' => $i + 1]),
      ];
      $form['field_group'][$i]['name'] = [
        '#type' => 'textfield',
        '#title'=> $this->t('Field-Group Name'),
      ];
      $form['field_group'][$i]['label_1'] = [
        '#type' => 'textfield',
        '#title' => $this->t('1st Label Title'),
      ];
      $form['field_group'][$i]['stat_1'] = [
        '#type' => 'number',
        '#title' => $this->t('1st Label Stats'),
      ];
      $form['field_group'][$i]['label_2'] = [
        '#type' => 'textfield',
        '#title' => $this->t('2nd Label Title'),
      ];
      $form['field_group'][$i]['stat_2'] = [
        '#type' => 'number',
        '#title' => $this->t('2nd Label Stats'),
      ];
    }
    $form['actions'] = [
      '#type' => 'actions',
    ];
    $form['actions']['add_more'] = [
      '#type' => 'submit',
      '#value' => $this->t('Add more'),
      '#submit' => ['::addMore'],
      '#ajax' => [
        'callback' => '::updateCallback',
        'wrapper' => 'field-group-wrapper',
      ],
    ];
    $form['actions']['remove'] = [
      '#type' => 'submit',
      '#value' => $this->t('Remove'),
      '#submit' => ['::removeCallback'],
      '#weight' => 50,
      '#ajax' => [
        'callback' => '::updateCallback',
        'wrapper' => 'field-group-wrapper',
      ],
    ];
    $form['actions']['submit']= [
      '#type' => 'submit',
      '#value' => $this->t('Submit'),
      '#weight' => 100,
    ];
    return $form;
  }

  /**
   * Custom submit handler for adding more fields.
   * 
   * @param  array $form
   *   An associative array containing the structure of the form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The current state of the form.
   * 
   * @return void
   */
  public function addMore(array &$form, FormStateInterface $form_state) {
    $num_field_group = $form_state->get('num_field_group');
    $form_state->set('num_field_group', $num_field_group + 1);
    $form_state->setRebuild();
  }

  /**
   * Custom submit handler for removing fields.
   * 
   * @param  array $form
   *   An associative array containing the structure of the form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The current state of the form.
   * 
   * @return void
   */
  public function removeCallback(array &$form, FormStateInterface $form_state) {
    $num_field_group = $form_state->get('num_field_group');
    if ($num_field_group > 1) {
      $form_state->set('num_field_group', $num_field_group - 1);
    }
    $form_state->setRebuild();
  }

  /**
   * AJAX callback to update the form.
   * 
   * @param  array $form
   *   An associative array containing the structure of the form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The current state of the form.
   * 
   * @return void
   */
  public function updateCallback(array &$form, FormStateInterface $form_state) {
    return $form['field_group'];
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    // Handle form submission.
    $values = $form_state->getValue('field_group');
    foreach ($values as $value) {
     $this->data_inserter->insertData('form_data', $value['name'], $value['label_1'], $value['stat_1'], $value['label_2'], $value['stat_2']);
    }
  }
}
