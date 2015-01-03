<?php namespace Judge\Controllers;

use Illuminate\Support\Facades\Response;
use Illuminate\Support\Contracts\ArrayableInterface;

class ApiController extends BaseController
{
    /**
     * API function to get all SolutionTypes as an id:name pair
     */
    public function getSolutionStates()
    {
        return ApiController::formatJSend($this->solution_states->all()->toArray());
    }

    /**
     * public function to get all unclaimed solutions
     */
    public function show()
    {
        return ApiController::formatJSend(
            $this->solutions->judgeableForContest()
        );
    }

    /**
     * API function to claim and retrieve a problem
     */
    public function claim($id)
    {
        $user_id = Auth::user()->id;

        // Attempt to claim, returning an error if it occurs
        $solution = $this->solutions->find($id);
        if (!$solution->claim()) {
            App::abort(403, 'You cannot claim that solution');
        }

        // No one has claimed the file, so the current judge claims it.
        // we update the record. If the save failed we flash the error
        // and redirect to the judge index
        $solution->claiming_judge_id = $user_id;
        if (!$solution->save()) {
            App::abort(400, $solution->errors());
        }

        return ApiController::formatJSend(array('solution' => $solution->toArray()));
    }

    /**
     * Updates the status of a submission via the API
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id)
    {
        $s = $this->solutions->find($id);
        $judge_id = Auth::user()->id;

        // Check that this current judge has claimed the problem
        // Check validation on save, and report errors if any. There shouldn't be, but
        // malicious input could cause it.
        if ($s->ownedByCurrentUser()) {
            $s->solution_state_id = Input::get('solution_state_id');
            if (!$s->save()) {
                App::abort(400, $s->errors());
            }
        } else {
            App::abort(403, 'You are not the claiming judge for this problem any more');
        }

        return ApiController::formatJSend();
    }

    /**
     * API function to unclaim a problem
     */
    public function unclaim($id)
    {
        $s = $this->solutions->find($id);

        if ($s->ownedByCurrentUser()) {
            // the user is the claiming judge, he can edit this solution
            $s->claiming_judge_id = null;
            $s->solution_state_id = $this->solution_states->firstPendingId();
            if (!$s->save()) {
                App::abort(400, $s->errors());
            }
        } else {
            App::abort(403, 'You are not the claiming judge for this problem');
        }

        return ApiController::formatJSend();
    }

    /**
     * Download route for packages
     * TODO: This is duplicate code from the judge controller, find a way to NOT duplicate
     */
    public function package($id)
    {
        $s = $this->solutions->find($id);

        $solution_package = new SolutionPackage($s);

        return Response::download($solution_package->getPath());
    }

    /**
     * Formats an array in JSEND format
     *
     * @param array $data The data to send to the user
     * @param bool $success If the response is a success
     * @param int $code The HTTP status code of the response. Defaults to 200
     * @param string $message The message to send to the user
     */
    public static function formatJSend($data = array(), $success = true, $code = 200, $message = '')
    {
        // Convert the data to an array if not an instance
        if ($data instanceof ArrayableInterface) {
            $data = $data->toArray();
        }

        return Response::json(array(
            'status' => $success ? 'success' : 'error',
            'code' => $code,
            'message' => $message,
            'data' => $data
        ));
    }
}
