<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\Process\Process;
use Exception, Log;

class DeployController extends Controller
{
    public function deploy(Request $request)
	{
		try {
			$githubPayload = $request->getContent();
		    $githubHash = $request->header('X-Hub-Signature') ?? "tes";
		    $localToken = config('app.deploy_secret');

		    $localHash = 'sha1=' . hash_hmac('sha1', $githubPayload, $localToken, false);

		    Log::info("Github hash: $githubHash, LocalHas: $localHash");

		    if (hash_equals($githubHash, $localHash)) {
		        $root_path = base_path();
		        $process = new Process('cd ' . $root_path . '; ./deploy.sh');
		        $process->run(function ($type, $buffer) {
		             echo $buffer;
		        });
		    }

		    return response()->json(['message' => "ok" ]);
		} catch (Exception $e) {
			
			Log::error($e->getMessage().' in '.$e->getFile().' at '.$e->getLine());
            return response()->json(['message' => $e->getMessage() ], $e->getCode() >  300 ?  $e->getCode() : 500 );
		}
	     
	}
}