
#### CHANGELOG FEEDATY 2.0.8 PRESTASHOP



-------------------------------------------------------------------------------------------------------------------------
#### RELASE NOTES
-------------------------------------------------------------------------------------------------------------------------

### v 2.1.0

## Technical updates
== New Feedaty Classes Addedd ==

Added:
 - FeedatyGenerateElements
 - FeedatyPositions
 - FeedatyWebservice

Updated:
 - Feedaty

== New Templates Added ==
 - views/templates/hook/FeedatyWidgetStore
 - views/templates/hook/FeedatyTemplateProduct

== New stylesheet ==
 - feedaty/feedaty_styles.css

### v 2.0.8

## Fixed issues

== Fixed an issue that overwriting the styles in the backend ==
Updated:
- css/ps_style.16.css
- views/templates/admin/backoffice.tpl

## Functional enhancements

== The microdata configuration has been separated from the widgets configurations ==
Added:
 -feedaty.php Feedaty::fdGenerateMerchantSnippet()
 -feedaty.php Feedaty::fdGenerateProductSnippet()
Updated:
 -feedaty.php Feedaty::fdGenerateProductWidget()
 -feedaty.php Feedaty::fdGenerateStoreWidget()
 -feedaty.php Feedaty::hookdisplayProductButtons()
 -feedaty.php Feedaty::getContent()
 -feedaty.php Feedaty::hookextraLeft()
 -feedaty.php Feedaty::hookextraRight()
 -feedaty.php Feedaty::hookproductActions()
 -feedaty.php Feedaty::hookproductOutOfStock()
 -feedaty.php Feedaty::hookproductfooter()
 -feedaty.php Feedaty::hookheader()
 -feedaty.php Feedaty::hookhome()
 -feedaty.php Feedaty::hookfooter()
 -feedaty.php Feedaty::hooktop()
 -feedaty.php Feedaty::hookleftColumn()
 -feedaty.php Feedaty::hookrightColumn()
 -views/templates/admin/backoffice.tpl

 == Update italian translations == 

 
### V 2.0.7

## Fixed issues

==  Fixed a bug that sends messages even if the debug function is disabled == 
- feedaty.php Feedaty::getProductRichSnippet()
- feedaty.php Feedaty::getMerchantRichSnippet()

### V 2.0.6

## Functional enhancements

==  Decreased microdata time out to 250ms == 
- feedaty.php Feedaty::getProductRichSnippet()
- feedaty.php Feedaty::getMerchantRichSnippet()

### V 2.0.5

## Fixed issues

==  Added a control on http response code to avoid errors on frontend == 
- feedaty.php Feedaty::getProductRichSnippet()
- feedaty.php Feedaty::getMerchantRichSnippet()

### V 2.0.4

## Functional enhancements

== Added a debugging function to keep track of errors ==
- feedaty.php Feedaty::getProductRichSnippet()
- feedaty.php Feedaty::getMerchantRichSnippet()
- feedaty.php Feedaty::hookUpdateOrderStatus()

### V 2.0.3

## Fixed issues

== Fix unsecure URL on products reviews tab ==

### V 2.0.2

## Fixed issues

== Fix bug on product tab, the product reviews tab was duplicated on versions between 1.6.1 and 1.6.9 ==


### V 2.0.1

## Fixed issues

- Fix Product reviews for PS 1.7
- Don't load backward compatibility on PS 1.7
- Add to cache Google microdata to speedup the site loading
- Fix Bug on top position for merchant widget


### V 2.0.0

## Functional enhancements

- New Feedaty API with OAuth authentication for sending Orders
- New google rich snippets
- Survey email is sent in Customer language

## Known Issues

- Feedaty plugin is not caching merchant's and product's rich snippets ( we'll add this function in next relase )
- Top position for merchant widget breack site functionalities on PS 1.6
- PS 1.7 is not showing product reviews in products page

-------------------------------------------------------------------------------------------------------------------------
#### RELASE NOTICE
-------------------------------------------------------------------------------------------------------------------------
### V 2.1.0

- Fixato problema sugli hook cambiando il nome degli hook in fase di setup 
- Il plugin segue ora il pattern MVC per il render di widget e microdata 
- Aggiunto un foglio di stile per poter sistemare le posizioni dello widget e falicilitare il posizionamento nel blocco desiderato
- Sepate le classi del plugin per convertire il plugin con paradigma OOP 
- Inizio della revisione delle classi del plugin per riallinearle allo standard PSR-2 e rispettare i coding standards di prestashop

### V 2.0.8

## Functional enhancements

- In questa versione abbiamo separato la configurazione dei microdata dagli widget, ora i microdata sono indipendenti al 100%
dagli widget

## Fixed issues

- In questa versione abbiamo sistemato un un bug che sovrascriveva alcuni stili del backend di prestashop


### V 2.0.7

## Fixed issues

- In questa versione abbiamo sistemato un errore che inviava messaggi di log anche se la funzione di debug era disattivata - Aggiornare Immediatamente


### V 2.0.6

## Functional enhancements

- in questa versione  abbiamo diminuito il timeout della chiamata ai microdata a 250ms

### V 2.0.5

## Fixed issues

- In questa versione abbiamo aggiunto un controllo sul codice di risposta http del server, per evitare errori nel frontend - Aggiornare Immediatamente


### V 2.0.4

## Functional enhancements

- In questa versione abbiamo aggiunto una funzione di debug per tenere traccia degli errori nell'invio degli ordini e nella visualizzazione dei microdata


### V 2.0.3

## Fixed issues

- In questa versione abbiamo sistemato un url non sicuro nella tab di review dei prodotti - Aggiornare immediatamente

### V 2.0.2

## Fixed issues

- In questa versione abbiamo sistemato un bug per le review prodotti, 
La tab di prodotti veniva duplicata per le versioni di prestashop tra 1.6.1 and 1.6.9 - Aggiornare Immediatamente


### V 2.0.1

## Fixed issues
In questa versione abbiamo sistemato i seguenti bug:
- Sistemate le review prodotti per Prestashop 1.7
- Non carica la backward compatibility su PS 1.7, cio genererebbe errori
- Aggiunti alla cache i mircrodata per diminuire il tempo di caricamento della pagina
- Sistemato un bug che bloccava jquery in caso il merchant widget fosse in posizione TOP


### V 2.0.0

## Functional enhancements
Questa versioen aggiunge le seguenti funzioni:
- Aggiunte nuove API OAuth con autenticazione per l'invio di ordini a feedaty
- Nuovi Rich Snippet di google
- La mail di survey è spedita nella lingua del cliente

## Known Issues

- La plugin non sta memorizzando in cache i rich snippets
- la posizione top per gli widget blocca il frontend del sito 
- Prestashop 1.7 non visualizza le review nella pagina prodotti

