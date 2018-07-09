<?php

/**
 * Guzzle Request Logger - A simple request logger for Guzzle
 *
 * @license http://opensource.org/licenses/MIT
 * @link https://github.com/caseyamcl/guzzle_request_logger
 * @version 1.0
 * @package caseyamcl/guzzle_request_logger
 * @author Casey McLaughlin <caseyamcl@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * ------------------------------------------------------------------
 */

namespace CaseyAMcL\GuzzleRequestLogger;

use GuzzleHttp\Promise\PromiseInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;

/**
 * HTTP Request Logger
 *
 * Simple class for logging HTTP requests with Guzzle.  This is useful
 * for debugging or auditing.
 *
 * @package CaseyAMcL\GuzzleRequestLogger
 */
class GuzzleRequestLogger
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var bool
     */
    private $multiLine;

    /**
     * @var bool
     */
    private $implodeMultiLine;

    /**
     * @var string
     */
    private $logLevel;

    /**
     * RequestLogger constructor.
     *
     * @param LoggerInterface $logger            A PSR-3 logger (e.g. Monolog, etc)
     * @param bool            $multiLine         If TRUE, prints detailed logging output, including headers and body
     * @param bool            $implodeMultiLine  If TRUE, detailed logging output will be imploded to one line
     * @param string          $logLevel          Defaults to \Psr\LogLogLevel::INFO
     */
    public function __construct(
        LoggerInterface $logger,
        bool $multiLine = true,
        bool $implodeMultiLine = false,
        string $logLevel = LogLevel::INFO
    ) {
        $this->logger = $logger;
        $this->multiLine = $multiLine;
        $this->implodeMultiLine = $implodeMultiLine;
        $this->logLevel = $logLevel;
    }

    /**
     * Log a request
     *
     * @param RequestInterface $request
     */
    public function logRequest(RequestInterface $request): void
    {
        $messageLines = [sprintf('---> REQUEST: %s %s', $request->getMethod(), $request->getUri())];

        if ($this->multiLine) {
            foreach ($request->getHeaders() as $headerName => $headerVals) {
                foreach ($headerVals as $headerVal) {
                    $messageLines[] = sprintf(' Header -> %s: %s', $headerName, $headerVal);
                }
            }

            if ($request->getBody()->isSeekable()) {
                if ($body = $request->getBody()->getContents()) {
                    $messageLines[] = ' Body:' . PHP_EOL . $body . PHP_EOL;
                }
            }
            else {
                $messageLines[] = ' Body: (streamed content)';
            }
        }

        $this->doLog($messageLines);
    }

    /**
     * Log a response
     *
     * @param ResponseInterface $response
     */
    public function logResponse(ResponseInterface $response): void
    {
        $messageLines = [sprintf('<--- RESPONSE: %s', $response->getStatusCode())];

        if ($this->multiLine) {
            foreach ($response->getHeaders() as $headerName => $headerVals) {
                foreach ($headerVals as $headerVal) {
                    $messageLines[] = sprintf(' Header -> %s: %s', $headerName, $headerVal);
                }
            }

            // Read the first 1024 bytes from the string and truncate the rest.
            $responseBody = $response->getBody();

            if ($responseBody->isSeekable()) {
                $responseString = ($responseBody->getSize() > 1024)
                    ? $responseBody->read(1024) . '...(truncated)'
                    : $responseBody->__toString();
                $response->getBody()->rewind();

                $messageLines[]  = ' Body: ' . PHP_EOL . $responseString . PHP_EOL;
            }
            else {
                $messageLines[] = ' Body: (streamed content)';
            }
        }

        $this->doLog($messageLines);
    }

    /**
     * Invokable method for registering with Guzzle HandlerStack
     *
     * This is useful if you're using Guzzle.  It provides quick syntax for registering with Guzzle's HandlerStack:
     *
     * $handlerStack->push(new GuzzleRequestLogger($somePsr3Logger));
     *
     * @param callable $handler
     * @return \Closure
     */
    public function __invoke(callable $handler): callable
    {
        return function (RequestInterface $request, array $options) use ($handler) {
            $this->logRequest($request);
            /** @var PromiseInterface $promise */
            $promise = $handler($request, $options);
            return $promise->then(function (ResponseInterface $response) {
                $this->logResponse($response);
                return $response;
            });
        };
    }

    /**
     * Write the log message to the logger
     *
     * @param array $lines
     */
    private function doLog(array $lines): void
    {
        if ($this->implodeMultiLine) {
            $this->logger->log($this->logLevel, implode(PHP_EOL, $lines));
        } else {
            foreach ($lines as $line) {
                $this->logger->log($this->logLevel, $line);
            }
        }
    }
}
