<?php

namespace App\Http\Controllers;

use App\Models\TeamDivision;
use App\Models\TeamMember;
use Illuminate\Http\Request;

class TeamController extends Controller
{
    /**
     * Dedicated Admin Team & Divisions Management View
     */
    public function adminIndex()
    {
        $divisions = TeamDivision::with('members')->orderBy('order')->orderBy('id')->get();
        $teamMembersCount = TeamMember::count();

        return view('admin.team.index', compact('divisions', 'teamMembersCount'));
    }

    /**
     * Store a newly created team division.
     */
    public function storeDivision(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'order' => 'nullable|integer',
        ]);

        if (!isset($validated['order']) || $validated['order'] === null) {
            $maxOrder = TeamDivision::max('order') ?? 0;
            $validated['order'] = $maxOrder + 1;
        }

        $division = TeamDivision::create($validated);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Team division added successfully!',
                'division' => $division->load('members'),
            ]);
        }

        return redirect()->route('admin.team.index')->with('message', "Division '{$division->name}' created successfully!");
    }

    /**
     * Update an existing team division.
     */
    public function updateDivision(Request $request, TeamDivision $division)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'order' => 'nullable|integer',
        ]);

        $division->update($validated);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Team division updated successfully!',
                'division' => $division->load('members'),
            ]);
        }

        return redirect()->route('admin.team.index')->with('message', "Division '{$division->name}' updated!");
    }

    /**
     * Remove the specified team division.
     */
    public function destroyDivision(Request $request, TeamDivision $division)
    {
        $name = $division->name;
        $division->delete();

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => "Division '{$name}' deleted successfully!",
            ]);
        }

        return redirect()->route('admin.team.index')->with('message', "Division '{$name}' removed.");
    }

    /**
     * Store a newly created team member with complete naming fields.
     */
    public function storeMember(Request $request, \App\Services\SupabaseStorageService $storageService)
    {
        $validated = $request->validate([
            'team_division_id' => 'required|exists:team_divisions,id',
            'name' => 'required|string|max:255',
            'role' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'linkedin' => 'nullable|string|max:500',
            'avatar' => 'nullable|string|max:500',
            'avatar_file' => 'nullable|image|max:3072',
            'bio' => 'nullable|string|max:1000',
            'order' => 'nullable|integer',
        ]);

        if ($request->hasFile('avatar_file')) {
            $validated['avatar'] = $storageService->uploadAvatar($request->file('avatar_file'));
        }

        if (!isset($validated['order']) || $validated['order'] === null) {
            $maxOrder = TeamMember::where('team_division_id', $validated['team_division_id'])->max('order') ?? 0;
            $validated['order'] = $maxOrder + 1;
        }

        unset($validated['avatar_file']);

        $member = TeamMember::create($validated);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => "Member '{$member->name}' added successfully!",
                'member' => $member,
            ]);
        }

        return redirect('/admin/team#division-' . $member->team_division_id)->with('message', "Member '{$member->name}' added successfully!");
    }

    /**
     * Update the specified team member.
     */
    public function updateMember(Request $request, TeamMember $member, \App\Services\SupabaseStorageService $storageService)
    {
        $validated = $request->validate([
            'team_division_id' => 'sometimes|required|exists:team_divisions,id',
            'name' => 'required|string|max:255',
            'role' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'linkedin' => 'nullable|string|max:500',
            'avatar' => 'nullable|string|max:500',
            'avatar_file' => 'nullable|image|max:3072',
            'bio' => 'nullable|string|max:1000',
            'order' => 'nullable|integer',
        ]);

        if ($request->hasFile('avatar_file')) {
            $validated['avatar'] = $storageService->uploadAvatar($request->file('avatar_file'));
        }

        unset($validated['avatar_file']);

        $member->update($validated);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => "Member '{$member->name}' updated successfully!",
                'member' => $member,
            ]);
        }

        return redirect('/admin/team#division-' . $member->team_division_id)->with('message', "Member '{$member->name}' updated!");
    }

    /**
     * Remove the specified team member.
     */
    public function destroyMember(Request $request, TeamMember $member)
    {
        $name = $member->name;
        $divId = $member->team_division_id;
        $member->delete();

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => "Member '{$name}' removed successfully!",
            ]);
        }

        return redirect('/admin/team#division-' . $divId)->with('message', "Member '{$name}' removed.");
    }
}
