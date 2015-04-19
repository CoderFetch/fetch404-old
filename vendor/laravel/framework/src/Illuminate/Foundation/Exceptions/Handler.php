<?php namespace Illuminate\Foundation\Exceptions;

use Exception;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Console\Application as ConsoleApplication;
use Symfony\Component\Debug\ExceptionHandler as SymfonyDisplayer;
use Illuminate\Contracts\Debug\ExceptionHandler as ExceptionHandlerContract;

use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Schema;
class Handler implements ExceptionHandlerContract {

	/**
	 * The log implementation.
	 *
	 * @var \Psr\Log\LoggerInterface
	 */
	protected $log;

	/**
	 * A list of the exception types that should not be reported.
	 *
	 * @var array
	 */
	protected $dontReport = [];

	/**
	 * Create a new exception handler instance.
	 *
	 * @param  \Psr\Log\LoggerInterface  $log
	 * @return void
	 */
	public function __construct(LoggerInterface $log)
	{
		$this->log = $log;
	}

	/**
	 * Report or log an exception.
	 *
	 * @param  \Exception  $e
	 * @return void
	 */
	public function report(Exception $e)
	{
		if ($this->shouldntReport($e)) return;

		$this->log->error((string) $e);
	}

	/**
	 * Determine if the exception should be reported.
	 *
	 * @param  \Exception  $e
	 * @return bool
	 */
	public function shouldReport(Exception $e)
	{
		return ! $this->shouldntReport($e);
	}

	/**
	 * Determine if the exception is in the "do not report" list.
	 *
	 * @param  \Exception  $e
	 * @return bool
	 */
	protected function shouldntReport(Exception $e)
	{
		foreach ($this->dontReport as $type)
		{
			if ($e instanceof $type) return true;
		}

		return false;
	}

	/**
	 * Render an exception into a response.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Exception  $e
	 * @return \Illuminate\Http\Response
	 */
	public function render($request, Exception $e)
	{
		if ($this->isHttpException($e))
		{
			return $this->renderHttpException($e);
		}
		else
		{
			return (new SymfonyDisplayer(config('app.debug')))->createResponse($e);
		}
	}

	/**
	 * Render an exception to the console.
	 *
	 * @param  \Symfony\Component\Console\Output\OutputInterface  $output
	 * @param  \Exception  $e
	 * @return void
	 */
	public function renderForConsole($output, Exception $e)
	{
		(new ConsoleApplication)->renderException($e, $output);
	}

	/**
	 * Render the given HttpException.
	 *
	 * @param  \Symfony\Component\HttpKernel\Exception\HttpException  $e
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	protected function renderHttpException(HttpException $e)
	{
		$status = $e->getStatusCode();

		if (DB::connection()->getDatabaseName())
		{
			if (Schema::hasTable('migrations')) //Migrations table exists
			{
				if (view()->exists("core.errors.{$status}"))
				{
					return response()->view("core.errors.{$status}", [], $status);
				}
				else
				{
					return (new SymfonyDisplayer(config('app.debug')))->createResponse($e);
				}
			} else {
				// Go to the install page, we haven't installed yet.
				return redirect('/');
			}
		} else {
			return redirect('/');
		}
	}

	/**
	 * Determine if the given exception is an HTTP exception.
	 *
	 * @param  \Exception  $e
	 * @return bool
	 */
	protected function isHttpException(Exception $e)
	{
		return $e instanceof HttpException;
	}

}
