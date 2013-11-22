<?php

/**
 * Configuration class.
 *
 * PHP version 5
 *
 * @category  DiffSniffer
 * @package   DiffSniffer
 * @author    Sergei Morozov <morozov@tut.by>
 * @copyright 2013 Sergei Morozov
 * @license   http://mit-license.org/ MIT Licence
 * @link      http://github.com/morozov/diff-sniffer
 */
namespace DiffSniffer;

/**
 * Configuration class.
 *
 * PHP version 5
 *
 * @category  DiffSniffer
 * @package   DiffSniffer
 * @author    Sergei Morozov <morozov@tut.by>
 * @copyright 2012 Sergei Morozov
 * @license   http://mit-license.org/ MIT Licence
 * @link      http://github.com/morozov/diff-sniffer
 */
class Config
{
    /**
     * Checks if configuration is defined.
     *
     * @return array
     * @throws \LogicException
     */
    public function isDefined()
    {
        $path = $this->getConfigPath();

        return file_exists($path);
    }

    /**
     * Returns configuration parameters or throws exception in case if configuration
     * is not defined.
     *
     * @return array
     * @throws \LogicException
     */
    public function getParams()
    {
        if (!$this->isDefined()) {
            throw new \LogicException('Configuration is not defined');
        }

        return include $this->getConfigPath();
    }

    /**
     * Stores configuration parameters
     *
     * @param array $params
     */
    public function setParams(array $params)
    {
        $path = $this->getConfigPath();

        $params = var_export($params, true);
        $contents = <<<EOF
<?php

return $params;

EOF;

        $dir_name = dirname($path);
        if (!file_exists($dir_name)) {
            mkdir($dir_name, 0777, true);
        }

        file_put_contents($path, $contents);
    }

    /**
     * Returns application directory where the configuration data should be stored.
     *
     * @return string
     * @throws \RuntimeException
     */
    protected function getAppDir()
    {
        $isWindows = defined('PHP_WINDOWS_VERSION_MAJOR');

        if ($isWindows) {
            if (!getenv('APPDATA')) {
                throw new \RuntimeException('The APPDATA environment variable must be set to run correctly');
            }
            $dir = strtr(getenv('APPDATA'), '/', DIRECTORY_SEPARATOR);
        } else {
            if (!getenv('HOME')) {
                throw new \RuntimeException('The HOME environment variable must be set to run correctly');
            }
            $dir = getenv('HOME');
        }

        $dir = rtrim($dir, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;

        if ($isWindows) {
            $dir .= 'DiffSniffer';
        } else {
            $dir .= '.diff-sniffer';
        }

        return $dir;
    }

    /**
     * Returns configuration file path.
     *
     * @return string
     */
    protected function getConfigPath()
    {
        return $this->getAppDir()
            . DIRECTORY_SEPARATOR . 'config.php';
    }
}
