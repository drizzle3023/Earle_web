<?php

namespace App\Http\Controllers;

use App\Http\Models\Admin;
use App\Http\Models\Company;
use App\Http\Models\Image;
use App\Http\Models\JobNumber;
use App\Http\Models\User;
use App\Http\Utils\Utils;
use Illuminate\Queue\Jobs\Job;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Validator;
use Illuminate\Http\Request;

class AdminController extends Controller
{

    public function index() {

        $admin = session()->get('admin');

        if (isset($admin)) {
            return view('dashboard');
        }

        return view('login');

    }

    public function doLogin() {

        $email = request('email');
        $password = request('password');


        if (!isset($email)) {
            session()->flash('error-msg', 'Please enter valid email.');
            return redirect()->back();
        }
        if (!isset($password)) {
            session()->flash('error-msg', 'Please enter valid password.');
            return redirect()->back();
        }

        $admin = Admin::where('email', $email)->first();

        if (!isset($admin)) {
            session()->flash('error-msg', 'User not found.');
            return redirect()->back();
        }

        if (!hash::check($password, $admin->password)) {
            session()->flash('error-msg', 'Invalid password.');
            return redirect()->back();
        }

        session()->put('admin', $admin);
        return redirect('/');
    }

    public function logout() {

        session()->remove('admin');
        return redirect('/login');
    }

    public function editProfile() {

        $oldPassword = request('oldPassword');
        $newPassword = request('newPassword');

        $email = session()->get('admin')->email;
        $admin = Admin::where('email', $email)->first();

        if (hash::check($oldPassword, $admin->password)) {

            Admin::where('email',$email)->update(['password' => hash::make($newPassword)]);
            $admin = Admin::where('email', $email)->first();
            session()->put('admin', $admin);

            return Utils::makeResponse();

        } else {
            return Utils::makeResponse([], 'Password is not correct.', false);
        }

    }

    public function showAdminlistPage() {

        $admins = Admin::all();
        return view('adminlist')->with('admins', $admins);

    }

    public function addAdmin() {

        $name = request('val-username');
        $email = request('val-email');
        $password = request('val-password');

        $admin = new Admin();
        $admin->name = $name;
        $admin->email = $email;
        $admin->password = hash::make($password);

        $admin->save();

        return redirect('/admin');
    }

    public function editAdmin() {

        $id = request('admin-id');
        $name = request('val-username');
        $email = request('val-email');
        $password = request('val-password');

        Admin::where('id', $id)
            ->update([
                'name' => $name,
                'email' => $email,
                'password' => hash::make($password)
            ]);

        return redirect('/admin');

    }

    public function getAdmin() {
        $id = request('id');
        $admin = Admin::where('id', $id)->get();
        return Utils::makeResponse(['admin' => $admin]);
    }

    public function delAdmin() {

        $id = request('id');

        Admin::where('id', $id)->delete();
        return Utils::makeResponse();

    }

    public function showUserlistPage() {
        $users = User::with('company')->get();
        $companies = Company::all();
        return view('userlist', ["users" => $users, "companies" => $companies]);
    }

    public function addUser() {

        $name = request('val-username');
        $email = request('val-email');
        $password = request('val-password');
        $company = request('val-company');
        $role = request('val-role');

        $user = new User();
        $user->name = $name;
        $user->email = $email;
        $user->password = hash::make($password);
        $user->company_id = $company;
        $user->role = $role;

        $user->save();

        return redirect('/user');
    }

    public function editUser() {

        $id = request('user-id');
        $name = request('val-username');
        $email = request('val-email');
        $password = request('val-password');
        $company = request('val-company');
        $role = request('val-role');

        User::where('id', $id)
            ->update([
                'name' => $name,
                'email' => $email,
                'password' => hash::make($password),
                'company_id' => $company,
                'role' => $role
            ]);

        return redirect('/user');

    }

    public function getUser() {
        $id = request('id');
        $user = User::where('id', $id)->get();
        return Utils::makeResponse(['user' => $user]);
    }

    public function delUser() {

        $id = request('id');

        User::where('id', $id)->delete();
        Image::where('user_id', $id)->delete();

        return Utils::makeResponse();

    }


    public function showImagelistPage() {

        $images = Image::with('user', 'user.company', 'jobnumber')->get();
        return view('imagelist')->with('images', $images);
    }

    public function getImage() {
        $id = request('id');
        $image = Image::where('id', $id)->get();
        return Utils::makeResponse(['image' => $image]);
    }

    public function delImage() {

        $id = request('id');

        Image::where('id', $id)->delete();
        return Utils::makeResponse();

    }



    // Compnay

    public function showCompanylistPage() {

        $companies = Company::all();
        return view('companylist')->with('companies', $companies);

    }

    public function addCompany() {

        $name = request('val-companyname');

        $company = new Company();
        $company->name = $name;

        $company->save();

        return redirect('/company');
    }

    public function editCompany() {

        $id = request('company-id');
        $name = request('val-companyname');

        Company::where('id', $id)
            ->update([
                'name' => $name,
            ]);

        return redirect('/company');

    }

    public function getCompany() {
        $id = request('id');
        $company = Company::where('id', $id)->get();
        return Utils::makeResponse(['company' => $company]);
    }

    public function delCompany() {

        $id = request('id');

        $company = Company::where('id', $id)->first();

        if ($company == null) {
            return Utils::makeResponse();
        }

        $users = User::where('company_id', $id)->get()->toArray();

        //Log::info($users);
        $user_id_array = array_map(function($user) {
            return $user["id"];
        }, $users);

        Image::whereIn('user_id', $user_id_array)->delete();
        User::where('company_id', $id)->delete();

        $job_numbers = JobNumber::where('company_id', $id)->get()->toArray();
        $job_number_id_array = array_map(function($job_number) {
            return $job_number["id"];
        }, $job_numbers);
        Image::whereIn('jobnumber_id', $job_number_id_array)->delete();
        JobNumber::where('company_id', $id)->delete();

        Company::where('id', $id)->delete();

        return Utils::makeResponse();

    }



    // Job Number
    public function showJobNumberPage() {

        $jobNumbers = JobNumber::with('company')->get();
        $companies = Company::all();
        return view('jobnumber')->with(['jobnumbers' => $jobNumbers, 'companies' => $companies]);

    }

    public function addJobNumber() {

        $name = request('val-jobnumber');
        $comapny_id = request('val-companyid');

        $jobnumber = new JobNumber();
        $jobnumber->jobnumber = $name;
        $jobnumber->company_id = $comapny_id;

        $jobnumber->save();

        return redirect('/jobnumber');
    }

    public function editJobNumber() {

        $id = request('jobnumber-id');
        $jobnumber = request('val-jobnumber');
        $companyid = request('val-companyid');

        JobNumber::where('id', $id)
            ->update([
                'jobnumber' => $jobnumber,
                'company_id' => $companyid
            ]);

        return redirect('/jobnumber');

    }

    public function getJobNumber() {
        $id = request('id');
        $jobnumber = JobNumber::where('id', $id)->get();
        return Utils::makeResponse(['jobnumber' => $jobnumber]);
    }

    public function delJobNumber() {

        $id = request('id');

        JobNumber::where('id', $id)->delete();
        Image::where('id', $id)->delete();

        return Utils::makeResponse();

    }
}
