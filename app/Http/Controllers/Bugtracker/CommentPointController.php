<?php

namespace App\Http\Controllers\Bugtracker;

use App\Board;
use App\Project;

use Illuminate\Http\Request;

use App\Http\Controllers\BugtrackerBaseController;

class CommentPointController extends BugtrackerBaseController
{
    /**
     * Display a listing of the comment points for the board.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Project $project, Board $board)
    {
        $boardCommentPoints = $board->commentPoints()->get();
        return response($boardCommentPoints, 200);
    }

    /**
     * Display the specified comment point of the board.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Project $project, Board $board, $id)
    {
        $commentPoints = $board->commentPoints()->find($id);
        return response($commentPoints, 200);
    }

    /**
     * Store a newly created comment point.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Project $project, Board $board)
    {
        $commentPoint = $board->commentPoints()->create($request->all()+['user_id'=>auth()->user()->id]);
        $commentPoint->load('creator');
        return response($commentPoint, 200);
    }

    /**
     * Update the specified comment point.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Project $project, Board $board, $id)
    {
        $board->commentPoints()->find($id)->update($request->all());
        return response("", 200);
    }

    /**
     * Remove the specified comment point from the board.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Project $project, Board $board, $id)
    {
        $board->commentPoints()->find($id)->delete();
        return response("", 200);
    }
}