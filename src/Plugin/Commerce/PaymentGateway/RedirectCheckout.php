<?php
namespace Drupal\commerce_payment_cetelem\Plugin\Commerce\PaymentGateway;

use Drupal\commerce_order\Entity\OrderInterface;
use Drupal\commerce_payment\Plugin\Commerce\PaymentGateway\OffsitePaymentGatewayBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Ogone\PaymentResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Provides the Cetelem offsite Checkout payment gateway.
 *
 * @CommercePaymentGateway(
 *   id = "cetelem_redirect_checkout",
 *   label = @Translation("Cetelem (Redirect to cetelem)"),
 *   display_label = @Translation("Cetelem"),
 *    forms = {
 *     "offsite-payment" = "Drupal\commerce_payment_cetelem\PluginForm\PaymentOffsiteForm",
 *   },
 *   requires_billing_information = FALSE,
 * )
 * @see https://www.cetelem.es/eCommerceLite/configuracion.htm
 */

class RedirectCheckout extends OffsitePaymentGatewayBase{

  public function defaultConfiguration() {
    return [
        'CodCentro' => '',
        'Modalidad' => '',
      ] + parent::defaultConfiguration();
  }

  public function buildConfigurationForm(array $form, FormStateInterface $form_state) {
    $form = parent::buildConfigurationForm($form, $form_state);

//CodCentro
    $form['CodCentro'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Codigo del centro'),
      '#description' => $this->t('This is the center code from the Cetelem.'),
      '#default_value' => $this->configuration['CodCentro'],
      '#required' => TRUE,
    ];
//Modalidad
    $form['Modalidad'] = array(
      '#type' => 'radios',
      '#title' => t('Type of credit'),
      '#default_value' => $this->configuration['Modalidad'],
      '#options' => array(
        'G' => t('Free, no interests for the customer'),
        'N' => t('Normal, with interests'),
      ),
      '#required' => TRUE,
    );

    return $form;
  }

  public function submitConfigurationForm(array &$form, FormStateInterface $form_state) {
    parent::submitConfigurationForm($form, $form_state);
    $values = $form_state->getValue($form['#parents']);
    $this->configuration['CodCentro'] = $values['CodCentro'];
    $this->configuration['Modalidad'] = $values['Modalidad'];
  }


  public function onReturn(OrderInterface $order, Request $request) {

    // 1. Obtén los datos relevantes de la respuesta de Cetelem (reemplaza 'param1', 'param2', etc. con los nombres de parámetros reales)
    //$transaction_id = $request->get('param1');
    //$payment_status = $request->get('param2');
    // ...

    // 2. Verifica la validez de la respuesta (por ejemplo, verificando la firma digital si la hay)
    // ...

    // 3. Obtén el objeto de pago a partir del pedido
    $payment_storage = $this->entityTypeManager->getStorage('commerce_payment');
    $payment = $payment_storage->create([
      'state' => 'Pendiente',
      'amount' => $order->getTotalPrice(),
      'payment_gateway' => $this->parentEntity->id(),
      'order_id' => $order->id(),
    ]);
      $state_transitions = $order->getState()->getTransitions();
      if (isset($state_transitions['place'])) {
        $order->getState()->applyTransition($state_transitions['place']);
      }
    // 4. Actualiza el estado del pago según la respuesta de Cetelem
      $payment->setState('Pendiente');
      $order->save();
    // 5. Guarda el pago
    $payment->save();

    // 6. Redirige al usuario a la página de confirmación del pedido
    $url = Url::fromRoute('commerce_checkout.form', ['commerce_order' => $order->id(), 'step' => 'complete']);
    $response = new Response('', 302);
    $response->headers->set('Location', $url->toString());
    return $response;
  }
}
