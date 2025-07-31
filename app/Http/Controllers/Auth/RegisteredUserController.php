<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        // Get active schools for candidates to choose from
        $schools = \App\Models\School::where('is_active', true)->orderBy('name')->get();
        
        return view('auth.register', compact('schools'));
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'terms' => ['required', 'accepted'],
            'school_id' => ['required', 'exists:schools,id'],
        ]);

        // Get candidate role ID (all registrations are candidates now)
        $roleId = Role::where('name', 'candidate')->first()->id ?? null;
        
        if (!$roleId) {
            // Create the candidate role if it doesn't exist
            $role = Role::create(['name' => 'candidate']);
            $roleId = $role->id;
        }

        // All registrations are candidates and require approval
        $approvalStatus = 'pending';
        
        $userData = [
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => $roleId,
            'approval_status' => $approvalStatus,
        ];
        
        // Set school_id (all registrations are candidates now)
        $school = \App\Models\School::find($request->school_id);
        
        // Only verify that the school is active (capacity will be checked during approval)
        if ($school && $school->is_active) {
            $userData['school_id'] = $school->id;
        } else {
            // Determine the specific error message
            if ($school && !$school->is_active) {
                $errorMessage = 'L\'école sélectionnée n\'accepte pas actuellement de nouvelles inscriptions.';
            } else {
                $errorMessage = 'L\'école sélectionnée n\'est pas disponible. Veuillez choisir une autre école.';
            }
            
            return redirect()->back()->withErrors(['school_id' => $errorMessage])->withInput();
        }

        $user = User::create($userData);

        event(new Registered($user));

        // All registrations are candidates and require approval
        return redirect()->route('registration.pending');
    }
    
    /**
     * Display the registration pending approval page.
     */
    public function showPendingApproval(): View
    {
        return view('auth.pending-approval');
    }
}
