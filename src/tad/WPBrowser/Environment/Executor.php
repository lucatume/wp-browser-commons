<?php

namespace tad\WPBrowser\Environment;

/**
 * Class Executor
 *
 * Handles execution of stand-alone processes.
 *
 * @package tad\WPBrowser\Environment
 */
class Executor
{

    /**
     * @var string
     */
    protected $sectionPrefix;

    /**
     * Executor constructor.
     * @param string $sectionPrefix
     */
    public function __construct($sectionPrefix = '')
    {
        $this->sectionPrefix = $sectionPrefix;
    }

    /**
     * Wraps the `exec` functions with some added debug information.
     *
     * @see exec()
     *
     * @param string $command
     * @param array $output
     * @param int $return_var
     *
     * @return int string
     */
    public function exec($command, array &$output = null, &$return_var = null)
    {
        $prefix = empty($this->sectionPrefix) ? '' : '[' . $this->sectionPrefix . '] ';

        codecept_debug($prefix . 'command', $command);
        $return = exec($command, $output, $return_var);
        codecept_debug($prefix . 'output', $output);

        return $return;
    }
}