<?php
/*This file generates the payment request and sends it to the Mercanet server
For more information on this use case, please refer to the following documentation:
Ce fichier génère la requête de paiement et l'envoie au serveur Mercanet
Pour plus d'informations sur ce cas d'utilisation, veuillez consulter la documentation suivante :
https://documentation.mercanet.bnpparibas.net/index.php?title=Paiement_en_2-3_fois */

session_start();

include('Common/sealCalculationPaypagePost.php');
include('Common/flatten.php');

//PAYMENT REQUEST - REQUETE DE PAIEMENT

//You can change the values in session according to your needs and architecture - Vous pouvez modifier les valeurs en session en fonction de vos besoins et de votre architecture
$_SESSION['secretKey'] = "002001000000002_KEY1";
$_SESSION['sealAlgorithm'] = "HMAC-SHA-256";
$_SESSION['normalReturnUrl'] = "http://localhost/mercanet-paypage-post-php/Common/paymentResponse.php";

$requestData = array(
   "normalReturnUrl" => $_SESSION['normalReturnUrl'],
   "merchantId" => "002001000000002",
   "amount" => "9000",           //Note that the amount entered in the "amount" field is in cents - Notez que le montant saisi dans le champ "montant" est en centimes
   "orderChannel" => "INTERNET",
   "currencyCode" => "978",
   "keyVersion" => "1",
   "responseEncoding" => "base64",

   "transactionReference" => "m136",
   "paymentPattern" => "INSTALMENT",
   "instalmentData" => array(
      "number" => "3",
      "amountsList" => array('1000','1000','7000'),   //The sum of these amounts must be equal to the content of the amount field - La somme de ces montants doit être égale au contenu du champ montant
      "datesList" => array('20200805','20200806','20200807'),  //Change the dates according to the time of the test of this use case - Modifiez les dates en fonction de l'heure du test de ce cas d'utilisation
      "transactionReferencesList" => array('m136','m236','m336'),   //The first reference must be equal to the one contained in the transactionReference field - La première référence doit être égale à celle contenue dans le champ transactionReference
   ),
);

$dataStr = flatten_to_mercanet_payload($requestData);

$dataStrEncode = base64_encode($dataStr);

$_SESSION['seal'] = compute_seal_from_string($_SESSION['sealAlgorithm'], $dataStrEncode, $_SESSION['secretKey']);

$_SESSION['data'] = $dataStrEncode;

header('Location: Common/redirectionForm.php');

?>
