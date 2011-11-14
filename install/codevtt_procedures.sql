

-- -----------------------------------------------------
-- Get project_id bug_resolved_status_threshold value from Mantis config
-- or (if not found) the default value defined in CodevTT config
-- -----------------------------------------------------
DROP FUNCTION IF EXISTS get_project_resolved_status_threshold;

DELIMITER |
CREATE FUNCTION get_project_resolved_status_threshold(proj_id INT) 
RETURNS INT
DETERMINISTIC
BEGIN
   DECLARE status INT DEFAULT NULL;
   
   SELECT value INTO status FROM `mantis_config_table` 
          WHERE config_id = 'bug_resolved_status_threshold' 
          AND project_id = proj_id
          LIMIT 1;
   
   IF status <=> NULL THEN
      SELECT value INTO status FROM `codev_config_table`
             WHERE config_id = 'bug_resolved_status_threshold' 
             AND project_id = 0
             LIMIT 1;
   END IF;
   
   RETURN status;
END|
DELIMITER ;

-- -----------------------------------------------------
-- Get project_id bug_resolved_status_threshold value from Mantis config
-- or (if not found) the default value defined in CodevTT config
-- -----------------------------------------------------
DROP FUNCTION IF EXISTS get_issue_resolved_status_threshold;
   
DELIMITER |
CREATE FUNCTION get_issue_resolved_status_threshold(bug_id INT) 
RETURNS INT
DETERMINISTIC
BEGIN
   DECLARE proj_id INT DEFAULT NULL;
   
   SELECT project_id INTO proj_id FROM `mantis_bug_table`
             WHERE id = bug_id
             LIMIT 1;
   
   RETURN get_project_resolved_status_threshold(proj_id);
END|
DELIMITER ;



-- -----------------------------------------------------
-- TESTS
-- -----------------------------------------------------

-- SELECT get_project_resolved_status_threshold(21); 
-- SELECT get_issue_resolved_status_threshold(50); 