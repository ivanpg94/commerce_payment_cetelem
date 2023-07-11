<?php

namespace Drupal\commerce_payment_cetelem\PluginForm;

use Drupal\commerce_payment\PluginForm\PaymentOffsiteForm as BasePaymentOffsiteForm;
use Drupal\Core\Form\FormStateInterface;
use Drupal\commerce_payment_cetelem\Plugin\Commerce\RedirectCheckout;


/**
 * Class PaymentOffsiteForm
 * @package Drupal\commerce_payment_cetelem\Services
 */

class PaymentOffsiteForm extends BasePaymentOffsiteForm
{
  public function buildConfigurationForm(array $form, FormStateInterface $form_state) {
    $form = parent::buildConfigurationForm($form, $form_state);
    $payment = $this->entity;
    $payment_gateway_plugin = $payment->getPaymentGateway()->getPlugin();
    $configuration = $payment_gateway_plugin->getConfiguration();
    $currency = $payment->getAmount()->getNumber();


    //IdTransaccion
    $id = $payment->getPaymentGatewayId();
    $cetelem_transaction = substr(date('y'), -1) . sprintf('%03d', date('z') + 1) . sprintf('%06d', $id);

    $currency = $currency * 100;
    $var1 = (string) $currency;
    $var2 = str_replace('.','',$var1);
    $var2 = intval($var2);
    $total = $var2;

    //NIF
    $nif = '';
    if (!isset ($nif)){
      $nif = "";
    }
    //Phone
    $phone = '';

    $redirect_url = 'https://www.cetelem.es/eCommerceLite/configuracion.htm';

    $data= array(
      "COMANDO" => "INICIO",
      "CodCentro" => $configuration['CodCentro'],
      "IdTransaccion" => $cetelem_transaction,
      "Importe"  => $total,
      "Modalidad"  => $configuration['Modalidad'],
      "ReturnURL"  => $form['#cancel_url'],
      "ReturnOK"  =>  $form['#return_url'],
      "NIF" => $nif,
      "Telefono1" => $phone,
    );

    return [$this->buildRedirectForm(
      $form,
      $form_state,
      $redirect_url,
      $data,
      self::REDIRECT_POST),];
  }

}


