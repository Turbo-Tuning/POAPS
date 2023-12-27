
# POAPS - PHP Object Agnostic Persistent Storage

Any objects created that extend the "POAPS" abstract class, will be saved automatically on save() or __destruct in an object-agnostic database (will start with SQLite through PDO).

The system should be smart enough to walk through variables in an object to find sub objects that must be stored. When recovering the main object, the solution will also recover these sub objects. Hence the process is to work from top to bottom.

# Database storage design

1. An "Objects" table with the following fields:

| Id | parentId | objName | objType |
| --- | --- | --- | --- |

(for sub-objects; if it is a top-level object then this field is equal to 0)
(name given to the object - variable name)
(class name)

3. A "Variables" table:

| Id | objId | varType | varName | varData |
| --- | --- | ---| --- | --- |
(type of variable: boolean, integer, string, object, etc)
(name of the variable)
(data of the variable)


Imagine the case of an object (Person) with 3 variables (Name, Age and Offspring - array consisting of Person objects). Peter is 35 and has 1 child, Michelle who is 13. The "Objects" table would have 2 rows:
a. "Objects" table

| Id | parentId | objType |
| --- | --- |--- |
| 1 | 0 | Person |
| 2 | 1 | Person |



b. "Variables" table
| Id | objId | varType | varName | varData |
| --- | --- | --- | --- | --- |
| 1 | 1 | "string" | "name" | "Peter" |
| 2 | 1 | "integer" | "age" | 35 |
| 3 | 1 | "array" | "offspring" | child => 2 |
| 4 | 2 | "string" | "name" | "Michelle" |
| 5 | 2 | "integer" | "age" | 13 |
When reading the first Person object, POAPS will create the object, populate the data and return the object.

