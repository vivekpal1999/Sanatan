<?php


namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Banner;


class BannerController extends Controller
{
    
    public function index()
    {
        return response()->json(Banner::all());
    }

  

public function store_banner(Request $request)
{
    $data = $request->validate([
        'name'  => 'required|string',
        'topic' => 'required|string',
        'image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
    ]);

    $imageUrl = null;

 if ($request->hasFile('image')) {
        $image = $request->file('image');
        $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();

        // Move the uploaded file to public/images
        $image->move(public_path('images'), $filename);

        // Full URL for browser
        $imageUrl = url('images/' . $filename); // <-- this generates full URL
    }


    $banner = Banner::create([
        'name' => $data['name'],
        'topic' => $data['topic'],
        'image' => $imageUrl,
    ]);

    return response()->json($banner, 201);
}




   
    public function show($id)
    {
        return response()->json(Banner::findOrFail($id));
    }

    
    public function update_banner(Request $request, $id)
    {
        $banner = Banner::findOrFail($id);

        $data = $request->validate([
            'name'      => 'sometimes|string',
            'topic'     => 'sometimes|string',
            'image_url' => 'sometimes|string',
        ]);

        $banner->update($data);

        return response()->json($banner);
    }

  
    public function destroy_banner($id)
    {
        dd("hey");
        $banner = Banner::findOrFail($id);
        $banner->delete();

        return response()->json(['message' => 'Banner deleted successfully']);
    }
}
