<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class TestLoginController extends Controller
{
    public function index(Request $request)
    {
        $userTypes = ['Student', 'CompanyRepresentative', 'BusinessOperator', 'Candidate', 'CompanyAdmin'];

        $query = User::query();

        // Apply filters
        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        if ($request->filled('email')) {
            $query->where('email', 'like', '%' . $request->email . '%');
        }

        if ($request->filled('user_type')) {
            $query->where('user_type', $request->user_type);
        }

        if ($request->filled('company')) {
            $companyId = $request->company;
            $query->where(function ($q) use ($companyId) {
                $q->whereHas('companyAdmin', function ($q) use ($companyId) {
                    $q->where('company_id', $companyId);
                })->orWhereHas('companyRepresentative', function ($q) use ($companyId) {
                    $q->where('company_id', $companyId);
                });
            });
        }

        $users = $query->with(['companyAdmin.company', 'companyRepresentative.company'])->paginate(15)->withQueryString();

        foreach ($users as $user) {
            $user->display_name = Str::limit($user->name, 25, '');
            $user->company_name = $this->getCompanyName($user);
        }

        $companies = Company::orderBy('name')->pluck('name', 'id');

        return view('test-login', [
            'users' => $users,
            'userTypes' => $userTypes,
            'companies' => $companies,
        ]);
    }

    private function getCompanyName($user)
    {
        if ($user->companyAdmin) {
            return $user->companyAdmin->company->name;
        } elseif ($user->companyRepresentative) {
            return $user->companyRepresentative->company->name;
        }
        return 'N/A';
    }

    public function login($id)
    {
        $user = User::findOrFail($id);
        Auth::login($user);
        return redirect('/'); // Redirect to the appropriate page after login
    }
}
