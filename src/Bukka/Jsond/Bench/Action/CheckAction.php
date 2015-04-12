<?php

namespace Bukka\Jsond\Bench\Action;

/**
 * File checking class
 */
class CheckAction extends AbstractFileAction
{
    /**
     * Results delimiter
     */
    const RESULTS_DELIMITER = ' ; ';

    /**
     * Execute check of file instance
     *
     * @param string $path
     * @param array  $sizeConf
     */
    protected function executeFile($path, $sizeConf)
    {
        $string = file_get_contents($path);
        // Decoding
        $decodeTestResult = $this->checkDecode($string);
        // Encoding
        $encodeTestResult = $this->checkEncode(json_decode($string));

        if ($this->conf->getParam('all') ||
                !$this->isCheckingResultSuccess($decodeTestResult) ||
                !$this->isCheckingResultSuccess($encodeTestResult)) {
            $this->writeln("FILE: " . $path);
            $this->writeln("DECODING: " . $decodeTestResult);
            $this->writeln("ENCODING: " . $encodeTestResult);
            $this->writeln();
        }
    }

    /**
     * Get message for checking
     *
     * @param string $ext
     *
     * @return string
     */
    protected function getCheckingResult($ext)
    {
        $lastErrorFn = $ext . "_last_error";
        $lastErrorCode = $lastErrorFn();
        if (!$lastErrorCode) {

            return $ext . ':S';
        }
        $lastErrorMsgFn = $lastErrorFn . '_msg';

        return sprintf("%s:E[ %d, %s ]", $ext, $lastErrorCode, $lastErrorMsgFn());
    }

    /**
     * Find out if the result is success
     *
     * @param string $resultString
     *
     * @return bool
     */
    protected function isCheckingResultSuccess($resultString)
    {
        $results = explode(self::RESULTS_DELIMITER, $resultString);
        foreach ($results as $result) {
            if (strpos($result, ':S') === false) {

                return false;
            }
        }

        return true;
    }

    /**
     * Check decoding
     *
     * @param string $string
     *
     * @return string
     *
     * @todo deep comparison
     */
    protected function checkDecode($string) {
        // check json decoding
        $json = json_decode($string);
        $jsonResult = $this->getCheckingResult('json');

        if (!function_exists('jsond_decode')) {

            return $jsonResult;
        }

        // check jsond decoding
        $jsond = jsond_decode($string);
        $jsondResult = $this->getCheckingResult('jsond');

        return $jsonResult . self::RESULTS_DELIMITER . $jsondResult;
    }

    /**
     * Check encoding
     *
     * @param string $object
     *
     * @return string
     */
    protected function checkEncode($object)
    {
        // check json encoding
        $json = json_encode($object);
        $jsonResult = $this->getCheckingResult('json');

        if (!function_exists('jsond_encode')) {

            return $jsonResult;
        }

        // check jsond encoding
        $jsond = jsond_encode($object);
        $jsondResult = $this->getCheckingResult('jsond');

        if ($json === $jsond ||
                !$this->isCheckingResultSuccess($jsonResult) ||
                !$this->isCheckingResultSuccess($jsondResult)) {

            return $jsonResult . self::RESULTS_DELIMITER . $jsondResult;
        }

        list($diffPos, $diffNear) = $this->findStringDifference($json, $jsond);

        return sprintf('json:N%sjsond:N --> len(%d:%d), diff(%d,"%s")',
            self::RESULTS_DELIMITER, strlen($json), strlen($jsond), $diffPos, $diffNear);
    }

    /**
     * Find string difference
     *
     * @param string $s1
     * @param string $s2
     * @param int    $len
     *
     * @return array
     */
    protected function findStringDifference($s1, $s2, $len = 10)
    {
        $count = min(strlen($s1), strlen($s2));
        for ($i = 0; $i < $count; $i++) {
            if ($s1[$i] !== $s2[$i]) {
                break;
            }
        }

        return array($i, substr($s1, max(0, $i - $len), min($len, $i)));
    }
}
