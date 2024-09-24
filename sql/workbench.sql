SELECT *
FROM user;

use mikeos_social;

select * from user;
select * from token where user_id = 10;

select * from cookies;

UPDATE token SET user_id = NULL where token = 'acdvzuntyxdjbnzjkoxdgklinvbzorhrvkmwhzphysqzhpktfixmukwjgzjwq';

SELECT count(*) as count FROM token WHERE token = 'acdvzuntyxdjbnzjkoxdgklinvbzorhrvkmwhzphysqzhpktfixmukwjgzjwq' AND user_id IS NULL;

select