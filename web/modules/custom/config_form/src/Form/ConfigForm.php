<?php

namespace Drupal\config_form\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\HtmlCommand;

/**
 * Configure Custom config form settings for this site.
 */
final class ConfigForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId(): string {
    return 'config_form';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames(): array {
    return ['config_form.settings'];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state): array {
    $config = $this->config('config_form.settings');

    $form['full_name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Full Name'),
      '#suffix' => '<div class="error" id="name_err"></div>',
      '#default_value' => $config->get('full_name'),
      '#required' => TRUE,
      '#ajax' => [
        'callback' => '::nameCheckError',
        'event' => 'change'
      ]
    ];

    $form['phone_number'] = [
      '#type' => 'tel',
      '#title' => $this->t('Phone Number'),
      '#default_value' => $config->get('phone_number'),
      '#suffix' => '<div class="error" id="ph_err"></div>',
      '#attributes' => ['maxlength' => 10],
      '#required' => TRUE,
      '#ajax' => [
        'callback' => '::validatePhoneNumber',
        'event' => 'change',
      ], 
    ];

    $form['email'] = [
      '#type' => 'email',
      '#title' => $this->t('Email ID'),
      '#suffix' => '<div class="error" id="email_err"></div>',
      '#default_value' => $config->get('email'),
      '#required' => TRUE,
      '#ajax' => [
        'callback' => '::emailCheckError',
        'event' => 'change'
      ]
    ];

    $form['gender'] = [
      '#type' => 'radios',
      '#title' => $this->t('Gender'),
      '#options' => [
        'male' => $this->t('Male'),
        'female' => $this->t('Female'),
        'other' => $this->t('Prefer not to say'),
      ],
      '#default_value' => $config->get('gender'),
      '#required' => TRUE,
    ];
    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    $public_domains = ['yahoo.com', 'gmail.com', 'outlook.com', 'hotmail.com'];
    $email = $form_state->getValue('email');
    $domain = substr(strrchr($email, "@"), 1);
    $phone_number = $form_state->getValue('phone_number');
    $name = $form_state->getValue('full_name');

    if (!preg_match('/^\d{10}$/', $phone_number)) {
      $form_state->setErrorByName('phone_number', $this->t('Please enter valid phone number!'));
    }
    if (!filter_var($email,FILTER_VALIDATE_EMAIL)) {
      $form_state->setErrorByName('email', $this->t('Please enter valid Email Format!'));
    }
    elseif (!in_array($domain, $public_domains)){
      $form_state->setErrorByName('email', $this->t('The email must be from a public domain and must end with .com!'));
    }
    if (!preg_match('/^[a-z A-Z]+$/', $name)) {
      $form_state->setErrorByName('name', $this->t('Enter valid name!'));
    }
    parent::validateForm($form, $form_state);
  }

  /**
   * This Function checks if the Full name field contains only alphabets or not.
   * 
   * @param array $form
   *  An array contains render output of Form.
   * @param FormStateInterface $form_state
   *  An object which stores current state of Form.
   * 
   * @return object
   *  An object which stores the AjaxResponse object.
   */
  public function nameCheckError(array &$form, FormStateInterface $form_state) {
    $name = $form_state->getValue('full_name');
    $error = '';
    if (!preg_match('/^[a-z A-Z]+$/', $name)) {
      $error = 'Enter valid name.';
    }
    $ajaxResponse = new AjaxResponse();
    $ajaxResponse->addCommand(new HtmlCommand('#name_err', $error));
    return $ajaxResponse;
  }
  
  /**
   * This Function checks if the phone number is valid or not.
   * 
   * @param array $form
   *  An array contains render output of Form.
   * @param FormStateInterface $form_state
   *  An object which stores current state of Form.
   * 
   * @return object
   *  An object which stores the AjaxResponse object.
   */
  public function validatePhoneNumber(array &$form, FormStateInterface $form_state) {
    $phone_number = $form_state->getValue('phone_number');
    $error = '';
    if (!preg_match('/^\d{10}$/', $phone_number)) {
      $error = 'Please enter valid phone number.';
    }
    $ajaxResponse = new AjaxResponse();
    $ajaxResponse->addCommand(new HtmlCommand('#ph_err', $error));
    return $ajaxResponse;
  }
  
  /**
   * This Function checks if the Email field has any Error or not.
   * 
   * @param array $form
   *  An array contains render output of Form.
   * @param FormStateInterface $form_state
   *  An object which stores current state of Form.
   * 
   * @return object
   *  An object which stores the AjaxResponse object.
   */
  public function emailCheckError(array &$form, FormStateInterface $form_state) {
    $email = $form_state->getValue('email');
    $error = '';
    $public_domains = ['yahoo.com', 'gmail.com', 'outlook.com', 'hotmail.com'];
    $domain = substr(strrchr($email, "@"), 1);
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $error = 'Please enter valid Email Format.';
    }
    else if (!in_array($domain, $public_domains)) {
      $error = 'The email must be from a public domain and must end with .com.';
    }
    $ajaxResponse = new AjaxResponse();
    $ajaxResponse->addCommand(new HtmlCommand('#email_err', $error));
    return $ajaxResponse;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state): void {
    $this->config('config_form.settings')
      ->set('full_name', $form_state->getValue('full_name'))
      ->set('phone_number', $form_state->getValue('phone_number'))
      ->set('email', $form_state->getValue('email'))
      ->set('gender', $form_state->getValue('gender'))
      ->save();

    $full_name = $form_state->getValue('full_name');
    $phone_number = $form_state->getValue('phone_number');
    $email = $form_state->getValue('email');
    $gender = $form_state->getValue('gender');

    \Drupal::messenger()->addMessage($this->t('The form has been submitted successfully.'));
    \Drupal::messenger()->addMessage($this->t('Full Name: @full_name', ['@full_name' => $full_name]));
    \Drupal::messenger()->addMessage($this->t('Phone Number: @phone_number', ['@phone_number' => $phone_number]));
    \Drupal::messenger()->addMessage($this->t('Email: @email', ['@email' => $email]));
    \Drupal::messenger()->addMessage($this->t('Gender: @gender', ['@gender' => $gender]));
  }
}
