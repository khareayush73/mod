<?php
/**
 * @file
 * Contains \Drupal\Rsvp\Form\RSVPForm
 */

namespace Drupal\rsvp\Form;

use Drupal\Core\Database\Database;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/**
  * Provides an RSVP Email Form.
  */
 
class RSVPForm extends FormBase {
  /**
    * (@inheritdoc)
    */
  public function getFormId() {
    return 'rsvp_email_form';
  }
  /**
    * (@inheritdoc)
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $node = \Drupal::routeMatch()->getParameter('node');
    $nid = $node->nid->value;
    $form['email'] = array(
      '#title' => t('Email Address'),
      '#type' => 'textfield',
      '#size' => 25,
      '#description' => t("We'll send updates to the email address you provide"),
      '#required' => TRUE,
    );
    $form['submit'] = array(
      '#type' => 'submit',
      '#value' => t('RSVP'),
    );
    $form['nid'] = array(
      '#type' => 'hidden',
      '#value' => $nid,
    );
    return $form;
  }
  /**
   * (@inheritdoc)
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    $value = $form_state->getValue('email');
    if ($value == !\Drupal::service('email.validator')->isValid($value)) {
      $form_state->setErrorByName ('email', t('The email address %mail is not valid.', array('%mail' => $value)));
    }
  }
 /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $user = \Drupal\user\Entity\User::load(\Drupal::currentUser()->id());
    \Drupal::database()->insert('rsvp')
      ->fields(array(
        'mail' => $form_state->getValue('email'),
        'nid' => $form_state->getValue('nid'),
        'uid' => $user->id(),
        'created' => time(),
       ))
      ->execute();
    
    $this->messenger()->addStatus('Thank you for your RSVP, you are on the list for the event.');
  }
}