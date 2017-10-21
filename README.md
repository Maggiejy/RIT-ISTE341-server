## MySQL to PostgreSQL
> CREATE OR REPLACE FUNCTION f_drop_db(dbname text) RETURNS text LANGUAGE sql AS
 $func$
 SELECT dblink_exec('port=5432 dbname=standup'
                 ,'DROP DATABASE ' || quote_ident(dbname))
 $func$;
> -- CREATE DATABASE standup
 --     WITH 
 --     OWNER = postgres
 --     ENCODING = 'UTF8'
 --     LC_COLLATE = 'English_United States.1252'
 --     LC_CTYPE = 'English_United States.1252'
 --     TABLESPACE = pg_default
 --     CONNECTION LIMIT = -1;
> DROP TABLE IF EXISTS STUDENT CASCADE;
> CREATE TABLE STUDENT(
	STUDENT_NAME VARCHAR(2) NOT NULL,
	STUDENT_ID int NOT NULL PRIMARY KEY,
	SECTION_ID  INT
);

> DROP TABLE IF EXISTS LANGUAGE CASCADE;
> CREATE TABLE LANGUAGE(	
       LANGUAGE VARCHAR(50) NOT NULL,
	LANGUAGE_ID INT NOT NULL,
	PRIMARY KEY(LANGUAGE_ID)
);

> DROP TABLE IF EXISTS SKILL CASCADE;
> CREATE TABLE SKILL(
	SKILL_ID INT,
	VALUE VARCHAR(50)
	PRIMARY KEY (SKILL_ID)
);

> DROP TABLE IF EXISTS USERLANG CASCADE;
> CREATE TABLE USERLANG(
	USERLANG_ID SERIAL PRIMARY KEY,
	STUDENT_ID INT NOT NULL REFERENCES STUDENT (STUDENT_ID),
	LANGUAGE_ID INT NOT NULL REFERENCES LANGUAGE (LANGUAGE_ID),
	SKILL_LEVEL INT REFERENCES SKILL (SKILL_ID), 
	NOTES VARCHAR(255)
);

>INSERT INTO STUDENT VALUES
('AA', 1,1),('AB', 2,1),('AC', 3,1),('AD', 4,1),('AE', 5,1),
('AF', 6,1),('AG', 7,1),('AH', 8,1),('AI', 9,1),('AJ', 10,1),
('AK', 11,1),('AL', 12,1),('AM', 13,1),('AN', 14,1),('AO', 15,1),('AP', 16,1),
('AQ', 17,2),('AR', 18,2),('AS', 19,2),('AT', 20,2),('AU', 21,2),('AV', 22,2),
('AW', 23,2),('AX', 24,2),('AY', 25,2),('AZ', 26,2),('BA', 27,2),('BB', 28,2),
('BC', 29,2),('BD', 30,2),('BE', 31,2),('BF', 32,2),('BG', 33,2),('BH', 34,2),
('BI', 35,2),('BJ', 36,2),('BK', 37,2),('BL', 38,2),('BM', 39,2),('BN', 40,2);

>INSERT INTO SKILL VALUES(1,'HIGH'), (2, 'MEDIUM'), (3, 'LOW'), (4,'ULTRA LOW');

>INSERT INTO LANGUAGE VALUES
('JAVA', 1),('JAVASCRIPT', 2),('C++', 3),('C', 4),('C#', 5),('PYTHON', 6),('PHP', 7),('COBOL', 8),('OTHER', 9);

