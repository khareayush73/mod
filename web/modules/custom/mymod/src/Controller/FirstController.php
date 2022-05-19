<?php
/**
 * @file
 * Contains \Drupal\mymod\Controller\MyModController.
 */
  
 namespace Drupal\mymod\Controller;

 use Drupal\Core\Controller\ControllerBase;

 class FirstController extends ControllerBase {
     public function content() {
         return array(
             '#type' => 'markup',
             '#markup' => t('This is my menu linked custom page'),
         );
     }
 }
  