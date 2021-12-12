# Requisiti

* PHP >= 7.4

# Getting started

1. Installare le dipendenze

   ```shell script
   composer install
   ```

2. Avviare il webserver built in

   ```shell script
   PATH/TO/php.exe -S 0.0.0.0:8000 -t PATH/TO/PROJECT
   ```


# Risorse utilizzate

* __Slim:__  
  Microframework PHP per disaccoppiare route e controller
  
* __PHP-View:__  
  Libreria PHP per disaccoppiare le viste
  
* __Carbon:__  
  Libreria PHP per facilitare la gestione delle date
  
* __VueJs:__  
  Framework JS per gestire al meglio la renderizzazione e l'interazione delle view

* __Quasar Framework:__  
  UI Component framework basato su VueJs per una implementazione 
  più rapida di una UI accattivante


# Come funziona

Una volta avviato il webserver avremo a disposizione due route:

1. __Home:__   
   Disponibile alla url http://localhost:8000/ utilizzando il metodo GET (ipotizzando che sia esposto sulla porta 8000).  
   Questa route espone una sola vista contenente un semplice form in cui decidere i filtri da applicare nella route successiva.
   
2. __Lista utenti:__  
   Disponibile alla url http://localhost:8000/users utilizzando il metodo POST.   
   Questa route espone può esporre una vista tra tabella di utenti e lista thumbnail
   di utenti in base ai parametri passati in input.