>INSERT INTO USERLANG (STUDENT_ID, LANGUAGE_ID,SKILL_LEVEL,NOTES) VALUES
(1,1,1,NULL),(1,3,2,NULL),
(2,1,1,NULL),(2,3,2,NULL),(2,5,3,NULL),
(3,1,1,NULL),(3,3,2,NULL),
(4,1,1,NULL),(4,3,2,NULL),
(5,1,1,NULL),(5,3,2,NULL),
(6,1,1,NULL),(6,3,2,NULL),
(7,1,1,NULL),(7,3,2,NULL),
(8,1,1,NULL),(8,3,2,NULL),
(9,1,1,NULL),(9,3,2,NULL),
(10,1,1,NULL),(10,3,2,NULL),(10,6,3,NULL),
(11,1,1,NULL),(11,6,2,NULL),
(12,1,1,NULL),(12,3,2,NULL),
(13,1,1,NULL),(13,3,2,NULL),
(14,1,1,NULL),(14,3,2,NULL),
(15,1,1,NULL),(15,5,2,NULL),
(16,9,1,'NO PREFERENCE'),
(17,1,1,NULL),(17,2,2,NULL),(17,7,3,NULL),
(18,7,1,NULL),(18,9,2,'MYSQL'),(18,9,3,'HTML'),
(19,2,1,NULL),(19,7,2,NULL),(19,6,3,NULL),
(20,4,1,NULL),(20,3,2,NULL),(20,5,3,NULL),(20,9,4,'.NET'),
(21,6,1,NULL),(21,1,2,NULL),(21,7,3,NULL),
(22,1,1,NULL),(22,2,2,NULL),(22,3,3,NULL),
(23,1,1,NULL),(23,3,3,NULL),(23,9,2,'ANDROID'),
(24,1,1,NULL),(24,7,2,NULL),(24,3,3,NULL),
(25,1,1,NULL),(25,4,2,NULL),(25,3,3,NULL),(25,9,4,'I HATE COMPUTING LANG BCOZ, MY MOM ONCE TOLD ME: GO TO THE GROCERY STORE, BRING 3 BOTTLES MILK, IF THEY HAVE EGGS BRING 6. I BROUGHT 6 BOTTLES MILK BECOZ THEY HAD EGGS!!!'),
(26,1,1,NULL),(26,7,2,NULL),(26,9,3,'.NET'),
(27,1,1,NULL),(27,5,2,NULL),(25,7,3,NULL),
(28,1,1,NULL),(28,8,2,NULL),(28,9,3,'.NET'),
(29,1,1,NULL),(29,3,2,NULL),(29,9,3,'.NET'),
(30,1,1,NULL),(30,3,2,NULL),(30,4,3,NULL),
(31,1,1,NULL),(31,3,2,NULL),(31,4,3,NULL),
(32,1,1,NULL),(32,3,2,NULL),(32,4,3,NULL),
(33,1,1,NULL),(33,3,2,NULL),(33,4,3,NULL),
(34,1,1,NULL),(34,2,2,NULL),
(35,5,1,NULL),(35,3,2,NULL),(35,4,3,NULL),
(36,1,1,NULL),(36,2,2,NULL),(36,9,3,'HTML'),
(37,1,1,NULL),(37,3,2,NULL),(37,9,3,'SQL'),
(38,1,1,NULL),(38,4,2,NULL),(38,9,3,'SQL'),
(39,1,1,NULL),(39,3,2,NULL),(39,4,3,NULL),
(40,1,1,NULL),(40,9,2,'.NET'),(40,4,3,NULL);


## Questions
1. What is the most frequently mentioned first language?
> SELECT LANGUAGE_ID, Count(*) FROM USERLANG GROUP BY LANGUAGE_ID ORDER BY LANGUAGE_ID; 
- Java


2. What are the counts of all languages at all ranks? List them using language names and rank names (low medium high)
> SELECT LANGUAGE_ID, SKILL_LEVEL, Count(*) FROM USERLANG GROUP BY LANGUAGE_ID, SKILL_LEVEL ORDER BY LANGUAGE_ID; 
- Java: High - 34, Medium - 1
- JavaScript: High - 1, Medium - 4
- C++: Medium - 22, Low - 4
- C: High - 1, Medium - 2, Low - 7
- C#: High - 1, Medium - 2, Low - 2
- Python: High - 1, Medium - 1, Low - 2
- PHP: High - 1, Medium - 3, Low - 3
- Cobol: Medium - 1
- Other: Medium - 3, Low - 7, Ulatr Low - 2


3. For each language mentioned as having skill(value) HIGH, identify the most frequently named MEDIUM language. If you prefer use more than one query but not more than two, 1.2., feel free to create a table of languages in step 1.


Creating table
> DROP TABLE IF EXISTS Question CASCADE;
> CREATE TABLE Question(	
	LANGUAGE_ID INT NOT NULL REFERENCES LANGUAGE (LANGUAGE_ID),
	SKILL_LEVEL INT NOT NULL REFERENCES SKILL (SKILL_ID),
	COUNT INT NOT NULL,
	Quest INT NOT NULL
);


Adding values from the question 2
> INSERT INTO Question(LANGUAGE_ID, SKILL_LEVEL, COUNT) 
  SELECT LANGUAGE_ID, SKILL_LEVEL, Count(*)
  FROM USERLANG GROUP BY LANGUAGE_ID, SKILL_LEVEL
   ORDER BY LANGUAGE_ID;


Adding Quest field by if it has skill value high. if yes then it is 1, if no it is 0.
> INSERT INTO Question (Quest) VALUES (1,1,0,1,1,1,1,0,0);


Get the count of language with condition of having high skill value
> SELECT LANGUAGE_ID, COUNT(*) FROM Question WHERE Quest = 1 AND SKILL_LEVEL = 2 ORDER BY LANGUAGE_ID;  
- JavaScript
