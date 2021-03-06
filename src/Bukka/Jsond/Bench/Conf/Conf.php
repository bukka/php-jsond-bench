<?php
namespace Bukka\Jsond\Bench\Conf;

use Bukka\Jsond\Bench\Exception\ConfException;

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
     * Default storage
     */
    const DEFAULT_STORAGE = 'file';

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
     * Bench directory
     *
     * @var string
     */
    protected $benchDir;

    /**
     * Storage directory
     *
     * @var string
     */
    protected $storageDir;

    /**
     * Storage
     *
     * @var \Bukka\Jsond\Bench\Storage\StorageInterface
     */
    protected $storage = null;

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
     * Parameters
     *
     * @var array
     */
    protected $params = array();

    /**
     * Run actions
     *
     * @var array
     */
    protected $runActions = array('encode', 'decode');


    /**
     * Constructor
     *
     * @param string $confFile
     * @param string $templateDir
     * @param string $outputDir
     * @param string $benchDir
     * @param string $storageDir
     */
    public function __construct($confFile, $templateDir, $outputDir, $benchDir, $storageDir)
    {
        $this->initConf($confFile);
        $this->templateDir = $templateDir;
        $this->outputDir = $outputDir;
        $this->benchDir = $benchDir;
        $this->storageDir = $storageDir;
    }

    /**
     * Magic getter
     *
     * @param string $name
     *
     * @return mixed
     */
    public function __get($name)
    {
        switch ($name) {
            case 'generator':
                return $this->getGenerator();
            case 'storage':
                return $this->getStorage();
            case 'sizes':
                return $this->getSizes();
            case 'td':
                return $this->templateDir;
            case 'od':
                return $this->outputDir;
            case 'sd':
                return $this->storageDir;
            case 'bd':
                return $this->benchDir;
            case 'wl':
                return $this->whiteList;
        }
        return null;
    }

    /**
     * @param string $confFile
     */
    public function initConf($confFile)
    {
        $conf = json_decode(file_get_contents($confFile), true);

        // init sizes
        $defaultSizeSettings = array(
            'seed'  => 1,
            'count' => 1,
            'loops' => 100,
        );
        if (isset($conf['sizes']) && is_array($conf['sizes'])) {
            foreach ($conf['sizes'] as $sizeName => $sizeConf) {
                $changed = false;
                foreach ($defaultSizeSettings as $defaultSizeSettingName => $defaultSizeSettingValue) {
                    if (!isset($sizeConf[$defaultSizeSettingName])) {
                        $sizeConf[$defaultSizeSettingName] = $defaultSizeSettingValue;
                        $changed = true;
                    }
                }
                if ($changed) {
                    $conf['sizes'][$sizeName] = $sizeConf;
                }
            }
        } else {
            $conf['sizes'] = array();
        }

        $this->conf = $conf;
    }

    /**
     * Get generator executable
     *
     * @return string
     */
    public function getGenerator()
    {
        return isset($this->conf['generator']) ? $this->conf['generator'] : self::DEFAULT_GENERATOR;
    }

    /**
     * Get storage instance
     *
     * @return \Bukka\Jsond\Bench\Storage\StorageInterface
     *
     * @throws ConfException
     */
    public function getStorage()
    {
        if (!$this->storage) {
            $storageName = isset($this->conf['storage']) ? $this->conf['storage'] : self::DEFAULT_STORAGE;
            $storageClass = '\Bukka\Jsond\Bench\Storage\\' . ucfirst($storageName) . 'Storage';
            if (!class_exists($storageClass)) {
                throw new ConfException('Invalid storage class ' . $storageClass);
            }
            $this->storage = new $storageClass($this);
        }
        return $this->storage;
    }

    /**
     * Get storage directory
     *
     * @return string
     */
    public function getStorageDir()
    {
        return $this->storageDir;
    }

    /**
     * Get template directory
     *
     * @return string
     */
    public function getOutputDir()
    {
        return $this->outputDir;
    }

    /**
     * Get bench directory
     *
     * @return string
     */
    public function getBenchDir()
    {
        return $this->benchDir;
    }

    /**
     * Get sizes
     *
     * @return array
     */
    public function getSizes()
    {
        return $this->conf['sizes'];
    }

    /**
     * Get template directory
     *
     * @return string
     */
    public function getTemplateDir()
    {
        return $this->templateDir;
    }

    /**
     * Get template directory
     *
     * @return string
     */
    public function getWhiteList()
    {
        return $this->whiteList;
    }

    /**
     * Set template directory
     *
     * @param mixed $whiteList
     *
     * @return Conf
     *
     * @throws ConfException
     */
    public function setWhiteList($whiteList)
    {
        if (is_array($whiteList)) {
            $this->whiteList = $whiteList;
        } elseif (!empty($whiteList)) {
            // try predefined white lists
            $whiteListName = (string) $whiteList;
            if (!isset($this->conf['white-lists'][$whiteListName])) {
                throw new ConfException("White list $whiteListName not found in config file");
            }
            $this->whiteList = $this->conf['white-lists'][$whiteListName];
        }

        return $this;
    }

    /**
     * Whether bench-marking should be executed
     *
     * @return boolean
     */
    public function isBench()
    {
        return $this->action === 'bench';
    }

    /**
     * Whether generating should be executed
     *
     * @return boolean
     */
    public function isGen()
    {
        return $this->action === 'gen';
    }

    /**
     * Whether generating should be executed
     *
     * @return boolean
     */
    public function isTest()
    {
        return $this->action === 'test';
    }

    /**
     * Disable force flag
     *
     * @return Conf
     */
    public function disableForce()
    {
        $this->force = false;

        return $this;
    }

    /**
     * Enable force flag
     *
     * @return Conf
     */
    public function enableForce()
    {
        $this->force = true;

        return $this;
    }

    /**
     * Whether force flag set
     *
     * @return boolean
     */
    public function isForce()
    {
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
    public function isWhiteListed($path, $isTemplate)
    {
        $whiteList = $this->getWhiteList();
        if (empty($whiteList)) {
            return true;
        }
        $dir = $isTemplate ? $this->templateDir : $this->outputDir;
        $dirName = basename($dir);
        $dirLength = strlen($dir);
        foreach ($whiteList as $allowedPath) {
            $allowedDirLength = $dirLength;
            if (strpos($allowedPath, $dirName) === 0) {
                $allowedDirLength -= strlen($dirName) + 1;
            }
            if (strpos($path, $allowedPath, $allowedDirLength) === $allowedDirLength) {
                return true;
            }
        }

        return false;
    }

    /**
     * Set parameters
     *
     * @param array $params
     *
     * @return Conf
     */
    public function setParams(array $params)
    {
        $this->params = $params;

        return $this;
    }

    /**
     * Set parameter
     *
     * @param string $name
     * @param mixed  $value
     *
     * @return Conf
     */
    public function setParam($name, $value)
    {
        $this->params[$name] = $value;

        return $this;
    }

    /**
     * Get parameter
     *
     * @param string $name
     * @param mixed  $default
     *
     * @return mixed
     */
    public function getParam($name, $default = null)
    {
        return isset($this->params[$name]) ? $this->params[$name] : $default;
    }

    /**
     * Get run types
     *
     * @return array
     */
    public function getRunTypes()
    {
        $runTypes = array('json');
        if (extension_loaded('jsond')) {
            $runTypes[] = 'jsond';
        }
        return $runTypes;
    }

    /**
     * Set run action
     *
     * @param string $action
     * @throws ConfException
     */
    public function setRunAction($action)
    {
        if (!$action) {
            return;
        }

        if (in_array($action, array('e', 'enc', 'encode'))) {
            $this->runActions = array('encode');
        } elseif (in_array($action, array('d', 'dec', 'decode'))) {
            $this->runActions = array('decode');
        } else {
            throw new ConfException('Unknown action ' . $action);
        }
    }

    /**
     * Get run actions
     *
     * @return array
     */
    public function getRunActions()
    {
        return $this->runActions;
    }
}
