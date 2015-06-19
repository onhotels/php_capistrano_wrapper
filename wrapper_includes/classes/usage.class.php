<?php
/**
*
* Essential class for wrapper
* @category Deployment
* @package Deployment
* @author Andrea Cannuni <donot@disturb.com>
* @copyright Copyright (c) 2015, OnHotels.com
* @title usage.class.php
* @date 20150616
* @version GIT: 1.0
* @example wrap --help
* @link http://www.onhotels.com
* PHP version 5.6.9 (cli)
*/

class Usage
{
    const CONFIG_FILES_FOLDER = 'wrapper_configs/';
    private $verbose = false;
    private $simulate = false;
    private $file_path = self::CONFIG_FILES_FOLDER . 'default.json';
    
    /**
     * Parse script arguments
     */
    public function readArgs()
    {
        global $argc, $argv;

        if ($argc == 2) {
            for ($i = 1; $i < $argc; $i++) {
                /* Exclusive arguments */
                if ($this->startsWith($argv[$i], "--help") || $this->startsWith($argv[$i], "-h")) {
                    $this->executeHelp();
                } 
                if($this->startsWith($argv[$i], "--version") || $this->startsWith($argv[$i], "-V")) {
                    $this->executeVersion();
                }
                /* Exclusive arguments EoF */
                /* Non-exclusive arguments */
                if ($this->startsWith($argv[$i], "--verbose") || $this->startsWith($argv[$i], "-v")) {
                    $this->verbose = true;
                }
                if ($this->startsWith($argv[$i], "--simulate") || $this->startsWith($argv[$i], "-s")) {
                    $this->simulate = true;
                }
                if ($this->startsWith($argv[$i], "--config=")) {
                    $config_file_array = explode("=", $argv[$i]);
                    $this->file_path = self::CONFIG_FILES_FOLDER . $config_file_array[1];
                }
                /* Non-exclusive arguments EoF */
            }
        } elseif ($argc > 2) {
            /* Check if there are more than one exclusive arguments */
            for ($i = 1; $i < $argc; $i++) {
                if (($this->startsWith($argv[$i], "--help") || $this->startsWith($argv[$i], "-h")) || ($this->startsWith($argv[$i], "--version") || $this->startsWith($argv[$i], "-V"))) {
                    $this->executeError();
                }
            }

            for ($i = 1; $i < $argc; $i++) {
                /* Non-exclusive arguments */
                if ($this->startsWith($argv[$i], "--verbose") || $this->startsWith($argv[$i], "-v")) {
                    $this->verbose = true;
                }
                if ($this->startsWith($argv[$i], "--simulate") || $this->startsWith($argv[$i], "-s")) {
                    $this->simulate = true;
                }
                if ($this->startsWith($argv[$i], "--config=")) {
                    $config_file_array = explode("=", $argv[$i]);
                    $this->file_path = self::CONFIG_FILES_FOLDER . $config_file_array[1];
                }
                /* Non-exclusive arguments EoF */
            }
        }
        return $this->executeConfig();
    }

    /** 
     * Check if alternative config file has been selected and if config file exists
     */
    private function executeConfig()
    {
        if (file_exists($this->file_path)) {
            $config_file = $this->file_path;
            return $config_file;
        } else {
            echo "[Error] Config file doesn't exists.\n";
            return false;
        }
    }

    /**
     * Print help menu and script usage instructions 
    */
    private function executeHelp()
    {
        global $argv;

        echo "Usage: ". $argv[0] ." [--help | --version] | [-v | verbose ] | [--config=existing_configuration_filename.json [-v | --verbose ]].\n";
        echo "\n";
        echo "DESCRIPTION\n";
        echo "This script will execute Capistrano tasks and start and stop services.\n";
        echo "\n";
        echo "COMMANDS\n";
        echo "-h\t--help\t\t\tThis help menu\n";
        echo "-s\t--simulate\t\tPrint the tasks to be executed without executing them\n";
        echo "-v\t--verbose\t\tPrint debug informartion while running the commands\n";
        echo "-V\t--version\t\tPrint the version of the script\n";
        exit;
    }

    /*
     * Print the version of the script
     */
    private function executeVersion()
    {
        echo "deployment v.1.0 by Andrea Cannuni for OnHotels.com.\n";
        exit;
    }

    /** 
     *Execute if error occurs
     */
    private function executeError()
    {
        echo "[ERROR] Wrong script arguments.\n";
        $this->executeHelp();
    }

    /** 
     * Execute commands
     */
    public function executeCommand($command) {
        if ($this->simulate) {
            echo "[SIMULATE] ". $command ."\n";
        } else {
            system($command, $retval);
            if($retval != 0) {
                echo "[ERROR] Script failed and next commands will NOT be executed.\n";
                exit;
            }
        }
        if ($this->verbose) {
            echo "[INFO] ". $command ."\n";
        }
    }

    protected function startsWith($haystack, $needle) {
        return $needle === "" || strrpos($haystack, $needle, -strlen($haystack)) !== FALSE;
    }
}
?>