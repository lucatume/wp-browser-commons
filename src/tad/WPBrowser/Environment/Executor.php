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
        ob_start();
        $return = exec($command, $output, $return_var);
        $output = array_merge(explode("\n", ob_get_clean()));

        return $return;
    }
}