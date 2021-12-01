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

/* marshallscee server has outdated software, so this is a version that works on older mysql and php versions 
	It selects the output rather than having an OUT parameter.
*/

CREATE PROCEDURE logIn(IN user_id VARCHAR(45), IN user_password VARCHAR(45))
BEGIN

	DECLARE role_num INT;
    DECLARE role_name VARCHAR(45);

	SELECT UserRole INTO role_num
	FROM Users
	WHERE UserId = user_id AND UserPassword=user_password;

	SELECT UserRoleName
	FROM UserRole
	WHERE UserRole = role_num;
    
END
