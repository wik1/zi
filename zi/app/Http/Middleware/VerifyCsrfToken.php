<?php

namespace App\Http\Middleware;

use Closure;
use Redirect;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as BaseVerifier;

class VerifyCsrfToken extends BaseVerifier

{
    /**

     * The URIs that should be excluded from CSRF verification.

     *

     * @var array

     */

    protected $except = [
        //
    ];

	public function handle($request, Closure $next)
    {
        if (
            $this->isReading($request) ||
            $this->runningUnitTests() ||
            $this->inExceptArray($request) ||
            $this->tokensMatch($request)
        ) {
            return $this->addCookieToResponse($request, $next($request));
        }

        //throw new TokenMismatchException;
        return Redirect::back()->withErrors('Sorry, we could not verify your request. Please try again.');
    }

}