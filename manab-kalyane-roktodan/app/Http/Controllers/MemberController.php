<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\User;

class MemberController extends Controller
{
    public function index()
    {
        $committeeRoles = \App\Models\Role::where('show_in_member_page', true)->pluck('name')->toArray();
        $committeeUsers = User::whereIn('role', $committeeRoles)->get();
        foreach ($committeeUsers as $u) {
            $u->syncCommitteeMemberStatus();
        }

        $members = Member::where('is_active', true)->orderBy('sort_order')->get();
        return view('members', compact('members'));
    }
}
