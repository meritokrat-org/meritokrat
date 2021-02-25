<?php

class Version20201028075547
{
    public function up()
    {
        return <<<SQL
CREATE OR REPLACE RECURSIVE VIEW linked_users (id, parent, email, ancestors, depth) AS
(
SELECT u0.id                 AS id,
       u0.invited_by         AS parent,
       u0.email              AS email,
       ARRAY [] :: INTEGER[] AS ancestors,
       0                     AS depth
FROM user_auth u0
WHERE u0.invited_by = 0
UNION ALL
SELECT u.id                        AS id,
       u.invited_by                AS parent,
       u.email                     AS email,
       p.ancestors || u.invited_by AS ancestors,
       p.depth + 1                 AS depth
FROM user_auth u,
     linked_users p
WHERE p.id = u.invited_by
    );
SQL;
    }

    public function down()
    {
        return <<<SQL
DROP VIEW linked_users;
SQL;
    }
}