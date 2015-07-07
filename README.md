# LAPIS
A Lightweight API Server (a base from which very simple, lightweight and reusable APIs can be quickly built from)

## Spec
  * Returns arrays of results
  * Can query by id (only returns JSON object)
  * Can query by row (where rowx equal / not equal / contains / > / < "this")
  * Gives objects a unique id
  * Can return failed responses from the server in a promisy fashion ({res:[], err:{code: 404, msg: "error does not exist"})
  * Easily hooked into (this way LapisJS can easily sit on top and also, modules)

## Spec (LapisJS)
  * Can be called using async promises
  * Easy to build on / strip down (modular?)

## How it could work (LapisJS)
  ```
    Lapis.mine("dbKey" || "host" || "path");
    // having u & p for the db in in an app/page might be risky,
    // although if its local it could be easier, just make sure the location of the db comm platform is absolute

    Lapis.query("Table")
      .where({
        "row": Lap.is("equal", "val"),
        "row2": Lap.is("!equal", "val2"),
        "row3": Lap.is(">", 3)
      })
      .find()
      .then(function (res) {
        // success
      }, function (err) {
        // oops, something went wrong
      });
  ```
  Note: maybe lapis should just be the curl half, then this could be a js shell we can put on top (LAPIS JS)?
  that way we could also have other LAPIS versions.

## What this will need
  * Security! unlike PHP, JS is completely open, we don't want any old person fiddling with the DB.

## What it could have
  * Command line interface `lapis upgrade`, `lapis init` etc.
  * Lapis modules that can be installed from the command line `Lapis pebbles`? (this way the original lapis is light but we can expand on it)


## Questions
  * Do we build this purely for JS?
  * Do we have a PHP middle man? (JS - PHP - SERVER || JS - SERVER)

