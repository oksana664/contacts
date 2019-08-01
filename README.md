# Contacts App

## what is it about?
It's an web app where you are going to be able to Create, Read, Update and Delete contacts.

## Dependencies
- Docker (https://docs.docker.com/install/)
- Composer Docker (https://docs.docker.com/compose/install/)

## How to run it?
In command line run the following commands:
    
    $ git clone git@github.com:federicogon/contacts.git
    $ docker-compose up
    
Then in your browser goto the url:
    
    http://localhost/


## Endpoints
All endpoints will return a **json** if the request has the headers:
    
    X-Requested-With: XMLHttpRequest 

otherwise will return an **HTML**.

I decide **NOT** to use a API REST because I want to take advantage of phalcon's  auto generated HTML code.

Also, Using REST I'll need a heavier client (js) to do the ajax calls and draw the UI. 
I tried to keep the project as simple as possible and in this case It wasn't necessary to add JS into the project.


You can use Postman to import tests/Contacts.postman_collecyion.json for endpoints calls examples.

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
Json Response: Same as /contacts/search

####/contacts/update
This endpoint updates a contact 

Request POST: 

    - id: [required]
    - first_name: [required]
    - last_name: [required]
    - email: <name>@<domain> [optional]
    - birthday: YYYY-MM-DD [optional]
    - phone: [optional]

Json Response: Same as /contacts/search

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

## Unit Tests
COMMING SOON