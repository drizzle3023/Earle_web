<?php

namespace App\Http\Controllers;

use App\Http\Models\Company;
use App\Http\Models\Image;
use App\Http\Models\JobNumber;
use App\Http\Models\User;
use App\Http\Utils\Utils;
use Hamcrest\Util;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Validator;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image as FacadesImage;

class ApiController extends Controller
{
    //

    public function doLogin()
    {
        $validation = Validator::make(request()->all(), [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if ($validation->fails()) {
            return Utils::makeResponse([], config('constants.response-message.invalid-params'));
        }

        $credentials = request(['email', 'password']);

        $user = User::where('email', $credentials['email'])->with('company')->first();

        if ($user == null) {
            return Utils::makeResponse([], config('constants.response-message.invalid-credentials'));
        }
        if (!Hash::check($credentials['password'], $user->password)) {
            return Utils::makeResponse([], config('constants.response-message.invalid-credentials'));
        }
        if (!$token = auth()->attempt($credentials)) {
            return Utils::makeResponse([], config('constants.response-message.error-generate-api-token'));
        }

        $user = $user->setHidden([
            'password'
        ]);

        return Utils::makeResponse([
            'api_token' => $token,
            'user' => $user,
        ]);
    }

    public function doUpload()
    {


//        request()->validate([
//            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
//            'lat' => 'required',
//            'lan' => 'required'
//        ]);

        $image_filename = time() . '.png';

        $original_image_path = public_path('images/original');
        if (!file_exists($original_image_path)) {
            mkdir($original_image_path);
        }

        $marker_image_path = public_path('images/marker');
        if (!file_exists($marker_image_path)) {
            mkdir($marker_image_path);
        }

        $thumbnail_image_path = public_path('images/thumbnail');
        if (!file_exists($thumbnail_image_path)) {
            mkdir($thumbnail_image_path);
        }

        // save original image
        request()->image->move($original_image_path, $image_filename);

        // generate marker image
        $original_image = FacadesImage::make($original_image_path . DIRECTORY_SEPARATOR . $image_filename);

        // create empty canvas
        $img = FacadesImage::canvas(128, 128);
        $img->encode('png');

        // fill image with color
        $img->fill('#ffffff');

        $mask_file_path = storage_path('app/public/marker_mask.png');

        // fill image with tiled image
        $img->insert(
            $original_image
                ->fit(128, 128)
                ->mask($mask_file_path, true)
                ->resize(112, 112),
            'top-left',
            8,
            8)
            ->mask($mask_file_path, true)
            ->save($marker_image_path . DIRECTORY_SEPARATOR . $image_filename);

        // generate thumbnail image
        FacadesImage::make($original_image_path . DIRECTORY_SEPARATOR . $image_filename)
            ->fit(320, 320)
            ->save($thumbnail_image_path . DIRECTORY_SEPARATOR . $image_filename);


        //request()->image->move(public_path('images'), $thumb_100_name);

        $lat = request('lat');
        $lan = request('lan');
        $title = request('title');
        $jobNo_id = request('jobNo_id');
        $route = request('route');
        $description = request('description');
        $asset = request('asset');
        $comment = request('comment');
        $urgency = request('urgency');
        $upload_timestamp = request('upload_timestamp');
        $user = request('user');

        $image = new Image();
        $image->user_id = $user->id;
        $image->filename = $image_filename;
        $image->latitude = $lat;
        $image->longitude = $lan;
        $image->jobnumber_id = $jobNo_id;
        $image->title = $title;
        $image->route = $route;
        $image->description = $description;
        $image->asset = $asset;
        $image->comment = $comment;
        $image->urgency = $urgency;
        $image->upload_timestamp = $upload_timestamp;

        $image->save();

        return Utils::makeResponse([
            'image' => $image_filename
        ], config('constants.response-message.success'));

    }

    public function doFetchImages()
    {

        $shownCount = request('shownCount');
        $images = Image::offset($shownCount)->limit(10)->with('user')->get();
        // $images = Image::all();
        $total = Image::count();

        return Utils::makeResponse([
            'images' => $images,
            'total' => $total
        ], config('constants.response-message.success'));

    }

    public function doSearch()
    {

        $title = request('title');
        $route = request('route');
        $description = request('description');
        $comment = request('comment');
        $job_id = request('job_id');
        $start_date = request('start_date');
        $end_date = request('end_date');
        $shown_count = request('shown_count');

        $where_clause = [];
        if ($title != "" && isset($title)) {
            $where_clause[] = ['title', 'like', "%$title%"];
        }
        if ($route != "" && isset($route)) {
            $where_clause[] = ['route', 'like', "%$route%"];
        }
        if ($description != "" && isset($description)) {
            $where_clause[] = ['description', 'like', "%$description%"];
        }
        if ($comment != "" && isset($comment)) {
            $where_clause[] = ['comment', 'like', "%$comment%"];
        }
        if ($start_date != 0 && isset($start_date)) {
            $where_clause[] = ['upload_timestamp', '>=', "%$start_date%"];
        }
        if ($end_date != 0 && isset($end_date)) {
            $where_clause[] = ['upload_timestamp', '<=', "%$end_date%"];
        }

        $images = null;
        if ($job_id == 0) {
            $images = Image::where($where_clause)->offset($shown_count)->limit(10)->with('user')->get();
            $total = Image::where($where_clause)->count();
        } else {
            $where_clause[] = ['jobnumber_id', $job_id];
            $images = Image::where($where_clause)->offset($shown_count)->limit(10)->with('user')->get();
            $total = Image::where($where_clause)->count();
        }

        return Utils::makeResponse([
            'images' => $images,
            'total' => $total
        ], config('constants.response-message.success'));
    }

    public function getJobNumbers()
    {
        $user = request('user');
        $jobnumber_array = [];
        if ($user->role == 'SUPER') {
            $jobnumber_array = JobNumber::orderby('company_id')->with('company')->get();
        } else {
            $jobnumber_array = JobNumber::where('company_id', $user->company_id)->with('company')->get();
        }
        return Utils::makeResponse([
            'jobnumbers' => $jobnumber_array
        ], config('constants.response-message.success')
        );
    }

    public function getCompanyList()
    {
        $company_list = Company::with('jobNumbers')->get();
        return Utils::makeResponse([
            'company_list' => $company_list
        ], config('constants.response-message.success')
        );
    }

    public function updateImage()
    {

//        request()->validate([
//            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
//            'lat' => 'required',
//            'lan' => 'required'
//        ]);

//        $imageName = time().'.'.request()->image->getClientOriginalExtension();
//
//        request()->image->move(public_path('images'), $imageName);

        $lat = request('lat');
        $lan = request('lan');
        $title = request('title');
        $jobNo_id = request('job_id');
        $route = request('route');
        $description = request('description');
        $asset = request('asset');
        $comment = request('comment');
        $urgency = request('urgency');
        $upload_timestamp = request('upload_timestamp');
        $image_id = request('image_id');
        $user = request('user');

//        $image->filename =  $imageName;

        Image::where('id', $image_id)->update([
            'latitude' => $lat,
            'longitude' => $lan,
            'jobnumber_id' => $jobNo_id,
            'title' => $title,
            'route' => $route,
            'description' => $description,
            'asset' => $asset,
            'comment' => $comment,
            'urgency' => $urgency,
            'upload_timestamp' => $upload_timestamp,
        ]);

        return Utils::makeResponse([
        ], config('constants.response-message.success'));

    }
}
