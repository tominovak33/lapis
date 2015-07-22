# LAPIS API DOCUMENTATION


## GET

### Available Options:

* ORDER_BY

    Accepts a field name that is present in the database.

    Eg:

        http://lapis.local.dev/GET/?ORDER_BY=author_id

* ORDER

     Optional if ORDER_BY is set. Defaults to ascending.
     Available options: "ASC" , "DESC"

     Eg:

        http://lapis.local.dev/GET/?ORDER_BY=author_id&ORDER=desc

* LIMIT

    Accepts an integer to limit amount of responses to. Defaults to 50

## POST

### Coming soon
