//php form
/*
if and else 
- double equal sign only could compare the value but not the type.
    need triple equal sign for that.

switch
- need the break; to loop all posibilities

constant
- no $ before constant
- they are global
- may not be redefiend or undefined
- constants cannot be object
- difine("PI",3.1415926,true) the third argument is case_sensitive
- define allows expressions as the value const cannot

function
- function name is not case_sensitive only if they are part of the object
- funcitons could be directly called. echo func(5,7)
- default parameter(only could be the last parameter)
    function func($some = "something"){}
    echo func()
- pass by reference 
        function deSomething(&$a,$b){
            $a+=$b
            return $a
        }
- function should not include echo statement

variable Scope
- globle declaration is inside of the function

include and include_once and require and require_once
- require is required to run, include will be fine. once means that
    it will only be included or required once
- include file could have .inc/.php ending 
- when including files with no php closing tag 

$this is important in php
classname::static_method_name would be used for static method


extending class 
- parent::__construct() call parent constructor





*/