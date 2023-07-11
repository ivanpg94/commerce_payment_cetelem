var total = drupalSettings.commerce_payment_cetelem.total;
if (total['total'] > 90){
  codCentro = total['codcentro'];
  cantidad = total['total'];
  color = "#525a31";
  server = 'https://www.cetelem.es';
  listadoMeses = "3,6,12,18,24";
  fontSize = "14";
  document.write('<scr'+'ipt type="text/JavaScript" src="'+server+'/eCommerceCalculadora/resources/js/eCalculadoraCetelemComboModelo2.js" async></scr'+'ipt>');
}else{
  function Visibility() {
    elemento = document.getElementById("block-cetelemblock").style.display = "none";
  }
  Visibility();

}
