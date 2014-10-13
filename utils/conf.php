<?php
namespace Json\Bench;

/**
 * Config class
 */
class Conf
{
    /**
     * Default generator executable
     */
    const DEFAULT_GENERATOR = 'jsogen';

    /**
     * Config array
     *
     * @var array
     */
    protected $conf;

    /**
     * Template directory
     *
     * @var string
     */
    protected $templateDir;

    /**
     * Output directory
     *
     * @var string
     */
    protected $outputDir;

    /**
     * Action name
     *
     * @var string
     */
    protected $action;

    /**
     * Force flag
     *
     * @var boolean
     */
    protected $force = false;

    /**
     * Whitelist of directory paths
     *
     * @var mixed
     */
    protected $whiteList = false;

    /**
     * Constructor
     *
     * @param string $confFile
     */
    public function __construct($argv, $confFile, $templateDir, $outputDir) {
        $this->conf = json_decode(file_get_contents($confFile), true);
        $this->templateDir = $templateDir;
        $this->outputDir = $outputDir;
        $this->processArguments($argv);
    }

    /**
     * Magic getter
     *
     * @param string $name
     *
     * @return mixed
     */
    public function __get($name) {
        switch ($name) {
            case 'generator':
                return $this->getGenerator();
            case 'sizes':
                return $this->getSizes();
            case 'td':
                return $this->templateDir;
            case 'od':
                return $this->outputDir;
            case 'wl':
                return $this->whiteList;
        }
        return null;
    }

    /**
     * Get generator executable
     *
     * @return string
     */
    public function getGenerator() {
        return isset($this->conf['generator']) ? $this->conf['generator'] : self::DEFAULT_GENERATOR;
    }

    /**
     * Get template directory
     *
     * @return string
     */
    public function getOutputDir() {
        return $this->outputDir;
    }

    /**
     * Get sizes
     *
     * @return array
     */
    public function getSizes() {
        return isset($this->conf['sizes']) ? $this->conf['sizes'] : array();
    }

    /**
     * Get template directory
     *
     * @return string
     */
    public function getTemplateDir() {
        return $this->templateDir;
    }

    /**
     * Get template directory
     *
     * @return string
     */
    public function getWhiteList() {
        return $this->whiteList;
    }

    /**
     * Whether bench-marking should be executed
     *
     * @return boolean
     */
    public function isBench() {
        return $this->action === 'bench';
    }

    /**
     * Whether generating should be executed
     *
     * @return booleam
     */
    public function isGen() {
        return $this->action === 'gen';
    }

    /**
     * Whether force flag set
     *
     * @return boolean
     */
    public function isForce() {
        return $this->force;
    }

    /**
     * Whether the path is white listed
     *
     * @param string  $path       Path
     * @param boolean $isTemplate Whether to check template
     *
     * @return boolean
     */
    public function isWhiteListed($path, $isTemplate) {
        $whiteList = $this->getWhiteList();
        if (empty($whiteList)) {
            return true;
        }
        $dirLength = strlen($isTemplate ? $this->templateDir : $this->outputDir);
        foreach ($whiteList as $allowedPath) {
            if (strpos($path, $allowedPath, $dirLength) === $dirLength) {
                return true;
            }
        }

        return false;
    }

    /**
     * Process argument
     *
     * @param array $argv
     */
    protected function processArguments($argv) {
        if (count($argv) < 2) {
            $this->action = 'bench';
            return;
        }
        switch ($argv[1]) {
            case 'bench':
            case 'gen':
                $this->action = $argv[1];
                break;
            default:
                throw new \Exception("Unknown action {$argv[1]}");
        }
        if (isset($argv[2]) && ($argv[2] === '-f' || $argv[2] === '--force')) {
            $this->force = true;
            $offset = 3;
        } else {
            $offset = 2;
        }
        if (count($argv) < 3) {
            return;
        }
        $this->whiteList = array_splice($argv, $offset);
    }
}
