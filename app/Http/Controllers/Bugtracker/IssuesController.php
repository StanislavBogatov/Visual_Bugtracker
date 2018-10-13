<?php

namespace App\Http\Controllers\Bugtracker;

use App\Http\Requests\CreateIssueRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\BugtrackerBaseController;

use App\User;
use App\Project;
use App\Issue;

use App\Repositories\IssueRepository;

class IssuesController extends BugtrackerBaseController
{

    public $issue_repository;

    public function __construct(IssueRepository $repository)
    {
        $this->issue_repository = $repository;
    }

    /**
     * Show all issues, assigned to the current project.
     *
     * @param Request $request
     * @param Project $project
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getProjectIssues(Request $request, Project $project)
    {

        $issues = $project->issues()
            ->where('closed', (bool)$request->closed_visible)
            ->getOrdered()
            ->paginate(10);

        $issues->load('discussion','assignedUser', 'creator', 'type', 'priority');

        if ($request->ajax()) {
            return view('bugtracker.project.partials.issues', compact('issues', 'project'));
        }

        return view('bugtracker.project.issues', compact('issues', 'project'));
    }

    /**
     * Create new issue, and store it.
     *
     * @param CreateIssueRequest $request
     * @param Project $project
     */
    public function postCreateIssue(CreateIssueRequest $request, Project $project)
    {
        $project->issues()->create($request->all());
    }

    /**
     * Delete existing issue.
     *
     * @param Project $project
     * @param Issue $issue
     * @return \Illuminate\Http\RedirectResponse
     */
    public function getDeleteIssue(Project $project, Issue $issue)
    {
        $issue->delete($issue);

        return redirect()->back();
    }

    /**
     * Close existing issue.
     *
     * @param Project $project
     * @param Issue $issue
     * @return \Illuminate\Http\RedirectResponse
     */
    public function closeIssue(Project $project, Issue $issue)
    {
        $issue->close();
        session()->flash('message', 'Issue closed!');

        return redirect()->back();
    }

    /**
     * Open existing issue.
     *
     * @param Project $project
     * @param Issue $issue
     * @return \Illuminate\Http\RedirectResponse
     */
    public function openIssue(Project $project, Issue $issue)
    {
        $issue->open();
        session()->flash('message', 'Issue re-opened!');

        return redirect()->back();
    }

}
