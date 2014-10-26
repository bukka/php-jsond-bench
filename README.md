# PHP JSON benchmark

This is a benchmark for PHP json and jsond extension. The JSON instances are generated from templates in the [template/](templates/) directory using [jsogen](https://github.com/bukka/jsogen) tool.

## Console

The console is a script in the [util/](util/) directory. The main configuration for the script is [conf/bench.json](conf/bench.json).

The script can be run from the shell as

```
$ php util/console.php command [whiteList]
```

where `command` must be one of the following:

* `gen` - generates instances to the new `output` directory
* `bench` - runs benchmarks in the `output` directory

The option `whiteList` specifies list of allowed directories that will be processed (generated/benchmarked). If it's not supplied, all directories will be processed.
