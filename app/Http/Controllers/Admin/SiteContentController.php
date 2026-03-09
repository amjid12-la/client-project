<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteContent;
use Illuminate\Http\Request;

class SiteContentController extends Controller
{
    public function index()
    {
        $contents = SiteContent::orderBy('section')->get();
        return view('pages.apps.site-content.index', compact('contents'));
    }

    public function edit($id)
    {
        $content = SiteContent::findOrFail($id);
        return view('pages.apps.site-content.edit', compact('content'));
    }

    public function update(Request $request, $id)
    {
        $content = SiteContent::findOrFail($id);
        
        $validated = $request->validate([
            'title' => 'nullable|string|max:255',
            'subtitle' => 'nullable|string|max:500',
            'content' => 'nullable|string',
            'contact_title' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'background_color' => 'nullable|string|max:7',
            'text_color' => 'nullable|string|max:7',
            'hero_text_color' => 'nullable|string|max:7',
            'button_bg_color' => 'nullable|string|max:7',
            'button_text_color' => 'nullable|string|max:7',
            'navbar_bg_color' => 'nullable|string|max:7',
            'navbar_text_color' => 'nullable|string|max:7',
            'navbar_button_bg_color' => 'nullable|string|max:7',
            'navbar_button_text_color' => 'nullable|string|max:7',
            'info_text_color' => 'nullable|string|max:7',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:50',
            'location' => 'nullable|string|max:255',
        ]);
        
        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($content->image && file_exists(public_path('frontend/images/' . $content->image))) {
                unlink(public_path('frontend/images/' . $content->image));
            }
            
            // Upload new image
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('frontend/images'), $imageName);
            $validated['image'] = $imageName;
        }
        
        $content->update($validated);
        
        return redirect()->route('admin.site-content.index')
            ->with('success', 'Content updated successfully!');
    }

    public function destroy($id)
    {
        $content = SiteContent::findOrFail($id);
        $content->delete();
        
        return redirect()->route('admin.site-content.index')
            ->with('success', 'Content deleted successfully!');
    }

    public function removeImage($id)
    {
        $content = SiteContent::findOrFail($id);
        
        // Delete image file if exists
        if ($content->image && file_exists(public_path('frontend/images/' . $content->image))) {
            unlink(public_path('frontend/images/' . $content->image));
        }
        
        // Update database
        $content->image = null;
        $content->save();
        
        return response()->json([
            'success' => true,
            'message' => 'Image removed successfully!'
        ]);
    }
}
