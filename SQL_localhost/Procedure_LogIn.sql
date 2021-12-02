/* Stored procedure takes input username and password. */
/* Returns role of user or NULL if the user is not in the database */
/*
CREATE PROCEDURE LogIn(IN user_id VARCHAR(45), IN user_password VARCHAR(45), OUT user_role VARCHAR(45))
BEGIN

	DECLARE role_num INT;
    	DECLARE role_name VARCHAR(45);

	SELECT UserRole INTO role_num
	FROM Users
	WHERE UserId = user_id AND UserPassword=user_password;

	SELECT UserRoleName INTO role_name
	FROM UserRole
	WHERE UserRole = role_num;
    
    	SET user_role = role_name;
END
*/

/* marshallscee server has outdated software, so this is a version that works on older mysql and php versions.
	Also had to add COLLATE to get this working on the server. 
	To see why: https://stackoverflow.com/questions/11770074/illegal-mix-of-collations-utf8-unicode-ci-implicit-and-utf8-general-ci-implic
*/
DELIMITER $$

CREATE PROCEDURE logIn(IN user_id VARCHAR(45), IN user_password VARCHAR(45))
BEGIN

	SELECT r.UserRoleName, u.UserId, u.UserPassword
    FROM Users u
    JOIN UserRole r ON u.UserRole = r.UserRole
    WHERE UserId = user_id COLLATE utf8_unicode_ci
	AND UserPassword = user_password COLLATE utf8_unicode_ci; 

END$$
