<?php
namespace App\Http\Middleware;
use Log;
use Closure;

class AfterRequest
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        // CPU LOAD
        $loads = sys_getloadavg();
        $cores = trim(shell_exec("grep -P '^physical id' /proc/cpuinfo|wc -l"));
        $load = $loads[0] / $cores;

        Log::info('CPU LOAD: '.$load, [
            'user' => $request->user() ? $request->user()->email : null,
            'method' => $request->method(),
            'url' => $request->fullUrl()
        ]);

        return $response;
    }
}
