# L.A.P.I.S.
A Lightweight API Server (a base from which very simple, lightweight and reusable APIs can be quickly built from)

## Spec
  * Returns arrays of results
  * Can query by id (only returns JSON object)
  * Can query by row (where rowx equal / not equal / contains / > / < "this")
  * Gives objects a unique id
  * Can return failed responses from the server in a promisy fashion ({res:[], err:{code: 404, msg: "error does not exist"})
  * Can easily push up new data in a similar way to pulling it down
  * One php script for bidirectional uploads and requests

## Deployment
  * Deployment script is located in /development and should be run from the same directory as the www folder is placed in
