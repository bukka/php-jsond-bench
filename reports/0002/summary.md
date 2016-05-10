# Summary for the report 0002

This report compares a new json parser method implementation. It is just PHP core json extension.

## Runs

The runs for this report can be found [here](runs.md).

### Source Code

The `function` is a current json implementation with the usual function calls. The commit that was benchmarked: https://github.com/bukka/php-src/commit/ee551106bc11d6ac2eb277b810c9b8bb90e318bc

The `pointer` is a method based implementation with pointer to the method structure. The commit that was benchmarked: https://github.com/bukka/php-src/commit/e6fb493e5dbafdad37ba5334c986636342b5d9aa

The `embed` is a method based implementation with embedded (nested) method structure in `json_parser`. The commit that was benchmarked: https://github.com/bukka/php-src/commit/e6fb493e5dbafdad37ba5334c986636342b5d9aa

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

It's clear that pure average of the lower levels doesn't give a complete information about the results and can be sometimes misleading.

However it gives quite clearly an information about the result in general.

## Results

The results are just for decoding as encoding has not been changed.

### Decoding

It is visible from the results that `pointer` couses some slow down due to extra dereferencing. However `embed` causes no slow down compare to `function` and thus it is more suitable implementation.