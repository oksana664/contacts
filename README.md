# Contacts App

It's a web app where you are going to be able to Create, Read, Update and Delete contacts.

You can use the web interface or the API.

## Deployed app

In this URL you are going to be able to test the UI and the services using postman or similar.

    http://federicogon.ml:8080/

## Dependencies
- Docker (https://docs.docker.com/install/)
- Composer Docker (https://docs.docker.com/compose/install/)

## Installation
In command line run the following commands:
    
    $ git clone git@github.com:federicogon/contacts.git
    $ docker-compose up -d
    
Then in your browser goto the url:
    
    http://localhost/


## Endpoints
All endpoints will return a **json** if the request has the headers:
    
    X-Requested-With: XMLHttpRequest 

otherwise will return an **HTML**.

I decide **NOT** to use a API REST because I want to take advantage of phalcon's  auto generated HTML code.

Also, Using API REST structure I'd need a heavier client (js) to do the ajax calls and draw the UI. 
I tried to keep the project as simple as possible that's why I didn't use JS to validate the fields and I used HTML5.


You can use Postman to import **tests/Contacts.postman_collecyion.json** for calls examples.

####/contacts/create
This method create a new contact.

Request POST:

    - first_name: [required]
    - last_name: [required]
    - email: <name>@<domain> [optional]
    - birthday: YYYY-MM-DD [optional]
    - phone: [optional]
Json Response:

    {
          "message": {
              "success": [
                  "Contact was created successfully"
              ]
          },
          "success": true
    }
    
####/contacts/search
This method list and filter the contacts.
If you call this endpoint with the GET verb we are going to get the list with the last filter applied. 

Request POST: 

    - search: partial match in first_name, last_name or email fields. [optional]

Json Response:

    {
       "page": {
           "items": [
               {
                   "id": "1",
                   "first_name": "Federico 2",
                   "last_name": "Gon",
                   "email": "test@fake.com",
                   "birthdate": "1984-03-18",
                   "phone": "1554545"
               },
               ...
           ],
           "first": 1,
           "before": 1,
           "previous": 1,
           "current": 1,
           "last": 1,
           "next": 1,
           "total_pages": 1,
           "total_items": 8,
           "limit": 10
       },
       "message": null,
       "success": true
    }

####/contacts/delete/<id>
Json Response:

    {
        "message": {
            "success": [
                "Contact '<contact name>' was deleted"
            ]
        },
        "success": true
    }

####/contacts/update
This endpoint updates a contact 

Request POST: 

    - id: [required]
    - first_name: [required]
    - last_name: [required]
    - email: <name>@<domain> [optional]
    - birthday: YYYY-MM-DD [optional]
    - phone: [optional]

Json Response:

{
    "id": "<id>",
    "message": {
        "success": [
            "Contact '<contact name>' updated"
        ]
    },
    "success": true
}

####/contacts/edit
This endpoint return a contact. Usefull for the UI to fill the edit form. 

Request POST: 

    - id: [required]


Json Response:

    {
        "id": "3",
        "contact": {
            "id": "3",
            "first_name": "Federico 2",
            "last_name": "Gon",
            "email": "test@fake.com",
            "birthdate": "1984-03-18",
            "phone": "1554545"
        },
        "message": null,
        "success": true
    }

## Tests
To run the automated test you have to:

1) docker exec -it web-contact /bin/bash
2) cd /var/www/ && composer install
3) codecept run

Example test output $ codecept run 
    
    Codeception PHP Testing Framework v3.0.3
    Powered by PHPUnit 8.2.5 by Sebastian Bergmann and contributors.
    Running with seed: 
    
    
    Api Tests (14) ----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
    ✔ ApiCest: Try contact search (0.02s)
    ✔ ApiCest: Try contact create (0.02s)
    ✔ ApiCest: Try contact create without first name (0.01s)
    ✔ ApiCest: Try contact create without last name (0.01s)
    ✔ ApiCest: Try contact create invalid email (0.01s)
    ✔ ApiCest: Try contact create empty (0.01s)
    ✔ ApiCest: Try contact save (0.02s)
    ✔ ApiCest: Try contact save without id (0.01s)
    ✔ ApiCest: Try contact save without first name (0.01s)
    ✔ ApiCest: Try contact save without last name (0.01s)
    ✔ ApiCest: Try contact save invalid email (0.01s)
    ✔ ApiCest: Try contact save empty (0.01s)
    ✔ ApiCest: Try contact delete (0.02s)
    ✔ ApiCest: Try contact delete without id (0.01s)
    -------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
    
    
    Time: 290 ms, Memory: 12.00 MB
    
    OK (14 tests, 68 assertions)
