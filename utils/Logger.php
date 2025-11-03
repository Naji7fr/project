<?php

/**
 * Logger Class
 * 
 * Handles error logging and debugging for the application
 * following PSR-12 coding standards
 * 
 * @package Sneakerness\Utils
 * @author  Development Team
 * @version 1.0.0
 */

require_once __DIR__ . '/../config/config.php';

class Logger
{
    /**
     * Log levels
     */
    const LEVEL_ERROR = 'ERROR';
    const LEVEL_WARNING = 'WARNING';
    const LEVEL_INFO = 'INFO';
    const LEVEL_DEBUG = 'DEBUG';

    /**
     * Log file path
     * 
     * @var string
     */
    private static $logFile;

    /**
     * Initialize logger
     */
    public static function init()
    {
        $logDir = ROOT_PATH . '/logs';
        
        // Create logs directory if it doesn't exist
        if (!is_dir($logDir)) {
            mkdir($logDir, 0755, true);
        }
        
        self::$logFile = $logDir . '/app_' . date('Y-m-d') . '.log';
    }

    /**
     * Log an error message
     * 
     * @param string $message Error message
     * @param array $context Additional context data
     * @param Exception|null $exception Exception object if available
     */
    public static function error($message, $context = [], $exception = null)
    {
        self::log(self::LEVEL_ERROR, $message, $context, $exception);
    }

    /**
     * Log a warning message
     * 
     * @param string $message Warning message
     * @param array $context Additional context data
     */
    public static function warning($message, $context = [])
    {
        self::log(self::LEVEL_WARNING, $message, $context);
    }

    /**
     * Log an info message
     * 
     * @param string $message Info message
     * @param array $context Additional context data
     */
    public static function info($message, $context = [])
    {
        self::log(self::LEVEL_INFO, $message, $context);
    }

    /**
     * Log a debug message (only in development)
     * 
     * @param string $message Debug message
     * @param array $context Additional context data
     */
    public static function debug($message, $context = [])
    {
        if (APP_ENV === 'development') {
            self::log(self::LEVEL_DEBUG, $message, $context);
        }
    }

    /**
     * Main logging method
     * 
     * @param string $level Log level
     * @param string $message Log message
     * @param array $context Additional context data
     * @param Exception|null $exception Exception object if available
     */
    private static function log($level, $message, $context = [], $exception = null)
    {
        try {
            if (!self::$logFile) {
                self::init();
            }

            $timestamp = date('Y-m-d H:i:s');
            $requestId = self::getRequestId();
            
            // Build log entry
            $logEntry = [
                'timestamp' => $timestamp,
                'level' => $level,
                'request_id' => $requestId,
                'message' => $message,
                'context' => $context,
                'user_ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown',
                'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'unknown',
                'request_uri' => $_SERVER['REQUEST_URI'] ?? 'unknown'
            ];

            // Add exception details if provided
            if ($exception) {
                $logEntry['exception'] = [
                    'type' => get_class($exception),
                    'message' => $exception->getMessage(),
                    'file' => $exception->getFile(),
                    'line' => $exception->getLine(),
                    'trace' => $exception->getTraceAsString()
                ];
            }

            // Format log entry
            $formattedEntry = sprintf(
                "[%s] %s [%s] %s %s\n",
                $timestamp,
                $level,
                $requestId,
                $message,
                !empty($context) || $exception ? json_encode($logEntry, JSON_UNESCAPED_SLASHES) : ''
            );

            // Write to log file
            file_put_contents(self::$logFile, $formattedEntry, FILE_APPEND | LOCK_EX);

            // Also log to PHP error log in development
            if (APP_ENV === 'development') {
                error_log($formattedEntry);
            }

        } catch (Exception $e) {
            // Fallback to PHP error log if our logging fails
            error_log("Logger failed: " . $e->getMessage() . " | Original message: " . $message);
        }
    }

    /**
     * Generate unique request ID for tracking
     * 
     * @return string
     */
    private static function getRequestId()
    {
        static $requestId = null;
        
        if ($requestId === null) {
            $requestId = substr(uniqid(), -8);
        }
        
        return $requestId;
    }

    /**
     * Get recent log entries
     * 
     * @param int $lines Number of lines to retrieve
     * @return array
     */
    public static function getRecentLogs($lines = 50)
    {
        try {
            if (!self::$logFile || !file_exists(self::$logFile)) {
                return [];
            }

            $content = file_get_contents(self::$logFile);
            $logLines = explode("\n", $content);
            $logLines = array_filter($logLines); // Remove empty lines
            
            return array_slice($logLines, -$lines);

        } catch (Exception $e) {
            error_log("Failed to retrieve logs: " . $e->getMessage());
            return [];
        }
    }
}
