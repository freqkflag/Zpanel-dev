<?php

namespace App\Http\Controllers;

use App\Services\IDEService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IDEController extends Controller
{
    public function __construct(
        private IDEService $ideService
    ) {
        $this->middleware('auth');
    }

    /**
     * Display IDE interface
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $workspace = $request->get('workspace', 'default');
        $projectId = $request->get('project_id');

        $token = $this->ideService->generateToken($user->id);
        $workspacePath = $this->ideService->getWorkspacePath($user->id, $projectId);
        $ideUrl = $this->ideService->getIDEUrl($token, $workspacePath);

        return view('ide.index', [
            'ideUrl' => $ideUrl,
            'workspace' => $workspace,
            'token' => $token,
        ]);
    }

    /**
     * List user workspaces
     */
    public function workspaces()
    {
        $user = Auth::user();

        // TODO: Implement workspace listing
        return response()->json([]);
    }

    /**
     * Create new workspace
     */
    public function createWorkspace(Request $request)
    {
        $user = Auth::user();
        $workspaceName = $request->input('name');

        // TODO: Implement workspace creation
        return response()->json(['message' => 'Workspace created']);
    }
}
