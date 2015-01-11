# Summary for the report 0001

## Runs

The runs for this report can be found [here](runs.md).

### Source Code

The `json` is an original (non-free) PHP 7 json extension at commit bukka/php-src@dbd02ad23bfbe0b0dc703c68774e397fc0a8b1ef

The `jsond` is an implementation based on jsond for PHP 7 at commit bukka/php-src@e6fb493e5dbafdad37ba5334c986636342b5d9aa

### Description

The results are split between encoding and decoding. The runs are indented by levels. The levels mirror the structure of the [template directory](/templates)

There are following levels:
- top: The average of all sizes which doesn't give a lot of information as each size has different number of loops. However it gives a general idea about the speed. 
- size: Either tiny, small or medium. It splits the instances by the size and allows that all instances on the lower level run the same number of loops which gives a quite useful info about the total perf. The number of loops can be found in the [conf](/conf/bench.json) 
- type: Either float, int, string or mixed. The type is a data type that is used for all values for the levels bellow. It's very useful for testing changes for specific data type.
- organization: Either array, arrobj, object or scalar. The organization is a data structure(s) contained in the instance.
- name: The name of the instance that depicts a subtype (usually contained data range) and depth. It is equal to the template name that the instance is generated from.
- idx: The index is just an instance number generated from the same template with different seed. 

The value for each run is an average of all values on the lower level. The lowest level ( the most indent results ) is an idx level. That value is a measured time for the number of size loops.

#### Correctness

It's clear that pure average of the lower levels doesn't give complete information about the results and can be sometimes a bit misleading. 

However it gives quite clearly information about the result in general. It also is the first version of this tool so a complete statistical analysis is out of the scope at the moment. The improvements in this area are planned for the later stages of development.


## Results

### Encoding

The internal functions for encoding have been moved to the file `json_encode.c` to organize the code mroe logically. This has no influence on the performance.
 
The only implementation change is using `php_gcvt` with a buffered double conversion instead of using `spprintf` . The code has also been moved to one inline function just to prevent code duplication.

That improves encoding for float types about 25% and mixed instances (where also is float) over 20% more or less for all sizes.

The string and int instances are in total averages more or less the same which is expected as there are no changes. 

It's interesting too see slight difference for string and integers on the lower levels. The reason for that might be slightly influenced by the computer state when running the instance (other running processes). However the average shows the expected results at the end.

### Decoding

The decoder has been completely rewritten - it's a new parser using bison and re2c. It means that LR is used instead of LL.

Even the current implementation has not been fully optimized, it gives already quite interesting results.


#### Performance decrease

Let's start with the instances that give worse result than the current core json extension. 

These instances are highly repeating arrays or objects with very short keys. The depth must be greater than 2. The value has to be either short integer and string with less than 10 characters. The point here is that there are lots arrays or objects compare to number of values.

For such cases the decrease is about 30% and for some very special cases up to 40%.

The good news is that these instances are not probably so usual in the real life apps. There also is a room for improvements and I believe that the numbers could be decreased.

#### Performances improvement

As soon as it comes on slightly bigger strings (20+ characters) then it's visible where jsond excels. The bigger the string is, the faster the parse is. It means that for strings about 100 characters it's about 30% faster and for string about 2000 characters it's often 2.5 times faster.
 
It gets even better for unicode where there are case that are almost 4 times faster. The reason for that is probably the scanner that limit number of allocation and unicode validation also seems faster.

There also is improvement for float numbers that are about 18% faster.

In general, the jsond should be much faster for the most real life apps. It also gives better total results for higher levels of the runs in this test. 