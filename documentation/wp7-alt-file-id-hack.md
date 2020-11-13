# WP7 Alternative file id hack

This patch addresses an issue with the way files were migrated from the old D7 site in 2019.
It appears that 'duplicate' records have been created (in the file_managed table) for the same document.

View an example of this using:

```SELECT * FROM file_managed where uri like '%report-second_expert_roundtable_on_the_common_principles%';```

The patch adds code to the file_entity module. If the file is not found, the database is read to try to find a matching file with an earlier file id.
The filename, size, user etc. should be the same - it is the uri which will be slightly different (usually without a _0 or _1 appended to the name.

Checking the size should ensure that similarly-named documents are not returned accidently.

If not matching file is found, then a 404 is returned as before.
