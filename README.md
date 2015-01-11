# PHP JSON benchmark

This is a benchmark for PHP json and jsond extension. The JSON instances are generated from templates in the [template/](templates/) directory using [jsogen](https://github.com/bukka/jsogen) tool.

## Console

The console is a script that executes all commands. The main configuration is in [conf/bench.json](conf/bench.json).

The script can be run from the shell as

```
$ php console command [options] [args]
```

where `command` must be one of the following:

* `gen` - generates instances to the new `output` directory. The following `options` can be supplied:
  * `--force` - rewrites existing files in the output directory
* `run` - runs benchmarks in the `output` directory
* `check` - checks generated benchmarks instances if they are correctly parse and also print some info about them
* `view` - view results.

The option `args` specifies list of allowed directories that will be processed (generated/benchmarked) for `gen`, `run` and `check`. If it's not supplied, all directories will be processed.

The view `args` depicts the storage identifiers and possible aliases after colon. The example could be 

```
$ ./console view 20150110161259 20150110194350:jsond=json
```

The `json` run from `20150110194350` will be shown in the view as `jsond`

## Reports

The reports of the runs can be found in [reports directory](reports/reports.md)