SELECT * FROM notes;
INSERT INTO notes (description, created_at, updated_at) 
VALUES ('Hello Beth!','2014-02-09, 12:04:06','2014-02-09, 12:04:06');
UPDATE profiles p 
SET 
    p.id = 1,
    p.user_id = 1,
    p.profile_txt = 1,
    p.updated_at = 1
WHERE
    u.id = 1;
DELETE FROM friends 
WHERE
    friend_id = 1 AND user_id = 4;
SELECT 
    u.id,
    CONCAT(u.first_name, ' ', u.last_name) AS Name,
    u.email AS Email
FROM
    comments c
        INNER JOIN
    messages m ON c.message_id = m.id
        INNER JOIN
    users u ON m.author_id = u.id
WHERE
    m.id = 1
GROUP BY u.id
ORDER BY u.last_name ASC , u.first_name ASC;