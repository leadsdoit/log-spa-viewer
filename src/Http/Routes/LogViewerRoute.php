<?php

declare(strict_types=1);

namespace Ldi\LogViewer\Http\Routes;

use Ldi\LogViewer\Http\Controllers\LogViewerController;
use src\Routing\RouteRegistrar;

class LogViewerRoute extends RouteRegistrar
{
    /**
     * Map all routes.
     */
    public function map(): void
    {
        $attributes = (array) config('log-viewer.route.attributes');

        $this->group($attributes, function() {
            $this->name('log-viewer::')->group(function () {
                $this->get('/', [LogViewerController::class, 'index'])
                     ->name('dashboard'); // log-viewer::dashboard

                $this->mapLogsRoutes();
            });
        });
    }

    /**
     * Map the logs routes.
     */
    private function mapLogsRoutes(): void
    {
        $this->prefix('logs')->name('logs.')->group(function() {

            $this->get('/', [LogViewerController::class, 'index']);

            $this->get('/levels', [LogViewerController::class, 'getLevels']);

            $this->delete('delete', [LogViewerController::class, 'delete'])
                 ->name('delete'); // log-viewer::logs.delete

            $this->prefix('{date}')->group(function() {
                $this->get('/', [LogViewerController::class, 'show']);

                $this->get('download', [LogViewerController::class, 'download']);
            });
        });
    }
}
