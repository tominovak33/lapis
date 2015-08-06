# Development

## Code snippets

### Inserting

  * Insert a new item into the default table

      curl -d "technology=bash&author_id=3333&description=curl%20request&code=foo" http://lapis.local.dev/POST/

      Putting '%20' rather than spaces is not required but I prefer doing it that way

### Inserting a new item into the users table

	(In this scenario you can substitute the table name and post variables (columns of the table in question) for any table you have in your database)

	curl -d "username=tominovak33&first_name=tomi&last_name=novak&bio=test&CONTENT_TABLE=users" http://lapis.local.dev/POST/