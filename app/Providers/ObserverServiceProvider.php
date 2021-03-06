<?php

namespace App\Providers;

use App\CommentPoint;
use App\IssueDiscussion;
use App\Observers\CommentPointObserver;
use App\Observers\UserObserver;
use App\Project;
use App\Board;
use App\Path;
use App\Issue;

use App\Observers\IssueDiscussionObserver;
use App\Observers\Project\ProjectObserver;
use App\Observers\Board\BoardObserver;
use App\Observers\IssueObserver;
use App\Observers\PathObserver;


use App\Services\ImageUpload\AbstractFileUploadService;
use App\Services\ImageUpload\ProjectImageUploadService;

use App\Services\ImageUpload\Board\BoardImageUploadService;
use App\Services\ImageUpload\Board\BoardThumbnailDecorator;

use App\Observers\Project\ProjectImageObserver;
use App\Observers\Board\BoardImageObserver;

use App\User;
use Illuminate\Support\ServiceProvider;

class ObserverServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        User::observe(UserObserver::class);
        Project::observe([
            ProjectObserver::class,
            ProjectImageObserver::class
        ]);
        Board::observe([
            BoardObserver::class,
            BoardImageObserver::class
        ]);

        Issue::observe(IssueObserver::class);
        IssueDiscussion::observe(IssueDiscussionObserver::class);
        Path::observe(PathObserver::class);

        CommentPoint::observe(CommentPointObserver::class);
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        /*
         * Observers
         */
        app()->when(ProjectImageObserver::class)->needs(AbstractFileUploadService::class)
            ->give(function() {
                return new ProjectImageUploadService(config('images.project_thumb_dir'));
            });
        app()->when(BoardImageObserver::class)->needs(AbstractFileUploadService::class)
            ->give(function() {
                $defaultDir = config('images.boards_images_dir');
                $uploadService = new BoardImageUploadService($defaultDir);

                return new BoardThumbnailDecorator($defaultDir, $uploadService, config('images.thumbnail_prefix'));
            });
    }
}
