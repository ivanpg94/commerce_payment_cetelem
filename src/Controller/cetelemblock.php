<?php
/**
 * @file
 * Contains \Drupal\commerce_payment_cetelem\Controller\cetelemblock.
 */

namespace Drupal\commerce_payment_cetelem\Controller;

use Drupal\Core\Controller\ControllerBase;

class cetelemblock
{
  public function content()
  {
    return [
      '#theme' => 'block--cetelemblock',
      '#test_var' => $this->t('Test Value'),
    ];

  }
}
