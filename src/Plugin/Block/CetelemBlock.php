<?php

namespace Drupal\commerce_payment_cetelem\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\commerce_payment_cetelem\Plugin\RedirectCheckout;
use Drupal\commerce_payment\Exception\PaymentGatewayException;
use Drupal\commerce_payment\PluginForm\PaymentOffsiteForm as BasePaymentOffsiteForm;
use Drupal\Core\Form\FormStateInterface;
use Drupal\commerce_order\Entity\Order;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\ReplaceCommand;

/**
 * Provides a Cetelem block.
 *
 * @Block(
 *
id = "cetelem_block",
 *
admin_label = @Translation("Cetelem Block")
 * )
 */

class CetelemBlock extends BlockBase
{


  public function build() {

    $form['#attached']['library'][] = 'commerce_payment_cetelem/commerce_payment_cetelem';
    $renderable = [
      '#theme' => 'block--commerce-payment-cetelem',
      '#test_var' => 'test variable',
    ];
    return[
      $form, $renderable
    ];
  }
  public function reloadmes(array $form, FormStateInterface $form_state) {
    //Form options financiacion

    return $form;
  }
}

