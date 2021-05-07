# PIR | Prosopographia Imperii Romani

## About the CN Project

**The encyclopaedia indexes and documents the ruling class of the Roman Empire.**  

The Roman Empire had come into being through conquest; but the ruling elites of the subjugated peoples were gradually incorporated into the political structure of the Empire. This contributed greatly to its remarkable stability. Local notables became involved in the running of the empire as senators or knights, providing high office holders and troop commanders. These families often exercised influence for generations, not only at the leadership level but also in their home communities, where they ensured loyalty to the Empire.

We have a great deal of evidence about these individuals - through inscriptions, papyri, coins and literary texts. These pieces are widely scattered and, individually, allow only a few meaningful insights into the lives of these people. Only a synopsis of the details can provide coherent historical insights. This is done in the "Prosopographia Imperii Romani", which thus provides a comprehensive picture of the leading classes of the Empire from Augustus to Diocletian (284 - 305 AD). Included are all members of these social groups, including women and family members - about 14,000 persons to date. The articles contain (except for the emperors) all testimonies and - as far as can be reconstructed - a short biography. A first edition of the encyclopaedia at the end of the 19th century was a breakthrough for research. Due to many new discoveries, a revision soon became necessary. This second edition was completed in 2006.

## About the PIR App

The [PIR App](https://pir.bbaw.de) is a lightweight piece of software to search the records of the PIR.  
A restful JSON API allows crawling the data autmatically.  

Feel free to adapt the source code in any way you wish.

## Installation

* clone the repository
* add an <code>.env</code> file and set the appropiate <code>APP_URL</code>
* set writing permissions for www-data on <code>storage</code>
* run <code>php composer install</code> to get the required packages
* setup your Webserver

## Updating Data

* Copy the complete Data as csv to the <code>data</code> directory
* run <code>php artisan import:csv {filename}</code> to write the data to <code>persons.json</code>. Any structural issues or wrong IDs will result in aborting the script.
* run <code>php artisan export:csv</code> and check if the resulting csv is identical to the provided one

## Main Dependencies

* [Laravel ^8.0](https://laravel.com/)
* [PHP 8.0](https://www.php.net/)
* [Vue.js 2.6](https://vuejs.org/)
* [Vue Router 3.0.1](https://router.vuejs.org/)
* [Vuetify 2.4.6](https://vuetifyjs.com/en/)

## Realization and Licensing

[Berlin-Brandenburg Academy of Sciences and Humanities](https://www.bbaw.de/)   
[TELOTA - IT/DH](https://www.bbaw.de/en/bbaw-digital/telota)   
2020-2021

The PIR App is open-sourced software licensed under the [GPLv3](http://www.gnu.org/licenses/gpl-3.0.en.html)   
created by [Jan Köster](https://orcid.org/0000-0003-2713-5207).