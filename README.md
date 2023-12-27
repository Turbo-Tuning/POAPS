# POAPS - PHP Object Agnostic Persistent Storage

Any objects created that extend the "POAPS" abstract class, will be saved automatically on save() or __destruct in an object-agnostic database (will start with SQLite through PDO).

The system should be smart enough to walk through variables in an object to find sub objects that must be stored. When recovering the main object, the solution will also recover these sub objects. Hence the process is to work from top to bottom.

# Database storage design

1. An "Objects" table with the following fields:
Id
parentId (for sub-objects; if it is a top-level object then this field is equal to 0)
objName (name given to the object - variable name)
objType (class name)

2. A "Variables" table:
Id
objId
varType (type of variable: boolean, integer, string, object, etc)
varName (name of the variable)
varData (data of the variable)

Imagine the case of an object (Person) with 3 variables (Name, Age and Offspring - array consisting of Person objects). Peter is 35 and has 1 child, Michelle who is 13. The "Objects" table would have 2 rows:
a. "Objects" table
Id = 1
parentId = 0
objType = Person  

Id = 2
parentId = 1
objType = Person

b. "Variables" table
Id = 1
objId = 1
varType = "string"
varName = "name"
varData = "Peter"

Id = 2
objId = 1
varType = "integer"
varName = "age"
varData = 35

Id = 3
objId = 1
varType = "array"
varName = "Offspring"
varData = array(Child => 2) //pointing to the Id of the record in the "Objects" table

Id = 4
ObjId = 2
varType = "string"
varName = "name"
varData = "Michelle"

Id = 5
objId = 2
varType = "integer"
varName = "age"
varData = 13

When reading the first Person object, POAPS will create the object, populate the data and return the object.

