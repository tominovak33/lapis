# LAPIS API DOCUMENTATION

When reading this documentation, substitute:

    http://lapis.local.dev/

With the domain that you are using as your endpoint.

When referring to what the API will return, unless explicitly specified otherwise the documentation is referring only to the 'data' part of the returned json. So for example if the true response is:

    {
        "request_time": 1437600903,
        "address": "127.0.0.1",
        "finish_time": 1437600903,
        "process_time": 0,
        "database_queries": 1,
        "software": "Apache\/2.4.7 (Ubuntu)",
        "request_method": "POST",
        "data": {
            "successful": true,
            "insert_id": 103
        }
    }

The documentation will only specify:

    "data": {
        "successful": true,
        "insert_id": 103
    }

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

* COUNT

    Accepts any value (as long as '&COUNT' is present in the querystring this will get executed)

    Counts and returns the amount of matching items for the query specified. This does not return the items themselves. (Maybe useful for preventing the frontend from receiving a lot of data. But still allowing the user to see if their search would return results )

    Eg:

        http://lapis.local.dev/GET/?technology=php&COUNT

## POST

### Available Options

Accepts all options that match column names in the database table for the content that lapis is being used to store.

Due to this a specific, hardcoded list of options is not available in the documentation as this changes depending on the circumstances. However a request to the the following endpoint with expose all these column names:

    http://lapis.local.dev/OPTIONS/

  This will return something such as:

    "data": [
     "id",
     "author_id",
     "technology",
     "description",
     "code"
    ]

#### Example request:
Url:

    http://lapis.local.dev/POST/

Post data:

      {
          "author_id": "5",
          "technology": "php",
          "description": "some php code written by author 5",
          "code": "the code would go here"
      }

Example with the basic xmlhttp request:

    // Setting up the xmlhttp object
    var xmlhttp;
    if (window.XMLHttpRequest){
      // code for IE7+, Firefox, Chrome, Opera, Safari where xmlhttp request works
      xmlhttp=new XMLHttpRequest();
    }
    else {
      // code for IE6, IE5
      xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
      // or alternatively
      alert('This browser is not supported. Unlucky');
    }

    // Processing the response when it comes back
    xmlhttp.onreadystatechange=function(){
      if (xmlhttp.readyState==4 && xmlhttp.status==200){
        var response =xmlhttp.responseText;
        // Process the response here
      }
    }

    // Making the request
    xmlhttp=new XMLHttpRequest();
    xmlhttp.open("POST","http://lapis.local.dev/POST/",true);
    xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
    xmlhttp.send("author_id=3333&technology=javascript&description=xmlhttp%20requests&code=this%20is%20the%20code");
