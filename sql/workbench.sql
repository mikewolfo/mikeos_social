SELECT *
FROM user;

use mikeos_social;

select * from user;

delete from user where user_id = 7;

select * from token where token = 'acdvzuntyxdjbnzjkoxdgklinvbzorhrvkmwhzphysqzhpktfixmukwjgzjwq';

SELECT count(*) as count FROM token WHERE token = 'acdvzuntyxdjbnzjkoxdgklinvbzorhrvkmwhzphysqzhpktfixmukwjgzjwq' AND user_id IS NULL;

select